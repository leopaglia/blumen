<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class RepositoryBindingsServiceProvider extends ServiceProvider
{
    /**
     * Bind repositories implementations to interfaces based on bindings configuration file
     *
     * @return void
     */
    public function register()
    {
        // if the bindings config file has not been loaded, we load it manually
        if(!config('bindings')) {
            config(['bindings'], require __DIR__ . '/../../config/bindings.php');
        }

        $repositoryBindings = config('bindings.repositories.bindings');

        foreach($repositoryBindings as $interface => $implementation) {

            $namespaces = config('bindings.repositories.namespaces');

            // build the fully qualified name for each class
            $contract = $namespaces['contracts'].$interface;
            $repository = $namespaces['repositories'].$implementation;

            // if the cache option is enabled, set a closure with the decoration into the concrete variable
            // if not, just send the namespace of the repository
            if(config('bindings.repositories.cache')) {
                $cache = $namespaces['cache'].$implementation.'Cache'; // fully qualified name for cache decorator
                $concrete = function() use($cache, $repository) {
                    return new $cache($this->app['cache.store'], new $repository());
                };
            } else {
                $concrete = $repository;
            }

            // finally, bind the concrete class to the contract
            App::bind($contract, $concrete);
        }
    }
}
