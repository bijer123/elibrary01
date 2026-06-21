<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    // Mengizinkan mass assignment untuk kolom-kolom tabel books
    protected $fillable = [
        'isbn',
        'title',
        'author',
        'category_id',
        'stock',
    ];

    /**
     * Relasi Many-to-One ke tabel categories
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi One-to-Many ke tabel loan_details (untuk pengecekan riwayat peminjaman)
     */
    public function loanDetails(): HasMany
    {
        return $this->hasMany(LoanDetail::class);
    }
}