<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Models\Book;
use App\Models\Cart; // Tambahkan ini
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        $previewBooks = Book::latest()->take(4)->get();
        return view('dashboard', compact('previewBooks'));
    })->name('dashboard');

    Route::get('/katalog', function (Request $request) {
        $query = Book::query();

        // Filter: Pencarian keyword (judul atau penulis)
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                  ->orWhere('author', 'like', '%' . $keyword . '%');
            });
        }

        // Filter: Harga minimum
        if ($request->filled('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', (int) $request->min_price);
        }

        // Filter: Harga maksimum
        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', (int) $request->max_price);
        }

        // Filter: Hanya tampilkan buku yang masih ada stok
        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        // Pengurutan
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'title_asc'  => $query->orderBy('title', 'asc'),
            default      => $query->latest(),
        };

        $books        = $query->get();
        $totalBooks   = Book::count(); // total semua buku di DB
        $activeFilter = $request->filled('search') || $request->filled('min_price') || $request->filled('max_price') || $request->boolean('in_stock') || ($sort !== 'latest');

        return view('katalog', compact('books', 'totalBooks', 'activeFilter'));
    })->name('katalog');

    // ---- CRUD BUKU ADMIN ----
    // Tambah Buku
    Route::post('/books', function (Request $request) {
        if(Auth::user()->role !== 'admin') abort(403);
        $validated = $request->validate(['title' => 'required', 
        'author' => 
        'required', 
        'price' => 'required|integer', 
        'stock' => 'required|integer',
        'description' => 'nullable',
        'genres' => 'nullable|array'
        ]);
        Book::create($validated);
        return back()->with('success', 'Buku baru berhasil ditambahkan!');
    });
    
    // Delete Buku
    Route::delete('/books/{id}', function ($id) {
        if(Auth::user()->role !== 'admin') abort(403);
        Book::findOrFail($id)->delete();
        return back()->with('success', 'Buku berhasil dihapus!');
    });

    // Edit Buku
    Route::get('/books/{id}/edit', function ($id) {
        if(Auth::user()->role !== 'admin') abort(403);
        
        $book = Book::findOrFail($id);
        return view('form-edit', compact('book')); 
    });

    // Update Data Buku di database
    Route::put('/books/{id}', function (Request $request, $id) {
        if(Auth::user()->role !== 'admin') abort(403);
        
        $validated = $request->validate([
            'title' => 'required', 
            'author' => 'required', 
            'price' => 'required|integer', 
            'stock' => 'required|integer',
            'description' => 'nullable',
            'genres' => 'nullable|array'
        ]);

        Book::findOrFail($id)->update($validated);
        return redirect('/katalog')->with('success', 'Buku berhasil diperbarui!');
    });

    // ---- FITUR USER: KERANJANG & CHECKOUT ----
    // 1. Tampilkan Halaman Keranjang
    Route::get('/keranjang', function () {
        if(Auth::user()->isAdmin()) abort(403); // Admin tidak punya keranjang
        $carts = Cart::with('book')->where('user_id', Auth::id())->get();
        return view('keranjang', compact('carts'));
    })->name('keranjang');

    // 2. Tambah Buku ke Keranjang
    Route::post('/cart/{book_id}', function ($book_id) {
        if(Auth::user()->isAdmin()) abort(403);
        
        $book = Book::findOrFail($book_id);
        if ($book->stock < 1) return back()->with('error', 'Maaf, stok buku habis!');

        $cart = Cart::where('user_id', Auth::id())->where('book_id', $book_id)->first();
        if ($cart) {
            $cart->increment('quantity'); // Jika sudah ada, tambah jumlahnya
        } else {
            Cart::create(['user_id' => Auth::id(), 'book_id' => $book_id, 'quantity' => 1]);
        }
        return back()->with('success', 'Buku dimasukkan ke keranjang!');
    });

    // 3. Hapus dari keranjang
    Route::delete('/cart/{id}', function ($id) {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Dihapus dari keranjang.');
    });

    Route::post('/cart/{id}/update', function (Illuminate\Http\Request $request, $id) {
        // Cari item keranjang milik user yang login
        $cart = App\Models\Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $book = $cart->book;

        if ($request->action === 'increase') {
            // Cek apakah stok masih cukup jika ditambah
            if ($book->stock > $cart->quantity) {
                $cart->increment('quantity');
            } else {
                return back()->with('error', 'Stok buku tidak mencukupi!');
            }
        } elseif ($request->action === 'decrease') {
            // Jika jumlah lebih dari 1, kurangi. Jika 1, hapus dari keranjang.
            if ($cart->quantity > 1) {
                $cart->decrement('quantity');
            } else {
                $cart->delete();
                return back()->with('success', 'Buku dihapus dari keranjang.');
            }
        }
        
        return back();
    });

    // 4. Proses Pembelian (Checkout)
    Route::post('/checkout', function (Illuminate\Http\Request $request) {
        // Validasi data array quantities
        $request->validate([
            'quantities' => 'required|array',
        ]);

        Illuminate\Support\Facades\DB::beginTransaction(); 

        try {
            // 1. BUAT DATA ORDER BARU DULU
            $order = App\Models\Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending', // Status awal
                'payment_code' => 'VA' . rand(10000000, 99999999), // Generate kode VA dummy/otomatis
                'total_price' => 0 // Set 0 dulu, nanti dihitung
            ]);

            $totalPrice = 0;

            foreach ($request->quantities as $cartId => $qtyToBuy) {
                // Cari item keranjangnya
                $cartItem = App\Models\Cart::findOrFail($cartId);
                $book = $cartItem->book;

                if ($book->stock < $qtyToBuy) {
                    throw new Exception("Gagal! Stok buku '{$book->title}' tidak mencukupi.");
                }

                // 2. PINDAHKAN DATA KE ORDER_ITEMS
                App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $book->id,
                    'quantity' => $qtyToBuy,
                    'price' => $book->price // Kunci harga saat beli
                ]);

                // Hitung total harga
                $totalPrice += ($book->price * $qtyToBuy);

                // 3. Kurangi stok buku & hapus keranjang
                $book->decrement('stock', $qtyToBuy);
                $cartItem->delete();
            }

            // 4. UPDATE TOTAL HARGA DI TABEL ORDER
            $order->update(['total_price' => $totalPrice]);

            Illuminate\Support\Facades\DB::commit();
            
            // Redirect langsung ke halaman akun agar user bisa lihat pesanannya
            return redirect('/account')->with('success', 'Checkout berhasil! Silakan cek detail pesanan Anda.');

        } catch (Exception $e) {
            Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    });

    // ---- HALAMAN DETAIL BUKU ----
    Route::get('/books/{id}', function ($id) {
        $book = App\Models\Book::findOrFail($id);
        return view('detail-buku', compact('book')); 
    });

    // ---- FITUR REVIEW BUKU ----
    // 1. Menampilkan halaman review (Dengan Fitur Sorting)
    Route::get('/books/{id}/reviews', function (Request $request, $id) {
        $book = Book::findOrFail($id);
        
        // Cek apakah ada request pengurutan dari URL (?sort=terlama)
        $sort = $request->query('sort', 'terbaru');
        
        $query = App\Models\Review::with('user')->where('book_id', $id);
        
        // Logika Sorting
        if ($sort === 'terlama') {
            $query->oldest(); // Paling lama di atas
        } else {
            $query->latest(); // Paling baru di atas (default)
        }
        
        $reviews = $query->get();
        return view('reviews', compact('book', 'reviews', 'sort')); 
    });

    // 2. Memproses form kirim review
    Route::post('/books/{id}/reviews', function (Request $request, $id) {
        // Keamanan ekstra: Tolak jika admin memaksa kirim data POST
        if(Auth::user()->role === 'admin') abort(403, 'Admin tidak boleh menulis ulasan.');

        $request->validate(['rating' => 'required|integer|min:1|max:5', 'comment' => 'required']);

        App\Models\Review::create([
            'user_id' => Auth::id(), 'book_id' => $id, 'rating' => $request->rating, 'comment' => $request->comment
        ]);
        return back()->with('success', 'Ulasan berhasil ditambahkan!');
    });

    // 3. Hapus Review (KHUSUS ADMIN)
    Route::delete('/reviews/{id}', function ($id) {
        if(Auth::user()->role !== 'admin') abort(403);
        App\Models\Review::findOrFail($id)->delete();
        return back()->with('success', 'Ulasan berhasil dihapus!');
    });

    // ---- HALAMAN ACCOUNT & RIWAYAT PESANAN ----
    Route::get('/account', function () {
        if (class_exists('\App\Models\Order')) {
            if (Auth::user()->role === 'admin') {
                // Jika ADMIN: Ambil SEMUA data pesanan dari semua user
                $orders = \App\Models\Order::with(['items.book', 'user'])->latest()->get();
            } else {
                // Jika USER BIASA: Hanya ambil pesanannya sendiri
                $orders = \App\Models\Order::with('items.book')->where('user_id', Auth::id())->latest()->get();
            }
        } else {
            $orders = collect(); 
        }
                  
        return view('account', compact('orders'));
    })->name('account')->middleware('auth');

    // ---- PROSES KONFIRMASI PESANAN (OLEH ADMIN) ----
    Route::post('/admin/orders/{id}/confirm', function ($id) {
        if(Auth::user()->role !== 'admin') abort(403);
        
        $order = \App\Models\Order::findOrFail($id);
        $order->update(['status' => 'completed']); // Ubah status jadi selesai
        
        return back()->with('success', 'Pesanan berhasil dikonfirmasi dan diselesaikan!');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});