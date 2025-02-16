<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'relations_namespace' => 'Illuminate\Database\Eloquent\Relations',
    'cache' => [
        'column_types' => [
            'enabled' => true,
            'key' => 'column_types',
            'ttl' => 86400, // 1 day
        ],
        // 'relationships' => [
        //     'enabled' => true,
        //     'key' => 'relationships',
        //     'ttl' => 86400, // 1 day
        // ],
    ],
];
