<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'E-Library' }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

        <flux:sidebar sticky stashable class="border-r border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" />

            <flux:brand href="{{ route('dashboard') }}" name="E-Library" class="px-6 mb-6" />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="home" href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')">Dashboard</flux:navlist.item>

                {{-- ✅ ADMIN + PETUGAS: Kategori & Buku --}}
                @can('manageLibrary')
                    <flux:navlist.item icon="folder" href="{{ route('categories.index') }}" :current="request()->routeIs('categories.index')">Category</flux:navlist.item>
                    <flux:navlist.item icon="book-open" href="{{ route('books.index') }}" :current="request()->routeIs('books.index')">Books</flux:navlist.item>
                @endcan

                {{-- ✅ SEMUA ROLE: Peminjaman --}}
                <flux:navlist.item icon="document" href="{{ route('loans.index') }}" :current="request()->routeIs('loans.index')">Peminjaman</flux:navlist.item>

                {{-- ✅ ADMIN + PETUGAS: Manajemen Denda --}}
                @can('manageLibrary')
                    <flux:navlist.item icon="currency-dollar" href="{{ route('fines.index') }}" :current="request()->routeIs('fines.index')">Manajemen Denda</flux:navlist.item>
                @endcan

                {{-- ✅ KHUSUS ADMIN: Laporan & Manajemen User --}}
                @can('isAdmin')
                    <flux:navlist.item icon="document-text" href="{{ route('admin.reports') }}" :current="request()->routeIs('admin.reports')">Laporan Sistem</flux:navlist.item>
                    <flux:navlist.item icon="users" href="{{ route('users.index') }}" :current="request()->routeIs('users.index')">Manajemen User</flux:navlist.item>
                @endcan

                <flux:navlist.item icon="user" href="{{ route('profile.show') }}" :current="request()->routeIs('profile.show')">Profil Saya</flux:navlist.item>
            </flux:navlist>

            <flux:spacer />

            <form method="POST" action="{{ route('logout') }}" class="px-4 mt-4 mb-4">
                @csrf
                <flux:button type="submit" variant="danger" class="w-full cursor-pointer">
                    Keluar / Logout
                </flux:button>
            </form>
        </flux:sidebar>

        <flux:main>
            {{ $slot }}
        </flux:main>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>