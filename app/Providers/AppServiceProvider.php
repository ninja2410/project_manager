<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('money', function ($amount) {
            return "<?php echo 'Q ' . number_format($amount, 2); ?>";
        });

        Blade::directive('pct', function ($amount) {
            return "<?php echo number_format($amount, 2).' % '; ?>";
        });
        Blade::directive('break', function() { return "<?php break; ?>"; });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
