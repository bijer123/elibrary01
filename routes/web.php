<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    // AKSES PENUH UNTUK ADMIN (Kelola Data Master + Halaman Transaksi)
    Route::middleware('can:isAdmin')->group(function () {
        Route::livewire('/categories', 'pages::category.index')->name('category.index');
        Route::get('/books', \App\Livewire\BookIndex::class)->name('book.index');
        
        // Fitur peminjaman/transaksi didaftarkan di sini agar admin bisa mengaksesnya
        Route::get('/loans', \App\Livewire\LoanIndex::class)->name('loan.index');
    });

    // AKSES UNTUK MAHASISWA / STUDENT
    Route::middleware('can:isStudent')->group(function () {
        Route::get('/loans', \App\Livewire\LoanIndex::class)->name('loan.index');
    });

    Route::get('/profile', \App\Livewire\ProfileSettings::class)->name('profile.settings');
});

require __DIR__.'/settings.php';