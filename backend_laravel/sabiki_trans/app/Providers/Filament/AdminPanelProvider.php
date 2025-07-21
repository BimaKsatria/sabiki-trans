<?php

namespace App\Providers\Filament;

use App\Filament\Resources\PhotoBannerResource;
use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Widgets\PaymentStats;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use App\Filament\Pages\LaporanKeuangan;
use App\Filament\Pages\Auth\LoginCustom;
use App\Filament\Resources\CarsResource;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationGroup;
use Rupadana\ApiService\ApiServicePlugin;
use App\Filament\Resources\DriverResource;
use App\Filament\Resources\RatingResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Pages\Auth\RegisterCustom;
use App\Filament\Resources\DamagesResource;
use App\Filament\Resources\PaymentResource;
use App\Filament\Resources\RentalsResource;
use App\Filament\Resources\BookingsResource;
use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\DiscountResource;
use App\Filament\Resources\CategoriesResource;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Resources\PaymentReportResource;
use Filament\Http\Middleware\AuthenticateSession;
use App\Filament\Resources\DiscountUsagesResource;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentShield\Resources\RoleResource;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;



class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel

            ->brandName('Sabiki Trans')
            ->brandLogo(url('images/logosabiki.png'))
            ->brandLogoHeight('40px')
            ->darkMode(false)
            

            ->renderHook(
                'panels::head.start',
                fn() => <<<'HTML'
                <style>
                    body {
                        background-image: url('/images/background.jpg') !important;
                        background-size: cover !important;
                        background-repeat: no-repeat !important;
                        background-attachment: fixed !important;   
                        background-position: center !important;
                    }
                    
                    .fi-main {
                        background: transparent;
                    }
                    
                    .fi-topbar, .fi-sidebar-nav {
                        background: rgba(255, 255, 255, 0.8);
                    }
                    
                    .fi-card {
                        background: rgba(255, 255, 255, 0.9);
                    }
                </style>
                HTML
            )

            ->default()
            ->id('admin')
            ->path('admin')
            ->login(LoginCustom::class)
            //->registration(RegisterCustom::class)
            ->passwordReset()
            ->profile()
            ->favicon('logos.png')
            ->colors([
                'primary' => Color::Red,
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
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
                EnsureEmailIsVerified::class,
            ])
            ->plugins([
                ApiServicePlugin::make(),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make('Dashboard')
                        ->items([
                            ...Dashboard::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Manajemen Pengguna')
                        ->items([
                            ...UserResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Akses Kontrol')
                        ->items([
                            ...RoleResource::getNavigationItems(),
                        ]),

                    // 🚗 Grup Data Mobil & Driver
                    NavigationGroup::make('Manajemen Kendaraan')
                        ->items([
                            ...CategoriesResource::getNavigationItems(),
                            ...CarsResource::getNavigationItems(),
                            ...DriverResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Manajemen Fitur')
                        ->items([
                            ...PhotoBannerResource::getNavigationItems(),
                        ]),

                    // 📋 Grup Transaksi & Pemesanan
                    NavigationGroup::make('Transaksi')
                        ->items([
                            ...DiscountResource::getNavigationItems(),
                            ...BookingsResource::getNavigationItems(),
                            ...RentalsResource::getNavigationItems(),
                            ...PaymentResource::getNavigationItems(),
                            ...DiscountUsagesResource::getNavigationItems(),
                        ]),

                    // 👥 Grup Data Pelanggan & Feedback
                    NavigationGroup::make('Hubungan Pelanggan')
                        ->items([
                            ...CustomerResource::getNavigationItems(),
                            ...RatingResource::getNavigationItems(),
                        ]),

                    // ⚠️ Grup Data Kerusakan
                    NavigationGroup::make('Laporan')
                        ->items([
                            ...PaymentReportResource::getNavigationItems(),
                            ...DamagesResource::getNavigationItems(),
                        ]),

                ]);
            })


        ;
    }
}
