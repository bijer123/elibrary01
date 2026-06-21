<x-layouts.app title="Dashboard">
    <div class="max-w-7xl mx-auto p-6 space-y-6">
        <div>
            <flux:heading size="xl" class="text-zinc-800 dark:text-white">Dashboard</flux:heading>
            <flux:subheading size="lg" class="text-zinc-600 dark:text-zinc-400">Ringkasan aktivitas dan data sistem perpustakaan</flux:subheading>
        </div>

        <flux:separator variant="subtle" />

        {{-- 3 Kartu Statistik (Metrik Utama) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <flux:card class="flex flex-col justify-between p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <flux:text size="sm" class="font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Total Koleksi Buku</flux:text>
                        <flux:heading size="xl" class="mt-2 font-bold text-zinc-800 dark:text-white">{{ \App\Models\Book::count() }}</flux:heading>
                    </div>
                    <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg text-indigo-600 dark:text-indigo-400">
                        <flux:icon name="book-open" variant="solid" size="lg" />
                    </div>
                </div>
                <div class="mt-4 text-xs text-zinc-400">Jumlah judul buku dalam database</div>
            </flux:card>

            <flux:card class="flex flex-col justify-between p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <flux:text size="sm" class="font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Sedang Dipinjam</flux:text>
                        <flux:heading size="xl" class="mt-2 font-bold text-zinc-800 dark:text-white">
                            {{ \App\Models\LoanDetail::whereHas('loan', function($query) {
                                    $query->where('status', 'borrowed');
                                })->count() 
                            }}
                        </flux:heading>
                    </div>
                    <div class="p-3 bg-amber-50 dark:bg-amber-900/30 rounded-lg text-amber-600 dark:text-amber-400">
                        <flux:icon name="arrow-trending-up" variant="solid" size="lg" />
                    </div>
                </div>
                <div class="mt-4 text-xs text-zinc-400">Buku yang sedang dalam masa peminjaman</div>
            </flux:card>

            <flux:card class="flex flex-col justify-between p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <flux:text size="sm" class="font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Total Anggota</flux:text>
                        <flux:heading size="xl" class="mt-2 font-bold text-zinc-800 dark:text-white">{{ \App\Models\User::where('role', 'student')->count() }}</flux:heading>
                    </div>
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg text-emerald-600 dark:text-emerald-400">
                        <flux:icon name="users" variant="solid" size="lg" />
                    </div>
                </div>
                <div class="mt-4 text-xs text-zinc-400">Mahasiswa/Anggota terdaftar</div>
            </flux:card>
        </div>

        {{-- Area Aktivitas / Tabel Log --}}
        <div class="grid grid-cols-1 gap-6 pt-2">
            <flux:card class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <flux:heading size="lg" class="mb-2">Aktivitas Sistem Terkini</flux:heading>
                <flux:subheading class="mb-4">Log rekapitulasi operasional peminjaman perpustakaan</flux:subheading>
                
                <div class="h-56 border border-dashed border-zinc-200 dark:border-zinc-700 rounded-lg flex items-center justify-center text-zinc-400">
                    <div class="text-center">
                        <flux:icon name="clock" size="xl" class="mx-auto mb-2 text-zinc-300 dark:text-zinc-600" />
                        <flux:text>Menampilkan tabel atau grafik log aktivitas di sini</flux:text>
                    </div>
                </div>
            </flux:card>
        </div>
    </div>
</x-layouts.app>