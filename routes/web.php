<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\FineIndex;
use App\Livewire\BookIndex;
use App\Livewire\LoanIndex;
use App\Livewire\UserIndex;
use App\Http\Controllers\ReportController;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Halaman Depan / Landing Page Katalog Publik
Route::get('/', function (Request $request) {
    $categories = Category::orderBy('name')->get();

    $books = Book::with('category')
        ->when($request->filled('search'), function ($query) use ($request) {
            $query->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('author', 'like', '%'.$request->search.'%');
        })
        ->when($request->filled('category'), function ($query) use ($request) {
            $query->where('category_id', $request->category);
        })
        ->orderBy('title')
        ->paginate(8)
        ->withQueryString();

    return view('public-catalog', [
        'books' => $books,
        'categories' => $categories,
    ]);
})->name('home');

// Autentikasi dan Dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // ✅ ADMIN + PETUGAS: Kelola Buku, Kategori, Denda
    Route::middleware('can:manageLibrary')->group(function () {
        Route::livewire('/categories', 'pages::category.index')->name('categories.index');
        Route::get('/books', BookIndex::class)->name('books.index');
        Route::get('/admin/fines', FineIndex::class)->name('fines.index');
    });

    // ✅ KHUSUS ADMIN: Laporan & Manajemen User
    Route::middleware('can:isAdmin')->group(function () {
        Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports');
        Route::get('/users', UserIndex::class)->name('users.index');
    });

    // ✅ SEMUA ROLE (Admin, Petugas, Mahasiswa): Peminjaman
    Route::get('/loans', LoanIndex::class)->name('loans.index');

    Route::get('/profile', \App\Livewire\ProfileSettings::class)->name('profile.show');
});

require __DIR__.'/settings.php';