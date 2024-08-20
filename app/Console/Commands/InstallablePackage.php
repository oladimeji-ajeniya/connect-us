<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Madnest\Madzipper\Facades\Madzipper;

class InstallablePackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prepare:installable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an installable package.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        File::deleteDirectory('.idea');
        Artisan::call('debugbar:clear');

        File::deleteDirectory('storage/app/public');
        Madzipper::make('installation/backup/public.zip')->extractTo('storage/app/public');

        $dot_env = base_path('.env');
        $new_env = base_path('.env.example');
        copy($new_env, $dot_env);

        $routes = base_path('app/Providers/RouteServiceProvider.php');
        $new_routes = base_path('installation/activate_install_routes.txt');
        copy($new_routes, $routes);

        Artisan::call('modules:disable');

        return 0;
    }
}