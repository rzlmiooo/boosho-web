<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminBookController;
use App\Models\Book;
use App\Models\Cart;
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
        if (Auth::user()->isAdmin()) {
            $totalBooks = Book::count();
            $totalStock = Book::sum('stock');
            // Menghitung total nilai secara manual atau via raw query
            $totalValue = Book::sum(DB::raw('price * stock'));

            $latestBooks = Book::latest()->take(5)->get();

            return view('admin.dashboard', compact('totalBooks', 'totalStock', 'totalValue', 'latestBooks'));
        } else {
            $books = Book::latest()->get();
            $orders = Order::with('items.book')->where('user_id', Auth::id())->latest()->get();
            return view('dashboard', compact('books', 'orders'));
        }
    })->name('dashboard');

    // Katalog untuk User
    Route::get('/katalog', function (Request $request) {
        $query = Book::query();

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('author', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', (int) $request->min_price);
        }

        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', (int) $request->max_price);
        }

        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'title_asc'  => $query->orderBy('title', 'asc'),
            default      => $query->latest(),
        };

        $books        = $query->get();
        $totalBooks   = Book::count();
        $activeFilter = $request->filled('search') || $request->filled('min_price') || $request->filled('max_price') || $request->boolean('in_stock') || ($sort !== 'latest');

        return view('katalog', compact('books', 'totalBooks', 'activeFilter'));
    })->name('katalog');

    // Rute Admin Books Baru menggunakan Controller
    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->prefix('admin/books')->name('admin.books.')->group(function () {
        Route::get('/', [AdminBookController::class, 'index'])->name('index');
        Route::get('/create', [AdminBookController::class, 'create'])->name('create');
        Route::post('/', [AdminBookController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminBookController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminBookController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminBookController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminBookController::class, 'destroy'])->name('destroy');
    });

    // Rute Legacy (untuk menjaga route agar tidak error jika ada view yang masih mereferensikannya)
    Route::post('/books', function(Request $request) {
        if(!Auth::user()->isAdmin()) abort(403);
        // ... kode legacy atau arahkan ke admin.books.store
        return redirect()->route('admin.books.store')->withInput();
    });

    Route::delete('/books/{id}', function($id) {
         if(!Auth::user()->isAdmin()) abort(403);
         return redirect()->route('admin.books.destroy', $id);
    });

    Route::get('/books/{id}', function ($id) {
        $book = Book::findOrFail($id);
        return view('detail', compact('book'));
    })->name('book.detail');

    Route::get('/books/{id}/edit', function($id) {
         if(!Auth::user()->isAdmin()) abort(403);
         return redirect()->route('admin.books.edit', $id);
    })->name('book.edit');

    Route::put('/books/{id}', function(Request $request, $id) {
        if(!Auth::user()->isAdmin()) abort(403);
        // ... kode legacy
        return redirect()->route('admin.books.update', $id);
    });

    // ---- FITUR USER: KERANJANG & CHECKOUT ----
    Route::get('/account', function () {
        $orders = Order::with('items.book')->where('user_id', Auth::id())->latest()->get();
        return view('account', compact('orders'));
    })->name('account');

    Route::get('/keranjang', function () {
        if(Auth::user()->isAdmin()) abort(403);
        $carts = Cart::with('book')->where('user_id', Auth::id())->get();
        return view('keranjang', compact('carts'));
    })->name('keranjang');

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

    Route::delete('/cart/{id}', function ($id) {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Dihapus dari keranjang.');
    });

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

    Route::post('/checkout', function () {
        $carts = Cart::with('book')->where('user_id', Auth::id())->get();
        if($carts->isEmpty()) return back()->with('error', 'Keranjang Anda kosong.');

        foreach($carts as $cart) {
            if($cart->book->stock < $cart->quantity) {
                return back()->with('error', 'Stok buku "' . $cart->book->title . '" tidak mencukupi untuk pesanan Anda.');
            }
        }

        try {
            DB::beginTransaction();
            $totalHarga = 0;
            foreach($carts as $cart) {
                $totalHarga += $cart->book->price * $cart->quantity;
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $totalHarga,
                'status' => 'pending',
                'payment_code' => null
            ]);

            foreach($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $cart->book_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->book->price
                ]);
                $cart->book->decrement('stock', $cart->quantity);
            }

            Cart::where('user_id', Auth::id())->delete();
            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Checkout berhasil! Mohon tunggu Kode Pembayaran dari Admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pembelian: ' . $e->getMessage());
        }
    });

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

    Route::post('/orders/{id}/pay', function ($id) {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if ($order->status !== 'waiting_payment') {
            return back()->with('error', 'Pesanan tidak dalam status menunggu pembayaran.');
        }
        $order->update(['status' => 'completed']);
        return back()->with('success', 'Pembayaran berhasil dikonfirmasi! Pesanan Anda telah selesai.');
    });

    Route::get('/admin/pembelian', function () {
        if(!Auth::user()->isAdmin()) abort(403);
        $orders = Order::with(['user', 'items.book'])->latest()->get();
        // Cek jika view admin.orders menggunakan layout admin terbaru atau layout lama.
        return view('admin.orders', compact('orders'));
    })->name('admin.orders');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});