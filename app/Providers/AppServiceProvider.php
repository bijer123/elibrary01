<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        // Daftarkan namespace 'layouts' supaya sintaks <x-layouts::...> dan view('layouts::...') bisa di-resolve
        Blade::anonymousComponentNamespace('components.layouts', 'layouts');
        View::addNamespace('layouts', resource_path('views/components/layouts'));

        // Gate::before akan mengeksekusi pengecekan sebelum Gate individu
        Gate::before(function (User $user, string $ability) {
            if ($user->role === 'admin') {
                return true; // Admin otomatis lolos akses ke semua fitur
            }
        });

        Gate::define('isAdmin', function (User $user) {
            return $user->role === 'admin';
        });

        // ✅ TAMBAHAN: Gate untuk role Petugas
        Gate::define('isPetugas', function (User $user) {
            return $user->role === 'petugas';
        });

        // ✅ TAMBAHAN: Gate gabungan — Admin & Petugas bisa kelola perpustakaan
        Gate::define('manageLibrary', function (User $user) {
            return in_array($user->role, ['admin', 'petugas']);
        });

        Gate::define('isStudent', function (User $user) {
            return $user->role === 'student';
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}