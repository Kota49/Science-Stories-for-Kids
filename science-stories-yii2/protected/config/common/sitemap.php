<?php
return [
    'sitemap' => [
        'class' => 'app\modules\sitemap\Module',
        'models' => [ // your models
                       // 'app\modules\feature\models\Feature'
        ],

        'urls' => [
            [
                'loc' => '/',
                'priority' => '1.0'
            ],

            [
                'loc' => '/site/about'
            ],

            [
                'loc' => '/site/privacy'
            ],
            [
                'loc' => '/site/terms'
            ]
        ],
        'enableGzip' => true
    ]
];

