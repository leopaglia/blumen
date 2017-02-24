<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class BindingsProvider extends ServiceProvider
{
    /**
     * Bind implementations to interfaces
     *
     * @return void
     */
    public function register()
    {
        // if the bindings config file has not been loaded, we load it manually
        if(!config('bindings')) {
            config(['bindings'], require __DIR__ . '/../../config/bindings.php');
        }

        $bindings = config('bindings');

        if(empty($bindings)) return;

        foreach($bindings as $contract => $implementation) App::bind($contract, $implementation);
    }
}
