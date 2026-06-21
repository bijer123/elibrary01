<div class="max-w-7xl mx-auto space-y-4">
    <flux:heading size="xl" class="text-zinc-800 dark:text-white">Manajemen User</flux:heading>
    <flux:subheading size="lg" class="text-zinc-600 dark:text-zinc-400">Kelola akun admin, petugas, dan mahasiswa</flux:subheading>
    <flux:separator variant="subtle" />

    <flux:button variant="primary" icon="plus" wire:click="create">Tambah User</flux:button>

    <div class="overflow-x-auto">
        <flux:table :paginate="$users">
            <flux:table.columns>
                <flux:table.column>No</flux:table.column>
                <flux:table.column>Nama</flux:table.column>
                <flux:table.column>Email</flux:table.column>
                <flux:table.column>Role</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($users as $user)
                    <flux:table.row :key="$user->id">
                        <flux:table.cell>{{ $loop->iteration + $users->firstItem() - 1 }}</flux:table.cell>
                        <flux:table.cell>{{ $user->name }}</flux:table.cell>
                        <flux:table.cell>{{ $user->email }}</flux:table.cell>
                        <flux:table.cell>
                            {{-- ✅ Badge warna berbeda per role --}}
                            <flux:badge :variant="match($user->role) {
                                'admin'   => 'primary',
                                'petugas' => 'warning',
                                default   => 'outline',
                            }">
                                {{ ucfirst($user->role) }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"></flux:button>
                                <flux:menu>
                                    <flux:menu.item icon="pencil" wire:click="edit({{ $user->id }})">Edit</flux:menu.item>
                                    <flux:menu.separator />
                                    <flux:menu.item
                                        variant="danger"
                                        icon="trash"
                                        wire:click="delete({{ $user->id }})"
                                        wire:confirm="Yakin ingin menghapus user ini?"
                                    >Hapus</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>

    <flux:modal wire:model="showModal" class="md:w-96">
        <form wire:submit="save" class="space-y-4">
            <flux:heading size="lg">{{ $editingUserId ? 'Edit User' : 'Tambah User' }}</flux:heading>

            <flux:input wire:model="name" label="Nama" placeholder="Nama lengkap" />
            <flux:input wire:model="email" label="Email" type="email" placeholder="email@contoh.com" />
            <flux:input
                wire:model="password"
                label="Password"
                type="password"
                :placeholder="$editingUserId ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter'"
            />

            {{-- ✅ Tambah opsi Petugas di dropdown --}}
            <flux:select wire:model="role" label="Role">
                <flux:select.option value="student">Mahasiswa</flux:select.option>
                <flux:select.option value="petugas">Petugas</flux:select.option>
                <flux:select.option value="admin">Admin</flux:select.option>
            </flux:select>

            <div class="flex justify-end gap-2">
                <flux:button type="button" variant="ghost" wire:click="$set('showModal', false)">Batal</flux:button>
                <flux:button type="submit" variant="primary">Simpan</flux:button>
            </div>
        </form>
    </flux:modal>
</div>