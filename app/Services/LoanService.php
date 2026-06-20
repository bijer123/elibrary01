<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Exception;

class LoanService
{
    public function createLoan(int $userId, array $bookIds)
    {
        return DB::transaction(function () use ($userId, $bookIds) {
            // 1. Validasi Aturan Bisnis: Maksimal meminjam 3 buku
            $activeLoansCount = Loan::where('user_id', $userId)
                ->where('status', 'borrowed')
                ->withSum('details', 'quantity')
                ->get()
                ->sum('details_sum_quantity');

            if (($activeLoansCount + count($bookIds)) > 3) {
                throw new Exception('Gagal! Mahasiswa maksimal hanya boleh meminjam 3 buku secara bersamaan.');
            }

            // 2. Buat data induk Peminjaman (Loans)
            $loan = Loan::create([
                'user_id' => $userId,
                'loan_date' => now(),
                'due_date' => now()->addDays(7), // Batas pinjam maksimal 7 hari
                'status' => 'borrowed',
            ]);

            // 3. Masukkan detail buku & kurangi stok database
            foreach ($bookIds as $bookId) {
                $book = Book::lockForUpdate()->find($bookId);

                // Validasi Aturan Bisnis: Stok habis
                if (!$book || $book->stock < 1) {
                    throw new Exception("Gagal! Stok buku '{$book->title}' sudah habis.");
                }

                // Kurangi stok buku
                $book->decrement('stock', 1);

                // Catat di detail peminjaman
                $loan->details()->create([
                    'book_id' => $bookId,
                    'quantity' => 1,
                ]);
            }

            return $loan;
        });
    }
}