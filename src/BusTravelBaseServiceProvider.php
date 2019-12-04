<?php

namespace glorifiedking\BusTravel;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Route;

class BusTravelBaseServiceProvider extends ServiceProvider
{
    /**
     * bootstrap package.
     */
    public function boot(Dispatcher $events)
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }
        $this->registerRoutes();
        $this->registerResources();
        //create menu
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $settings_menu = [
                //        [
                    'text'    => 'Settings',
                    'url'     => '#',
                    'icon'    => 'fa fa-cog',
                    'submenu' => [
                        [
                            'text' => 'General Settings',
                            'url'  => route('bustravel.general_settings'),
                            'icon' => 'fa fa-cog',

                        ],

                        [
                            'text'    => 'Company Settings',
                            'url'     => route('bustravel.company_settings'),
                            'icon'    => 'fa fa-cog',
                            'submenu' => [
                              [
                                  'text' => 'Booking Custom Fields',
                                  'url'  => route('bustravel.company_settings.fields'),
                                  'icon' => 'clipboard',
                              ],
                            ],

                        ],
                        [
                            'text' => 'Stations',
                            'url'  => route('bustravel.stations'),
                            'icon' => 'fa fa-map-marker-alt',
                        ],

                    ],

            ];
            $operations_menu = [
            'text'    => 'Operations',
            'url'     => '#',
            'icon'    => 'fa fa-cube',
            'submenu' => [
                [
                    'text' => 'Operators',
                    'url'  => route('bustravel.operators'),
                    'icon' => 'fa fa-list',

                ],

                [
                    'text' => 'Buses',
                    'url'  => route('bustravel.buses'),
                    'icon' => 'fa fa-bus',

                ],
                [
                    'text' => 'Routes',
                    'url'  => route('bustravel.routes'),
                    'icon' => 'fa fa-route',

                ],
                [
                    'text' => 'Drivers',
                    'url'  => route('bustravel.drivers'),
                    'icon' => 'fa fa-user',

                ],
                [
                    'text' => 'Routes Departures Times',
                    'url'  => route('bustravel.routes.departures'),
                    'icon' => 'fa fa-clock',

                ],
                [
                    'text' => 'Bookings',
                    'url'  => route('bustravel.bookings'),
                    'icon' => 'fa fa-money-check',

                ],

            ],

            ];
            $reports_menu = [
            'text'    => 'Reports',
            'url'     => '#',
            'icon'    => 'fa fa-list',
            'submenu' => [
                    [
                        'text' => 'Sales',
                        'url'  => route('bustravel.reports.sales'),
                        'icon' => 'fa fa-money-bill',

                    ],

                    [
                        'text' => 'Profitable Routes',
                        'url'  => route('bustravel.reports.profitroute'),
                        'icon' => 'fa fa-route',

                    ],
                    [
                        'text' => 'Passenger Traffic',
                        'url'  => route('bustravel.reports.traffic'),
                        'icon' => 'fa fa-traffic-light',

                    ],
                    [
                        'text' => 'Locations',
                      //  'url'  => route('bustravel.reports.locations'),
                        'icon'=> 'fa fa-search-location',

                    ],
                    [
                        'text' => 'Bookings',
                        'url'  => route('bustravel.reports.bookings'),
                        'icon' => 'fa fa-money-check',

                    ],

                ],

            ];
            $users_menu = [

                        'text'    => 'User and Profile',
                        'url'     => '#',
                        'icon'    => 'fas fa-fw fa-users',
                        'submenu' => [
                            [
                                'text' => 'Profile',
                                'url'  => route('bustravel.testdefault'),
                                'icon' => 'fas fa-fw fa-address-card',

                            ],
                            [
                                'text'    => 'Users',
                                'url'     => route('bustravel.testdefault'),
                                  'icon'  => 'fa fa-users',
                                'submenu' => [
                                  [
                                      'text' => 'User Accounts',
                                      'url'  => route('bustravel.users'),
                                      'icon' => 'fa fa-lock',

                                  ],
                                  [
                                      'text' => 'Roles',
                                      'url'  => route('bustravel.users.roles'),
                                      'icon' => 'fa fa-lock',

                                  ],
                                  [
                                      'text' => 'Permissions',
                                      'url'  => route('bustravel.users.permissions'),
                                      'icon' => 'fa fa-lock',

                                  ],
                                ],
                            ],

                        ],
            //        ]

                ];

            $event->menu->add($settings_menu);
            $event->menu->add($operations_menu);
            $event->menu->add($reports_menu);
            $event->menu->add($users_menu);
        });
    }

    /**
     * register package.
     */
    public function register()
    {
        $this->app->register('JeroenNoten\LaravelAdminLte\AdminLteServiceProvider');
        $this->app->register('Spatie\Permission\PermissionServiceProvider');
    }

    private function registerResources()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bustravel');
    }

    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__.'/../config/bustravel.php' => config_path('bustravel.php'),
        ], 'bustravel-config');
        $this->publishes([
            __DIR__.'/../assets' => public_path('vendor/glorifiedking'),
        ], 'bustravel-assets');
        $this->publishes([
        __DIR__.'/../database/test_migrations/' => database_path('migrations'),
    ], 'bustravel-migrations');
        $this->publishes([
    __DIR__.'/../database/factories/' => database_path('factories'),
], 'bustravel-factories');
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
            'prefix'    => config('bustravel.path', 'transit'),
            'namespace' => 'glorifiedking\BusTravel\Http\Controllers',
        ];
    }
}
