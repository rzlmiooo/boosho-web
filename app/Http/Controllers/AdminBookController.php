<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBookController extends Controller
{
    public function index()
    {
        $books = Book::latest()->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'genres' => 'nullable|array'
        ]);

        $data = $validated;

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover'] = $path;
        }

        Book::create($data);

        return redirect()->route('admin.books.index')->with('success', 'Buku baru berhasil ditambahkan!');
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('admin.books.show', compact('book'));
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'genres' => 'nullable|array'
        ]);

        $data = $validated;

        if ($request->hasFile('cover')) {
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover'] = $path;
        }

        $book->update($data);

        return redirect()->route('admin.books.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus!');
    }
}
