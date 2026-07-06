<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Models\Book;
use App\Models\Cart; // Tambahkan ini
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $books = Book::latest()->get();
        $orders = Order::with('items.book')->where('user_id', Auth::id())->latest()->get();
        return view('dashboard', compact('books', 'orders'));
    })->name('dashboard');

    Route::get('/katalog', function () {
        return redirect()->route('dashboard');
    })->name('katalog');

    // ---- CRUD BUKU ADMIN ----
    Route::post('/books', function (Request $request) {
        if(Auth::user()->role !== 'admin') abort(403);
        $validated = $request->validate(['title' => 'required', 'author' => 'required', 'price' => 'required|integer', 'stock' => 'required|integer']);
        Book::create($validated);
        return back()->with('success', 'Buku baru berhasil ditambahkan!');
    });

    Route::delete('/books/{id}', function ($id) {
        if(Auth::user()->role !== 'admin') abort(403);
        Book::findOrFail($id)->delete();
        return back()->with('success', 'Buku berhasil dihapus!');
    });

    // ---- FITUR USER: KERANJANG & CHECKOUT ----
    // 1. Tampilkan Halaman Keranjang
    Route::get('/keranjang', function () {
        if(Auth::user()->isAdmin()) abort(403); // Admin tidak punya keranjang
        $carts = Cart::with('book')->where('user_id', Auth::id())->get();
        return view('keranjang', compact('carts'));
    })->name('keranjang');

    // 2. Tambah Buku ke Keranjang dengan quantity khusus
    Route::post('/cart/{book_id}', function (Request $request, $book_id) {
        if(Auth::user()->isAdmin()) abort(403);
        
        $book = Book::findOrFail($book_id);
        $qty = intval($request->input('quantity', 1));
        if ($qty < 1) $qty = 1;

        if ($book->stock < $qty) {
            return back()->with('error', 'Maaf, stok buku tidak mencukupi!');
        }

        $cart = Cart::where('user_id', Auth::id())->where('book_id', $book_id)->first();
        if ($cart) {
            $newQty = $cart->quantity + $qty;
            if ($book->stock < $newQty) {
                return back()->with('error', 'Stok buku tidak mencukupi untuk ditambahkan ke keranjang!');
            }
            $cart->update(['quantity' => $newQty]);
        } else {
            Cart::create(['user_id' => Auth::id(), 'book_id' => $book_id, 'quantity' => $qty]);
        }
        return back()->with('success', 'Buku dimasukkan ke keranjang!');
    });

    // 3. Hapus dari keranjang
    Route::delete('/cart/{id}', function ($id) {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Dihapus dari keranjang.');
    });

    // 3.1 Update Kuantitas Keranjang
    Route::post('/cart/{id}/update', function (Request $request, $id) {
        if(Auth::user()->isAdmin()) abort(403);
        
        $cart = Cart::with('book')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $action = $request->input('action');
        $quantity = $request->input('quantity');
        
        $book = $cart->book;
        $newQty = $cart->quantity;

        if ($action === 'increase') {
            $newQty++;
        } elseif ($action === 'decrease') {
            $newQty--;
        } elseif ($quantity !== null) {
            $newQty = intval($quantity);
        }

        if ($newQty < 1) {
            $cart->delete();
            return back()->with('success', 'Buku berhasil dihapus dari keranjang.');
        }

        if ($book->stock < $newQty) {
            return back()->with('error', 'Stok buku "' . $book->title . '" tidak mencukupi untuk jumlah tersebut!');
        }

        $cart->update(['quantity' => $newQty]);
        return back()->with('success', 'Kuantitas keranjang berhasil diperbarui.');
    });

    // 4. Proses Pembelian (Checkout)
    Route::post('/checkout', function () {
        $carts = Cart::with('book')->where('user_id', Auth::id())->get();
        if($carts->isEmpty()) return back()->with('error', 'Keranjang Anda kosong.');

        // Validasi stok sebelum mulai transaksi
        foreach($carts as $cart) {
            if($cart->book->stock < $cart->quantity) {
                return back()->with('error', 'Stok buku "' . $cart->book->title . '" tidak mencukupi untuk pesanan Anda.');
            }
        }

        try {
            DB::beginTransaction();

            // Hitung total harga
            $totalHarga = 0;
            foreach($carts as $cart) {
                $totalHarga += $cart->book->price * $cart->quantity;
            }

            // Buat order utama (status: pending, menanti kode dari admin)
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $totalHarga,
                'status' => 'pending',
                'payment_code' => null
            ]);

            // Simpan detail item dan kurangi stok
            foreach($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $cart->book_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->book->price
                ]);

                $cart->book->decrement('stock', $cart->quantity);
            }

            // Hapus isi keranjang setelah sukses beli
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Checkout berhasil! Mohon tunggu Kode Pembayaran dari Admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pembelian: ' . $e->getMessage());
        }
    });

    // 5. Admin Input Kode Pembayaran
    Route::post('/admin/orders/{id}/assign-code', function (Request $request, $id) {
        if(!Auth::user()->isAdmin()) abort(403);

        $validated = $request->validate([
            'payment_code' => 'required|string|max:100'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'payment_code' => $validated['payment_code'],
            'status' => 'waiting_payment'
        ]);

        return back()->with('success', 'Kode pembayaran berhasil diberikan ke user!');
    });

    // 6. User Melakukan Pembayaran (Simulasi)
    Route::post('/orders/{id}/pay', function ($id) {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($order->status !== 'waiting_payment') {
            return back()->with('error', 'Pesanan tidak dalam status menunggu pembayaran.');
        }

        $order->update([
            'status' => 'completed'
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi! Pesanan Anda telah selesai.');
    });

    // ---- FITUR ADMIN: RIWAYAT PEMBELIAN ----
    Route::get('/admin/pembelian', function () {
        if(!Auth::user()->isAdmin()) abort(403);
        $orders = Order::with(['user', 'items.book'])->latest()->get();
        return view('admin.orders', compact('orders'));
    })->name('admin.orders');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});