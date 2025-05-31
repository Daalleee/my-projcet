<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Resources\RentalResource;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;

// 1. Import model User Anda dan kontrak Authenticatable
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Models\User; // Sesuaikan jika path model User Anda berbeda

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->resources([RentalResource::class])
            ->middleware(['auth', 'role:admin'])
            ->authGuard('web')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,

            ]);
    }

    // 2. Tambahkan metode canAccessPanel
    /**
     * Menentukan apakah pengguna yang diautentikasi saat ini dapat mengakses panel ini.
     *
     * @param  Authenticatable|User  $user Pengguna yang diautentikasi.
     *                                     Pastikan untuk type-hint dengan model User spesifik Anda
     *                                     jika Anda perlu mengakses properti/metode kustomnya.
     * @return bool
     */
    public function canAccessPanel(Authenticatable $user): bool
    {
        // Pastikan $user adalah instance dari model User Anda
        if (!$user instanceof User) {
            return false;
        }

        // Contoh 1: Jika Anda memiliki kolom 'role' di tabel users
        // return $user->role === 'admin';

        // Contoh 2: Jika Anda memiliki kolom boolean 'is_admin' di tabel users
        // return $user->is_admin;

        // Contoh 3: Jika Anda menggunakan Spatie Laravel Permission package
        // Pastikan model User Anda menggunakan trait HasRoles
        // return $user->hasRole('admin');

        // --- PILIH SALAH SATU DARI CONTOH DI ATAS ATAU SESUAIKAN ---
        // Misalnya, kita asumsikan Anda punya kolom 'role' dengan nilai 'admin'
        return $user->role === 'admin';
    }
}
