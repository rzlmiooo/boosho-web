<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // 1. Menampilkan semua buku (READ)
    public function index()
    {
        $books = Book::all();
        return response()->json($books, 200);
    }

    // 2. Menyimpan buku baru (CREATE)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
        ]);

        $book = Book::create($validated);
        
        return response()->json([
            'message' => 'Buku berhasil ditambahkan!', 
            'data' => $book
        ], 201);
    }

    // 3. Menampilkan satu buku spesifik (READ)
    public function show($id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }
        
        return response()->json($book, 200);
    }

    // 4. Mengupdate data buku (UPDATE)
    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|integer',
            'stock' => 'sometimes|required|integer',
        ]);

        $book->update($validated);
        
        return response()->json([
            'message' => 'Data buku berhasil diperbarui!', 
            'data' => $book
        ], 200);
    }

    // 5. Menghapus buku (DELETE)
    public function destroy($id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $book->delete();
        
        return response()->json(['message' => 'Buku berhasil dihapus!'], 200);
    }

    // 6. Mencari buku berdasarkan judul (Search)
    public function search($title)
    {
        $books = Book::where('title', 'like', '%' . $title . '%')->get();
        
        if ($books->isEmpty()) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }
        
        return response()->json($books, 200);
    }
}