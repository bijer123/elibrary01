<x-layouts.app :title="'Laporan Peminjaman'">
    <div class="max-w-7xl mx-auto p-6 space-y-6">
        <div>
            <flux:heading size="xl" class="text-zinc-800 dark:text-white">Laporan Peminjaman</flux:heading>
            <flux:subheading size="lg" class="text-zinc-600 dark:text-zinc-400">Filter dan cetak rekapitulasi data peminjaman perpustakaan</flux:subheading>
        </div>

        <flux:separator variant="subtle" />

        {{-- Area Filter --}}
        <form method="GET" action="{{ route('admin.reports') }}" class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4 items-end no-print">
            <div>
                <flux:label>Tanggal Mulai</flux:label>
                <flux:input type="date" name="start_date" value="{{ $startDate }}" class="mt-1" />
            </div>
            <div>
                <flux:label>Tanggal Selesai</flux:label>
                <flux:input type="date" name="end_date" value="{{ $endDate }}" class="mt-1" />
            </div>
            <div>
                <flux:label>Status</flux:label>
                <select name="status" class="mt-1 w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm text-zinc-800 dark:text-zinc-200">
                    <option value="" {{ $status == '' ? 'selected' : '' }}>Semua Status</option>
                    <option value="borrowed" {{ $status == 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="returned" {{ $status == 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>
            <div class="flex gap-2">
                <flux:button type="submit" variant="primary" class="flex-1">Filter</flux:button>
                <flux:button icon="printer" onclick="window.print()" class="flex-1">Cetak</flux:button>
            </div>
        </form>

        {{-- Tabel Laporan --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700 text-zinc-500 font-semibold">
                            <th class="py-3 px-4">Peminjam</th>
                            <th class="py-3 px-4">Buku</th>
                            <th class="py-3 px-4">Tanggal Pinjam</th>
                            <th class="py-3 px-4">Tenggat</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-zinc-700 dark:text-zinc-300">
                        @forelse($loans as $loan)
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                            <td class="py-3 px-4">{{ $loan->user->name ?? '-' }}</td>
                            <td class="py-3 px-4">
                                <ul class="list-disc pl-4 text-xs space-y-1">
                                    @foreach($loan->details as $detail)
                                        <li>{{ $detail->book->title ?? 'Buku Dihapus' }} (x{{ $detail->quantity }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="py-3 px-4">{{ $loan->loan_date }}</td>
                            <td class="py-3 px-4">{{ $loan->due_date }}</td>
                            <td class="py-3 px-4">
                                <flux:badge variant="{{ $loan->status === 'returned' ? 'success' : 'warning' }}">
                                    {{ ucfirst($loan->status) }}
                                </flux:badge>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-zinc-400">Tidak ada data peminjaman pada rentang ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <style>
            @media print {
                body { background: white; color: black; font-size: 12pt; }
                .no-print, header, aside, flux-sidebar, form, button { display: none !important; }
                main { width: 100% !important; margin: 0 !important; padding: 0 !important; }
                .border-zinc-200 { border-color: #ccc !important; }
            }
        </style>
    </div>
</x-layouts.app>