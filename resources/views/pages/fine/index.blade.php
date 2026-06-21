<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-zinc-800 dark:text-white">Manajemen Denda</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Kelola dan pantau denda keterlambatan peminjaman</p>
        </div>

        <flux:input wire:model.live="search" placeholder="Cari nama peminjam..." icon="magnifying-glass" class="w-64" />
    </div>

    @if (session()->has('success'))
        <div class="p-4 text-sm text-green-700 bg-green-100 rounded-xl dark:bg-green-900/40 dark:text-green-300 border border-green-200 dark:border-green-800">
            {{ session('success') }}
        </div>
    @endif

    <flux:table :paginate="$fines">
        <flux:table.columns>
            <flux:table.column>Peminjam</flux:table.column>
            <flux:table.column>Buku</flux:table.column>
            <flux:table.column>Jumlah Denda</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column align="end">Aksi</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($fines as $fine)
                <flux:table.row :key="$fine->id">
                    <flux:table.cell class="font-medium whitespace-nowrap">{{ $fine->loan->user->name ?? '-' }}</flux:table.cell>
                    <flux:table.cell>
                        <ul class="list-disc pl-4 space-y-1 text-xs">
                            @foreach($fine->loan->details as $detail)
                                <li>{{ $detail->book->title ?? 'Buku tidak ditemukan' }}</li>
                            @endforeach
                        </ul>
                    </flux:table.cell>
                    <flux:table.cell class="font-bold text-red-600 dark:text-red-400">
                        Rp {{ number_format($fine->amount, 0, ',', '.') }}
                    </flux:table.cell>
                    <flux:table.cell>
                        @if($fine->paid)
                            <flux:badge size="sm" color="green" inset="top bottom">Lunas</flux:badge>
                        @else
                            <flux:badge size="sm" color="red" inset="top bottom">Belum Bayar</flux:badge>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell align="end">
                        @if(!$fine->paid)
                            <flux:button wire:click="markAsPaid({{ $fine->id }})" variant="primary" size="sm" class="cursor-pointer">
                                Tandai Lunas
                            </flux:button>
                        @else
                            <span class="text-zinc-400 dark:text-zinc-500 text-xs">-</span>
                        @endif
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="5" class="text-center py-8 text-zinc-400 dark:text-zinc-500">Belum ada data denda.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <div class="mt-4">
        {{ $fines->links() }}
    </div>
</div>