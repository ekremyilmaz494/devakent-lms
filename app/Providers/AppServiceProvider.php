<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->isLocal()) {
            DB::listen(function ($query) {
                if ($query->time > 100) {
                    Log::channel('daily')->warning(
                        'Slow query (' . $query->time . 'ms): ' . $query->sql,
                        ['bindings' => $query->bindings]
                    );
                }
            });
        }
    }
}
