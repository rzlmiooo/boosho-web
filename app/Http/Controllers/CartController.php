<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Fungsi untuk memproses tombol + Keranjang
    public function store($id)
    {
        $book = Book::findOrFail($id);

        Cart::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'quantity' => 1 // Default jumlah 1
        ]);

        return redirect('/keranjang')->with('success', 'Buku berhasil dimasukkan ke keranjang!');
    }

    // Fungsi untuk menampilkan halaman Keranjang
    public function index()
    {
        // Ambil data keranjang khusus milik user yang sedang login
        $carts = Cart::where('user_id', Auth::id())->with('book')->get();

        return view('keranjang', compact('carts'));
    }
}