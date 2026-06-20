<?php

namespace App\Services;

use App\Repositories\BookRepository;
use App\Models\Book;

class BookService
{
    protected $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function getBooksList($perPage = 10)
    {
        return $this->bookRepository->getAllPaginated($perPage);
    }

    public function createBook(array $data)
    {
        // Di sini bisa ditambah business logic validasi tambahan jika perlu
        return $this->bookRepository->store($data);
    }

    public function updateBook(Book $book, array $data)
    {
        return $this->bookRepository->update($book, $data);
    }

    public function deleteBook(Book $book)
    {
        // Business Rule: Buku yang sedang dipinjam tidak boleh dihapus
        // Untuk sementara langsung hapus, nanti kita sambung ke modul Peminjaman
        return $this->bookRepository->delete($book);
    }
}