<x-layouts::app.sidebar :title="$title ?? null">
    <flux:sidebar sticky stashable class="border-r border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" />

        <flux:brand href="{{ route('dashboard') }}" name="E-Library" class="px-6 mb-6" />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="home" href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')">Dashboard</flux:navlist.item>
            
            @can('isAdmin')
                <flux:navlist.item icon="folder" href="{{ route('category.index') }}" :current="request()->routeIs('category.index')">Category</flux:navlist.item>
                <flux:navlist.item icon="book-open" href="{{ route('book.index') }}" :current="request()->routeIs('book.index')">Books</flux:navlist.item>
            @endcan

            @if(auth()->check() && (auth()->user()->role === 'student' || auth()->user()->role === 'admin'))
                <flux:navlist.item icon="document" href="{{ route('loan.index') }}" :current="request()->routeIs('loan.index')">Peminjaman</flux:navlist.item>
            @endif
            
            <flux:navlist.item icon="user" href="{{ route('profile.settings') }}" :current="request()->routeIs('profile.settings')">Profil Saya</flux:navlist.item>
        </flux:navlist>

        <flux:spacer />

        <form method="POST" action="{{ route('logout') }}" class="px-4 mt-4 mb-4">
            @csrf
            <flux:button type="submit" variant="danger" class="w-full cursor-pointer">
                Keluar / Logout
            </flux:button>
        </form>

        <flux:navlist variant="outline" class="mb-2">
            </flux:navlist>
    </flux:sidebar>

    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>