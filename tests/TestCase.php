<?php
namespace glorifiedking\BusTravel\Tests;

use glorifiedking\BusTravel\BusTravelBaseServiceProvider;
use Route;

class TestCase extends \Orchestra\Testbench\TestCase 
{
    protected function setUp(): void
    {
        parent::setUp();
        Route::auth();
        $this->loadLaravelMigrations();
        $this->withFactories(__DIR__.'/../database/factories');
    }

    protected function getPackageProviders($app)
    {
        return BusTravelBaseServiceProvider::class;
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key','base64:7cTani6npGETrhDkHpBxFjD1gztdumNrLkJAShMg+zI=');
        $app['config']->set('database.default','testdb');
        $app['config']->set('database.connections.testdb',[
            'driver' => 'sqlite',
            'database' => ':memory:'
        ]);
    }
}