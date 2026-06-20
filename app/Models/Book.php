<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'author',
        'category_id',
        'stock',
    ];

    /**
     * Relasi balik ke Model Category (Setiap Buku memiliki satu Kategori)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}