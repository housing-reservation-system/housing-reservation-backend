<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->registerObservers();
    }

    public function boot(): void {
        if (config('app.env') !== 'local') {
        \URL::forceScheme('https');
         }   
    }

    private function registerObservers(): void
    {
        User::observe(UserObserver::class);
    }
}
