<x-layouts.app title="Kelola Kategori">
    <div class="max-w-7xl mx-auto p-6 space-y-6">
        <div>
            <flux:heading size="xl" class="text-zinc-800 dark:text-white">Kelola Kategori</flux:heading>
            <flux:subheading size="lg" class="text-zinc-600 dark:text-zinc-400">Manajemen data kategori buku perpustakaan</flux:subheading>
        </div>

        <flux:separator variant="subtle" />

        {{-- Area Konten / Tabel Kategori --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
            <flux:text class="text-zinc-600 dark:text-zinc-300">
                Berhasil memuat halaman kategori! Integrasikan tabel data Livewire atau komponen Flux di sini.
            </flux:text>
        </div>
    </div>
</x-layouts.app>