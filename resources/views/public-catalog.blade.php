<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-zinc-900 antialiased bg-zinc-50 dark:bg-zinc-900 dark:text-zinc-100">

        <div class="max-w-6xl mx-auto p-6 space-y-8 mt-6">
            <div class="flex justify-between items-center border-b border-zinc-200 dark:border-zinc-800 pb-4">
                <div>
                    <h1 class="text-3xl font-bold text-zinc-800 dark:text-white">E-Library Katalog</h1>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Cek ketersediaan buku perpustakaan kami secara langsung</p>
                </div>
                <div>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition">Masuk / Login</a>
                        @endauth
                    @endif
                </div>
            </div>

            {{-- Form Search & Filter --}}
            <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50">
                <div class="md:col-span-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari judul atau penulis..."
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm"
                    />
                </div>
                <div class="md:col-span-1">
                    <select name="category" class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1 flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition">
                        Cari
                    </button>
                    @if (request('search') || request('category'))
                        <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl text-sm font-semibold border border-zinc-300 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- Grid Buku --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @forelse ($books as $book)
                    <div class="p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 flex flex-col justify-between space-y-3 shadow-sm">
                        <div>
                            <h3 class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $book->title }}</h3>
                            <p class="text-xs text-zinc-500">{{ $book->author }}</p>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-zinc-400">{{ $book->category->name ?? '-' }}</span>
                            @if ($book->stock > 0)
                                <span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                                    Tersedia ({{ $book->stock }})
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400">
                                    Stok Habis
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-zinc-400 py-12">
                        Tidak ada buku ditemukan{{ request('search') || request('category') ? ' untuk filter ini.' : '.' }}
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div>
                {{ $books->links() }}
            </div>
        </div>
    </body>
</html>