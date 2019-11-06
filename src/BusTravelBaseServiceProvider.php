<?php
namespace glorifiedking\BusTravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Route;

class BusTravelBaseServiceProvider extends ServiceProvider 
{
    /**
     * bootstrap package
     * 
     */
    public function boot(Dispatcher $events)
    {
        if($this->app->runningInConsole())
        {
            $this->registerPublishing();
        }        
        $this->registerRoutes();
        $this->registerResources();
        //create menu 
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $settings_menu = [
                //        [
                    'text' => 'Settings',
                    'url'  => '#',
                    'icon' => 'cog',
                    'submenu' =>
                    [          				
                        [
                            'text' => 'General Settings',
                            'url'  => route('bustravel.general_settings'),
                                                    
                        ],
                            
                        [
                            'text' => 'Company Settings',
                            'url'  => route('bustravel.company_settings'),                            
                            
                        ],
                        [
                            'text' => 'Stations',
                            'url'  => route('bustravel.stations'),                            
                            'icon' => 'clipboard',
                        ],       					
          				
                    ]
        
            ];
            $operations_menu = [       
            'text' => 'Operations',
            'url'  => '#',
            'icon' => 'cog',
            'submenu' =>
            [          				
                [
                    'text' => 'Operators',
                    'url'  => route('bustravel.operators'),
                                            
                ],
                    
                [
                    'text' => 'Buses',
                    'url'  => route('bustravel.buses'),                            
                    
                ],
                [
                    'text' => 'Routes',
                    'url'  => route('bustravel.routes'),                            
                    
                ],
                [
                    'text' => 'Drivers',
                    'url'  => route('bustravel.drivers'),                                
                    
                ],
                [
                    'text' => 'Bookings',
                    'url'  => route('bustravel.bookings'),                                
                    
                ],       					
                    
            ],
   
            ];
            $reports_menu = [    
            'text' => 'Reports',
            'url'  => '#',
            'icon' => 'cog',
            'submenu' =>
                [          				
                    [
                        'text' => 'Sales',
                        'url'  => route('bustravel.reports.sales'),
                                                
                    ],
                        
                    [
                        'text' => 'Profitable Routes',
                        'url'  => route('bustravel.reports.profitroute'),                            
                        
                    ],
                    [
                        'text' => 'Passenger Traffic',
                        'url'  => route('bustravel.reports.traffic'),                            
                        
                    ],
                    [
                        'text' => 'Locations',
                        'url'  => route('bustravel.reports.locations'),                                
                        
                    ],
                    [
                        'text' => 'Bookings',
                        'url'  => route('bustravel.reports.bookings'),                                
                        
                    ],       					
                        
                ], 
    
            ];
            $users_menu = [
        
                        'text' => 'User and Profile',
                        'url'  => '#',
                        'icon' => 'fas fa-fw fa-share',
                        'submenu' =>
                        [ 
                            [
                                'text' => 'Profile',
                                'url'  => route('bustravel.testdefault'),                                
                                
                            ],
                            [
                                'text' => 'Users',
                                'url'  => route('bustravel.testdefault'),                                
                                
                            ],
                            
                        ]
            //        ]
              
                ];
             
             $event->menu->add($settings_menu);
             $event->menu->add($operations_menu);
             $event->menu->add($reports_menu);             
             $event->menu->add($users_menu);
        });
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
        
        $this->publishes([
            __DIR__.'/../assets' => public_path('vendor/glorifiedking'),
        ], 'bustravel-assets');
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