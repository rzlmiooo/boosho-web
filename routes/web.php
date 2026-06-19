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

    Route::get('/katalog', function () {
        $books = Book::latest()->get();
        return view('katalog', compact('books'));
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

    // 4. Proses Pembelian (Checkout)
    Route::post('/checkout', function () {
        $carts = Cart::where('user_id', Auth::id())->get();
        if($carts->isEmpty()) return back()->with('error', 'Keranjang Anda kosong.');

        // Kurangi stok buku
        foreach($carts as $cart) {
            $book = $cart->book;
            if($book->stock >= $cart->quantity) {
                $book->decrement('stock', $cart->quantity);
            } else {
                return back()->with('error', 'Stok buku "' . $book->title . '" tidak mencukupi untuk pesanan Anda.');
            }
        }

        // Hapus isi keranjang setelah sukses beli
        Cart::where('user_id', Auth::id())->delete();
        return redirect('/katalog')->with('success', 'Pembelian berhasil! Terima kasih telah berbelanja di BooSho.');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});