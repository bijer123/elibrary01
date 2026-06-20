<?php

namespace App\Livewire;

use App\Services\LoanService;
use App\Models\Book;
use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Flux;
use Exception;

class LoanIndex extends Component
{
    use WithPagination;

    public $selectedBooks = []; 
    public $showConfirmModal = false;
    
    public $search = '';
    public $categoryId = '';

    // Properti baru untuk Modal Detail Buku
    public $showDetailModal = false;
    public $bookDetail;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    // FUNGSI BARU: Membuka modal detail buku
    public function viewDetail($bookId)
    {
        $this->bookDetail = Book::with('category')->findOrFail($bookId);
        $this->showDetailModal = true;
    }

    public function toggleSelectBook($bookId)
    {
        if (in_array($bookId, $this->selectedBooks)) {
            $this->selectedBooks = array_diff($this->selectedBooks, [$bookId]);
        } else {
            if (count($this->selectedBooks) >= 3) {
                Flux::toast(
                    text: 'Maksimal hanya boleh memilih 3 buku sekaligus.',
                    variant: 'danger'
                );
                return;
            }
            $this->selectedBooks[] = $bookId;
        }
    }

    public function processLoan(LoanService $loanService)
    {
        if (empty($this->selectedBooks)) {
            Flux::toast(text: 'Silakan pilih minimal 1 buku terlebih dahulu.', variant: 'danger');
            return;
        }

        try {
            $loanService->createLoan(Auth::id(), $this->selectedBooks);

            $this->selectedBooks = [];
            $this->showConfirmModal = false;

            Flux::toast(text: 'Peminjaman buku berhasil diproses!', variant: 'success');
        } catch (Exception $e) {
            $this->showConfirmModal = false;
            Flux::toast(text: $e->getMessage(), variant: 'danger');
        }
    }

    public function returnBook($loanId)
    {
        try {
            $loan = Loan::where('id', $loanId)->where('user_id', Auth::id())->firstOrFail();
            
            if ($loan->status === 'returned') {
                Flux::toast(text: 'Buku ini sudah dikembalikan sebelumnya.', variant: 'warning');
                return;
            }

            $details = LoanDetail::where('loan_id', $loanId)->get();
            foreach ($details as $detail) {
                $book = Book::find($detail->book_id);
                if ($book) {
                    $book->increment('stock', 1);
                }
            }

            $loan->status = 'returned';
            $loan->save();

            Flux::toast(text: 'Buku berhasil dikembalikan, stok telah diperbarui.', variant: 'success');
        } catch (Exception $e) {
            Flux::toast(text: 'Gagal memproses pengembalian.', variant: 'danger');
        }
    }

    public function render()
    {
        $availableBooks = Book::where('stock', '>', 0)
            ->when($this->search, function($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('author', 'like', '%' . $this->search . '%');
            })
            ->when($this->categoryId, function($query) {
                $query->where('category_id', $this->categoryId);
            })
            ->with('category')
            ->paginate(6);

        $myLoans = Loan::where('user_id', Auth::id())
            ->with(['details.book'])
            ->latest()
            ->get();

        $categories = Category::all();

        return view('pages.book.loan', [
            'availableBooks' => $availableBooks,
            'myLoans' => $myLoans,
            'categories' => $categories
        ])->layout('layouts.app');
    }
}