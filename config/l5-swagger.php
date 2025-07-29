<?php

return [

    'default' => 'default',

    'documentations' => [

        'default' => [
            'api' => [
                'title' => env('L5_SWAGGER_API_TITLE', 'Azka Garden API'),
            ],

            'routes' => [
                /*
                 * Route for accessing parsed swagger annotations.
                 */
                'api' => 'api/documentation',
            ],

            'paths' => [
                /*
                 * Edit to include directories containing your annotations.
                 */
                'annotations' => [
                    base_path('app'),
                ],
                'docs_json'  => storage_path('api-docs/api-docs.json'),
                'docs_yaml'  => storage_path('api-docs/api-docs.yaml'),
                'views'      => base_path('resources/views/vendor/l5-swagger'),
                'base'       => env('L5_SWAGGER_BASE_PATH', null),
                'swagger_ui_assets_path' => env('L5_SWAGGER_UI_ASSETS_PATH'),
            ],

            'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),

            'swagger_version' => env('L5_SWAGGER_VERSION', '3.0'),

            'proxy' => false,
        ],

    ],

];
