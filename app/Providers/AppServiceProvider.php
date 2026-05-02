<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

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
        FilamentView::registerRenderHook(
            'panels::head.end',
            fn(): \Illuminate\View\View => view('favicons'),
        );

        // Filament::registerRenderHook(
        //     'panels::topbar.end', // posisi kanan navbar
        //     fn() => new HtmlString('
        //     <div style="margin-right:15px;">
        //         <div class="gtranslate_wrapper"></div>
        //     </div>
        // ')
        // );

        Filament::registerRenderHook(
            'panels::topbar.start',
            fn() => new HtmlString('
                <div style="margin-right:10px;">
                    <div class="gtranslate_wrapper"></div>
                </div>
            ')
        );

        Filament::registerRenderHook(
            'panels::head.end',
            fn() => new HtmlString('
            <script>
            window.gtranslateSettings = {
                default_language: "en",
                languages: ["en", "id", "es", "fr", "de", "zh-CN", "ja", "ru", "ar", "pt"],
                wrapper_selector: ".gtranslate_wrapper"
            }
            </script>
            <script src="https://cdn.gtranslate.net/widgets/latest/flags.js" defer></script>
        ')
        );
    }
}
