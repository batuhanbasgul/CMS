<?php

namespace App\Providers;
use App\Services\FileManagerService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /** DEVELOPER
         * Binding FileManagerService for file processes.
         */
        $this->app->bind(FileManagerService::class, function($app){
            return new FileManagerService();
        });
    }
}
