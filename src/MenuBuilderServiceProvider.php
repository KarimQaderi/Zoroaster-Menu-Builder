<?php

    namespace KarimQaderi\ZoroasterMenuBuilder;

    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\ServiceProvider;
    use KarimQaderi\Zoroaster\Sidebar\FieldMenu\MenuItem;
    use KarimQaderi\ZoroasterMenuBuilder\Http\Resources\MenuResource;
    use KarimQaderi\Zoroaster\Zoroaster;

    class MenuBuilderServiceProvider extends ServiceProvider
    {
        /**
         * Bootstrap any application services.
         *
         * @return void
         */
        public function boot()
        {

            $this->loadViewsFrom(__DIR__ . '/../resources/views' , 'Zoroaster-menu-builder');


            $this->app->booted(function(){
                $this->routes();
            });


            $this->publishMigrations();


            Zoroaster::resources([
                MenuResource::class ,
            ]);

            Zoroaster::SidebarMenus([
                MenuItem::make()->resource('menu-builder')->icon('list') ,
            ]);


            Zoroaster::script(asset('Zoroaster-assets/ZoroasterMenuBuilder/js/tool.js'));
            Zoroaster::style(asset('Zoroaster-assets/ZoroasterMenuBuilder/css/tool.css'));


        }

        /**
         * Register the tool's routes.
         *
         * @return void
         */
        protected function routes()
        {
            if($this->app->routesAreCached()){
                return;
            }


            Route::middleware(['web' , 'Zoroaster' , 'can:Zoroaster' , 'can:ZoroasterMenuBuilder'])
                ->namespace('\KarimQaderi\ZoroasterMenuBuilder\Http\Controllers')
                ->prefix('Zoroaster-vendor/menu-builder')
                ->as('Zoroaster-menu-builder.')
                ->group(__DIR__ . '/../routes/web.php');


        }

        /**
         * Publish required migration
         */
        private function publishMigrations()
        {
            $this->publishes([
                __DIR__ . '/Migrations/create_menus_table.php.stub' => database_path('migrations/' . date('Y_m_d_His' , time()) . '_create_menus_table.php') ,
            ] , 'menu-builder-migration');

            $this->publishes([
                __DIR__ . '/../dist' => public_path('Zoroaster-assets/ZoroasterMenuBuilder')
            ] , 'menu-builder-assets');
        }

        /**
         * Register any application services.
         *
         * @return void
         */
        public function register()
        {


        }
    }
