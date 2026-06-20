<div class="p-6 max-w-4xl mx-auto space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-zinc-800 dark:text-white">Pengaturan Akun</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400">Kelola profil dan keamanan akun E-Library kamu</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-zinc-700 dark:text-zinc-300">Informasi Profil</h2>
                <p class="text-xs text-zinc-400">Perbarui nama dan email akun kamu</p>
            </div>

            <div class="space-y-4">
                <flux:input wire:model="name" label="Nama Lengkap" placeholder="Masukkan nama kamu" />
                <flux:input wire:model="email" label="Email" type="email" placeholder="Masukkan email kamu" />

                <div class="flex justify-end pt-2">
                    <flux:button wire:click="updateProfile" variant="primary" class="cursor-pointer">Simpan Perubahan</flux:button>
                </div>
            </div>
        </div>

        <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-zinc-700 dark:text-zinc-300">Keamanan Password</h2>
                <p class="text-xs text-zinc-400">Ubah password akses E-Library kamu</p>
            </div>

            <div class="space-y-4">
                <flux:input wire:model="current_password" label="Password Saat Ini" type="password" />
                <flux:input wire:model="password" label="Password Baru" type="password" />
                <flux:input wire:model="password_confirmation" label="Konfirmasi Password Baru" type="password" />

                <div class="flex justify-end pt-2">
                    <flux:button wire:click="updatePassword" variant="primary" class="cursor-pointer">Ubah Password</flux:button>
                </div>
            </div>
        </div>
    </div>
</div>