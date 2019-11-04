<?php
namespace glorifiedking\BusTravel;

use Illuminate\Support\ServiceProvider;
use Route;

class BusTravelBaseServiceProvider extends ServiceProvider 
{
    /**
     * bootstrap package
     * 
     */
    public function boot()
    {
        if($this->app->runningInConsole())
        {
            $this->registerPublishing();
        }        
        $this->registerRoutes();
        $this->registerResources();
    }

    /**
     * register package 
     */
    public function register()
    {

    }

    private function registerResources()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views','bustravel');
    }

    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__.'/../config/bustravel.php' => config_path('bustravel.php')
        ],'bustravel-config');
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    private function routeConfiguration()
    {
        return [
            "prefix" => config('bustravel.path','transit'),
            "namespace" => 'glorifiedking\BusTravel\Http\Controllers',
        ];
    }
}