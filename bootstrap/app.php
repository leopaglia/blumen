<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once 'ApplicationAspectKernel.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
| The transactional application wraps every request in a DB transaction.
| There is also available the normal Lumen application to use.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

 $app->withFacades();
 $app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
    App\Http\Middleware\ExampleMiddleware::class
]);

/*
|--------------------------------------------------------------------------
| Load Custom Configuration Files
|--------------------------------------------------------------------------
|
| Here we can load custom configuration files into the application's
| config array, that would be later accessible with the global config
| function.
*/

//$app->configure('bindings');
$app->configure('constants');
//$app->configure('swagger-lume');

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

if(in_array(env('APP_ENV'), ['LOCAL', 'DEV'])) {
    $app->register(\Blumen\Generators\Providers\GeneratorServiceProvider::class);
    $app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
}

/*
|--------------------------------------------------------------------------
| Initialize an application aspect container
|--------------------------------------------------------------------------
*/
$applicationAspectKernel = ApplicationAspectKernel::getInstance();
$applicationAspectKernel->init(array(
    'debug' => true, // use 'false' for production mode
    // Cache directory
    'cacheDir'  => storage_path('framework/cache'),
    // Include paths restricts the directories where aspects should be applied, or empty for all source files
    'includePaths' => array(__DIR__ . '/../app/')
));

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

// Health route
$app->group(['namespace' => 'App\Http\Controllers'], function (Laravel\Lumen\Application $app) {
    $app->get('/', [
        'uses' => 'StatusController@getStatus'
    ]);
});

// App routes - Load every route file in the Http/Routes folder
// TODO: make easier to use route level middlewares
$files = (new Illuminate\Filesystem\Filesystem())->allFiles(__DIR__ . '/../app/Http/Routes');
foreach ($files as $file) {
    $file = $file->getFileName();
    $prefix = strtolower(basename($file, ".php"));
    $app->group(['namespace' => 'App\Http\Controllers', 'prefix' => $prefix], function ($app) use ($file) {
        require __DIR__ . '/../app/Http/Routes' . '/' . $file;
    });
}

return $app;
