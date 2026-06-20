<div class="p-6 space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-zinc-800 dark:text-white">Peminjaman Buku</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400">Pilih buku yang ingin kamu pinjam secara online (Maksimal 3 buku)</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-4">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50">
                <div class="md:col-span-2">
                    <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Cari judul buku atau penulis..." />
                </div>
                <div>
                    <flux:select wire:model.live="categoryId" placeholder="Semua Kategori" class="w-full">
                        <flux:select.option value="">Semua Kategori</flux:select.option>
                        @foreach($categories as $cat)
                            <flux:select.option value="{{ $cat->id }}">{{ $cat->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
            </div>

            <h2 class="text-lg font-semibold text-zinc-700 dark:text-zinc-300">Katalog Buku Tersedia</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($availableBooks as $book)
                    @php $isSelected = in_array($book->id, $selectedBooks); @endphp
                    <div class="p-4 rounded-xl border {{ $isSelected ? 'border-indigo-500 bg-indigo-50/50 dark:bg-indigo-950/20' : 'border-zinc-200 dark:border-zinc-800' }} flex flex-col justify-between space-y-4 transition">
                        <div>
                            <div class="flex justify-between items-start">
                                <flux:badge size="sm" color="zinc">{{ $book->category?->name ?? 'Uncategorized' }}</flux:badge>
                                <span class="text-xs text-zinc-400 font-mono">Stok: {{ $book->stock }} pcs</span>
                            </div>
                            <h3 wire:click="viewDetail({{ $book->id }})" class="font-semibold text-zinc-800 dark:text-zinc-200 mt-2 line-clamp-1 hover:underline cursor-pointer transition">
                                {{ $book->title }}
                            </h3>
                            <p class="text-xs text-zinc-500">Penulis: {{ $book->author }}</p>
                        </div>
                        
                        <flux:button 
                            wire:click="toggleSelectBook({{ $book->id }})" 
                            variant="{{ $isSelected ? 'primary' : 'outline' }}" 
                            size="sm" 
                            class="w-full cursor-pointer"
                        >
                            {{ $isSelected ? 'Batal Pilih' : 'Pilih Buku' }}
                        </flux:button>
                    </div>
                @empty
                    <div class="col-span-2 text-center p-8 text-zinc-400">Buku tidak ditemukan atau tidak tersedia.</div>
                @endforelse
            </div>
            
            <div class="mt-4">
                {{ $availableBooks->links() }}
            </div>
        </div>

        <div class="space-y-6">
            <div class="p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 space-y-4">
                <h2 class="text-base font-semibold text-zinc-800 dark:text-zinc-200">Buku Dipilih ({{ count($selectedBooks) }}/3)</h2>
                
                @if(count($selectedBooks) > 0)
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach($selectedBooks as $id)
                            @php $b = \App\Models\Book::find($id); @endphp
                            <div class="py-2 flex justify-between items-center text-sm">
                                <span class="font-medium text-zinc-700 dark:text-zinc-300 truncate max-w-[180px]">{{ $b?->title }}</span>
                                <flux:button wire:click="toggleSelectBook({{ $id }})" variant="ghost" size="sm" icon="x-mark" square class="text-zinc-400 hover:text-red-500 cursor-pointer" />
                            </div>
                        @endforeach
                    </div>
                    <flux:button wire:click="$set('showConfirmModal', true)" variant="primary" class="w-full mt-2 cursor-pointer">Konfirmasi Pinjam Buku</flux:button>
                @else
                    <p class="text-xs text-zinc-400 py-4 text-center">Belum ada buku yang dipilih.</p>
                @endif
            </div>

            <div class="space-y-3">
                <h2 class="text-base font-semibold text-zinc-800 dark:text-zinc-200">Riwayat Pinjam Kamu</h2>
                <div class="space-y-2 max-h-[300px] overflow-y-auto pr-1">
                    @forelse($myLoans as $loan)
                        <div class="p-3 rounded-lg border border-zinc-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs space-y-1">
                            <div class="flex justify-between items-center">
                                <span class="font-mono text-zinc-400">#LN-{{ $loan->id }}</span>
                                <flux:badge size="sm" inset="top bottom" color="{{ $loan->status === 'borrowed' ? 'amber' : 'green' }}">
                                    {{ $loan->status === 'borrowed' ? 'Dipinjam' : 'Kembali' }}
                                </flux:badge>
                            </div>
                            <div class="font-medium text-zinc-700 dark:text-zinc-300">
                                @foreach($loan->details as $det)
                                    • {{ $det->book?->title ?? 'Buku Dihapus' }} <br>
                                @endforeach
                            </div>
                            <div class="text-[10px] text-zinc-400 pt-1 border-t border-zinc-50 dark:border-zinc-800 flex justify-between items-center">
                                <div>
                                    <span>Tgl Pinjam: {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</span><br>
                                    <span class="text-red-500 font-medium">Deadline: {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</span>
                                </div>
                                
                                @if($loan->status === 'borrowed')
                                    <flux:button wire:click="returnBook({{ $loan->id }})" variant="primary" size="xs" class="cursor-pointer">
                                        Kembalikan
                                    </flux:button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-zinc-400 text-center py-4">Kamu belum memiliki riwayat peminjaman.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <flux:modal wire:model="showConfirmModal" class="md:w-[24rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Konfirmasi Peminjaman</flux:heading>
                <flux:subheading>Apakah kamu yakin ingin memproses peminjaman untuk {{ count($selectedBooks) }} buku ini? Batas waktu pengembalian adalah 7 hari.</flux:subheading>
            </div>
            <div class="flex gap-2 justify-end">
                <flux:modal.close><flux:button variant="ghost" class="cursor-pointer">Batal</flux:button></flux:modal.close>
                <flux:button wire:click="processLoan" variant="primary" class="cursor-pointer">Ya, Pinjam Buku</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal wire:model="showDetailModal" class="md:w-[32rem]">
        <div class="space-y-6">
            @if($bookDetail)
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <flux:badge size="sm" color="zinc">{{ $bookDetail->category?->name ?? 'Uncategorized' }}</flux:badge>
                        <span class="text-xs text-zinc-400 font-mono">Stok Tersedia: {{ $bookDetail->stock }} pcs</span>
                    </div>
                    <flux:heading size="lg" class="text-zinc-800 dark:text-white">{{ $bookDetail->title }}</flux:heading>
                    <flux:subheading class="text-zinc-500">Penulis: {{ $bookDetail->author }}</flux:subheading>
                </div>
                
                <div class="p-4 rounded-xl border border-zinc-100 dark:border-zinc-800 bg-zinc-50/30 dark:bg-zinc-900/30 space-y-2">
                    <span class="text-xs font-semibold text-zinc-700 dark:text-zinc-300">Sinopsis / Deskripsi Buku:</span>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        {{ $bookDetail->description ?? 'Tidak ada sinopsis atau deskripsi yang tersedia untuk buku ini.' }}
                    </p>
                </div>

                <div class="flex justify-end">
                    <flux:modal.close><flux:button variant="primary" class="cursor-pointer">Tutup</flux:button></flux:modal.close>
                </div>
            @endif
        </div>
    </flux:modal>
</div>