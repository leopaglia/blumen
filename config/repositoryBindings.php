<?php

return [

    'repositories' => [

        'cache' => true,

        'namespaces' => [
            'contracts' => 'App\Repositories\Contracts\\',
            'repositories' => 'App\Repositories\\',
            'cache' => 'App\Repositories\Decorators\\',
        ],

        'bindings' => [
            // set the bindings here as contract => implementation
        ]

    ]

];

