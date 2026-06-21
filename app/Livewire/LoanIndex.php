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
use Carbon\Carbon;

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
            // Status loan secara default akan diset 'pending' pada service-nya
            $loanService->createLoan(Auth::id(), $this->selectedBooks);

            $this->selectedBooks = [];
            $this->showConfirmModal = false;

            Flux::toast(text: 'Pengajuan peminjaman buku berhasil dikirim!', variant: 'success');
        } catch (Exception $e) {
            $this->showConfirmModal = false;
            Flux::toast(text: $e->getMessage(), variant: 'danger');
        }
    }

    // FUNGSI BARU: ACC Peminjaman oleh Admin
    public function approveLoan($loanId)
    {
        $loan = Loan::with('details.book')->findOrFail($loanId);
        
        if ($loan->status !== 'pending') {
            Flux::toast(text: 'Aksi tidak valid atau status telah berubah.', variant: 'warning');
            return;
        }

        // Kurangi stok buku secara aktual saat disetujui
        foreach ($loan->details as $detail) {
            $book = $detail->book;
            if ($book && $book->stock > 0) {
                $book->decrement('stock', 1);
            }
        }

        $loan->status = 'approved';
        $loan->save();

        Flux::toast(text: 'Peminjaman buku berhasil disetujui.', variant: 'success');
    }

    // FUNGSI BARU: Tolak Peminjaman oleh Admin
    public function rejectLoan($loanId)
    {
        $loan = Loan::findOrFail($loanId);
        
        if ($loan->status !== 'pending') {
            Flux::toast(text: 'Aksi tidak valid atau status telah berubah.', variant: 'warning');
            return;
        }

        $loan->status = 'rejected';
        $loan->save();

        Flux::toast(text: 'Peminjaman buku telah ditolak.', variant: 'danger');
    }

    // DIPERBARUI: Pengembalian Buku dengan Otomasi Denda
    public function returnBook($loanId)
    {
        try {
            $loan = Loan::where('id', $loanId)->where('user_id', Auth::id())->firstOrFail();
            
            if ($loan->status === 'returned') {
                Flux::toast(text: 'Buku ini sudah dikembalikan sebelumnya.', variant: 'warning');
                return;
            }

            // 1. Kembalikan stok buku
            $details = LoanDetail::where('loan_id', $loanId)->get();
            foreach ($details as $detail) {
                $book = Book::find($detail->book_id);
                if ($book) {
                    $book->increment('stock', 1);
                }
            }

            // 2. Ubah status peminjaman menjadi returned (selesai)
            $loan->status = 'returned';
            $loan->save();

            // 3. Otomasi Cek Denda (Keterlambatan dihitung dari due_date)
            $now = Carbon::now();
            $dueDate = Carbon::parse($loan->due_date);

            if ($now->greaterThan($dueDate)) {
                // Hitung jumlah hari terlambat
                $lateDays = $now->diffInDays($dueDate);
                
                // Tarif denda per hari (contoh: Rp 2.000 per hari, silakan disesuaikan aturan perpusmu)
                $finePerDay = 2000; 
                $fineAmount = $lateDays * $finePerDay;

                // Simpan otomatis ke tabel fines
                \App\Models\Fine::create([
                    'loan_id' => $loan->id,
                    'amount' => $fineAmount,
                    'paid' => false, // Status belum lunas
                ]);

                Flux::toast(text: 'Buku berhasil dikembalikan. Anda terkena denda Rp ' . number_format($fineAmount, 0, ',', '.') . ' karena terlambat mengembalikan.', variant: 'warning');
            } else {
                Flux::toast(text: 'Buku berhasil dikembalikan tepat waktu.', variant: 'success');
            }

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

        // Peminjaman milik student yang sedang login
        $myLoans = Loan::where('user_id', Auth::id())
            ->with(['details.book'])
            ->latest()
            ->get();

        // TAMBAHAN: Ambil semua data peminjaman untuk admin (mendukung pagination)
        $allLoansForAdmin = Loan::with(['user', 'details.book'])
            ->latest()
            ->paginate(5);

        $categories = Category::all();

        return view('pages.book.loan', [
            'availableBooks' => $availableBooks,
            'myLoans' => $myLoans,
            'allLoansForAdmin' => $allLoansForAdmin, // Variabel untuk ditampilkan ke view blade admin
            'categories' => $categories
        ])->layout('layouts.app');
    }
}