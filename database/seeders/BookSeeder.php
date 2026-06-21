<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan kategori dasar ada terlebih dahulu
        $category1 = Category::firstOrCreate(['name' => 'Pemrograman', 'description' => 'Buku seputar koding']);
        $category2 = Category::firstOrCreate(['name' => 'Jaringan', 'description' => 'Buku networking']);
        $category3 = Category::firstOrCreate(['name' => 'Fiksi', 'description' => 'Novel dan cerita']);

        $books = [
            ['title' => 'Belajar Laravel 11', 'author' => 'Taylor Otwell', 'category_id' => $category1->id, 'stock' => 5],
            ['title' => 'Mastering Livewire 3', 'author' => 'Caleb Porzio', 'category_id' => $category1->id, 'stock' => 3],
            ['title' => 'Dasar Keamanan Siber', 'author' => 'Kevin Mitnick', 'category_id' => $category2->id, 'stock' => 10],
            ['title' => 'Kalkulus Lanjut', 'author' => 'James Stewart', 'category_id' => $category2->id, 'stock' => 0],
            ['title' => 'Resident Evil: The Umbrella Conspiracy', 'author' => 'S.D. Perry', 'category_id' => $category3->id, 'stock' => 2],
            ['title' => 'Matematika Diskrit', 'author' => 'Rinaldi Munir', 'category_id' => $category1->id, 'stock' => 7],
        ];

        foreach ($books as $book) {
            Book::create([
                'isbn' => '978-' . rand(1000000000, 9999999999),
                'title' => $book['title'],
                'author' => $book['author'],
                'category_id' => $book['category_id'],
                'stock' => $book['stock']
            ]);
        }
    }
}