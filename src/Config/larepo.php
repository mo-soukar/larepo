<?php
return [
    'data_limit'    => [
        'pagination' => [
            'pageName'   => 'page',
            'itemsCount' => 10,
        ],
        'take'       => [
            'itemsCount' => 10,
        ],
    ],
    'interfaces'    => [
        'namespace' => 'App\Interfaces\LaRepositories',
        'path'      => 'Interfaces/LaRepositories',
        //Relative Path After app
    ],
    'repositories'  => [
        'namespace' => 'App\LaRepositories',
        'path'      => 'LaRepositories',
        //Relative Path After app
    ],
    'DTO'           => [
        'namespace' => 'App\DTO',
        'path'      => 'DTO',
        //Relative Path After app
    ],
    'requests'      => [
        'namespace' => 'App\Http\Requests',
        'path'      => 'Http/Requests',
        //Relative Path After app
    ],
    'controllers'   => [
        'namespace' => 'App\Http\Controllers',
        'path'      => 'Http/Controllers',
    ],
    'responseTrait' => \Soukar\Larepo\Traits\ApiResponse::class,
];
