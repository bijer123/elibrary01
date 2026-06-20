<?php

namespace App\Livewire;

use App\Services\BookService;
use App\Models\Category;
use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;
use Flux;

class BookIndex extends Component
{
    use WithPagination;

    // Properti Form
    public $isbn = '';
    public $title = '';
    public $author = '';
    public $category_id = '';
    public $stock = '';
    public $editingBookId = null;
    public $showModal = false;

    protected $rules = [
        'isbn' => 'required|string',
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'stock' => 'required|integer|min:0',
    ];

    public function create()
    {
        $this->resetValidation();
        $this->reset(['isbn', 'title', 'author', 'category_id', 'stock', 'editingBookId']);
        $this->showModal = true;
    }

    public function save(BookService $bookService)
    {
        $this->validate([
            'isbn' => 'required|string|unique:books,isbn,' . ($this->editingBookId ?? 'NULL'),
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
        ]);

        $data = [
            'isbn' => $this->isbn,
            'title' => $this->title,
            'author' => $this->author,
            'category_id' => $this->category_id,
            'stock' => $this->stock,
        ];

        if ($this->editingBookId) {
            $book = Book::find($this->editingBookId);
            $bookService->updateBook($book, $data);
            Flux::toast('Buku berhasil diperbarui.');
        } else {
            $bookService->createBook($data);
            Flux::toast('Buku baru berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->reset(['isbn', 'title', 'author', 'category_id', 'stock', 'editingBookId']);
    }

    public function edit($id)
    {
        $this->resetValidation();
        $book = Book::find($id);
        if ($book) {
            $this->editingBookId = $book->id;
            $this->isbn = $book->isbn;
            $this->title = $book->title;
            $this->author = $book->author;
            $this->category_id = $book->category_id;
            $this->stock = $book->stock;
            $this->showModal = true;
        }
    }

    public function delete($id, BookService $bookService)
    {
        $book = Book::find($id);
        if ($book) {
            $bookService->deleteBook($book);
            Flux::toast('Buku berhasil dihapus.');
        }
    }

    public function render(BookService $bookService)
    {
        // SUDAH FIX: Mengarah ke folder baru kamu di resources/views/pages/book/index.blade.php
        return view('pages::book.index', [
            'books' => $bookService->getBooksList(5),
            'categories' => Category::all()
        ])->layout('layouts.app');
    }
}