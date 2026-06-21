<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class PublicCatalog extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryId = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $books = Book::where('stock', '>', 0)
            ->when($this->search, function($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('author', 'like', '%' . $this->search . '%');
            })
            ->when($this->categoryId, function($query) {
                $query->where('category_id', $this->categoryId);
            })
            ->with('category')
            ->paginate(8);

        $categories = Category::all();

        return view('livewire.public-catalog', [
            'books' => $books,
            'categories' => $categories
        ])->layout('layouts. guest'); // Pastikan menggunakan layout guest / non-auth
    }
}