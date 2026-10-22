<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $books = Book::when($request->search, function ($query, $search) {
        $query->where(function ($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
              ->orWhere('pengarang', 'like', "%{$search}%")
              ->orWhere('kode_buku', 'like', "%{$search}%");
        });
    })->latest()->paginate(10)->withQueryString();

    return inertia('Books/Index', [
        'books' => $books,
        'search' => $request->search,
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Books/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'kode_buku' => 'required|unique:books',
        'judul'     => 'required|max:255',
        'pengarang' => 'required|max:255',
        'penerbit'  => 'nullable|max:255',
        'tahun'     => 'nullable|digits:4',
        'stok'      => 'required|integer|min:0',
    ], [
        'stok.required' => 'Stok wajib diisi.',
        'stok.integer'  => 'Stok harus berupa bilangan bulat.',
        'stok.min'      => 'Stok tidak boleh bernilai negatif.',
    ]);

    Book::create($validated);

    return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan');
}
    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
{
    return inertia('Books/Edit', [
        'book' => $book,
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
{
    $validated = $request->validate([
        'kode_buku' => 'required|unique:books,kode_buku,' . $book->id,
        'judul'     => 'required|max:255',
        'pengarang' => 'required|max:255',
        'penerbit'  => 'nullable|max:255',
        'tahun'     => 'nullable|digits:4',
        'stok'      => 'required|integer|min:0',
    ]);

    $book->update($validated);

    return redirect()->route('books.index')
        ->with('success', 'Buku berhasil diperbarui');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
{
    $book->delete();

    return redirect()->route('books.index')
        ->with('success', 'Buku berhasil dihapus');
}
}