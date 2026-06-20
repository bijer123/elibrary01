<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository
{
    public function getAllPaginated($perPage = 10)
    {
        // Mengambil data buku beserta nama kategorinya (Eager Loading)
        return Book::with('category')->latest()->paginate($perPage);
    }

    public function store(array $data)
    {
        return Book::create($data);
    }

    public function update(Book $book, array $data)
    {
        $book->update($data);
        return $book;
    }

    public function delete(Book $book)
    {
        return $book->delete();
    }
}