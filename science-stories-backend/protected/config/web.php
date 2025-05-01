<?php

/**
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author	 : Shiv Charan Panjeta < shiv@ozvid.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of OZVID Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
$params = require (__DIR__ . '/params.php');

$config = [
    'id' => PROJECT_ID,
    'name' => PROJECT_NAME,
    'basePath' => PROTECTED_PATH,
    'runtimePath' => RUNTIME_PATH,
    'bootstrap' => [
        'log',
        'session',
        'app\components\TBootstrap',
        'languagepicker'
    ],
    'vendorPath' => VENDOR_PATH,
    'timeZone' => date_default_timezone_get(),
    'language' => 'en-AU',
    'components' => [

        'request' => [
            'class' => 'app\components\TRequest',
            'trustedHosts' => [
                '192.168.0.0/20'
            ]
        ],
        'settings' => [
            'class' => 'app\modules\settings\components\Keys'
        ],
        'session' => [
            'class' => 'app\components\TSession'
        ],
        'cache' => [
            'class' => (YII_ENV == 'dev') ? 'yii\caching\DummyCache' : 'yii\caching\FileCache',
            'defaultDuration' => 60
        ],
        'user' => [

            'class' => 'app\components\WebUser'
        ],
        'firebase' => [
            'class' => 'app\modules\notification\components\FireBaseNotification'
        ],
        'languagepicker' => [
            'class' => 'lajax\languagepicker\Component',
            'languages' => [
                'en' => 'English',
                'he' => 'Hebrew'
            ]
        ],
        'mailer' => [
            'class' => 'app\modules\smtp\components\SmtpMailer'
        ],
        'log' => [
            'traceLevel' => defined('YII_DEBUG') ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => [
                        'error',
                        'warning'
                    ]
                ]
            ]
        ],

        'formatter' => [
            'class' => 'app\components\formatter\TFormatter',
            'thousandSeparator' => ',',
            'decimalSeparator' => '.',
            'defaultTimeZone' => date_default_timezone_get(),
            'datetimeFormat' => 'php:Y-m-d h:i:s A',
            'dateFormat' => 'php:Y-m-d'
        ],

        'view' => [
            'theme' => [
                'class' => 'app\components\AppTheme',
                'name' => 'new'
                // 'style'=>'green'
            ]
        ]
    ],
    'params' => $params,
    'modules' => [],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset'
    ]
];

$config['components']['urlManager'] = require 'url-manager.php';

if (file_exists(DB_CONFIG_FILE_PATH)) {

    $config['components']['db'] = require (DB_CONFIG_FILE_PATH);
} else {
    $config['modules']['installer'] = [
        'class' => 'app\modules\installer\Module',
        'sqlfile' => [
            DB_BACKUP_FILE_PATH . '/install.sql'
        ]
    ];
}

if (YII_ENV == 'dev') {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => [
            '127.0.0.1',
            '::1',
            '192.168.10.*'
        ],
        'checkAccessCallback' => function ($app) {
            return Yii::$app->controller && Yii::$app->controller->module->id == 'debug';
        }
    ];

    $config['modules']['tugii'] = [
        'class' => 'app\modules\tugii\Module'
    ];
}
// Custom Logger
$config['modules']['logger'] = [
    'class' => 'app\modules\logger\Module',
    'viewPath' => '@app/views/logger/views'
];
$config['components']['errorHandler'] = [
    'class' => 'app\modules\logger\components\TErrorHandler'
];

if (defined('ENABLE_ERP')) {
    $config['defaultRoute'] = 'dashboard/index';
}

$config['modules']['comment'] = [
    'class' => 'app\modules\comment\Module'
    // 'enableRichText' => true
];
$config['modules']['shadow'] = [
    'class' => 'app\modules\shadow\Module'
];
$config['modules']['seo'] = [
    'class' => 'app\modules\seo\Module'
];
$config['modules']['contact'] = [
    'class' => 'app\modules\contact\Module'
];
$config['modules']['feature'] = [
    'class' => 'app\modules\feature\Module',
    'viewPath' => '@app/views/feature/views'
];

$config['modules']['api'] = [
    'class' => 'app\modules\api\Module'
];
$config['modules']['hotel'] = [
    'class' => 'app\modules\hotel\Module'
];
$config['modules']['event'] = [
    'class' => 'app\modules\event\Module'
];
$config['modules']['booking'] = [
    'class' => 'app\modules\booking\Module'
];
$config['modules']['page'] = [
    'class' => 'app\modules\page\Module',
    'viewPath' => '@app/views/page/views'
];
$config['modules']['book'] = [
    'class' => 'app\modules\book\Module',
    'viewPath' => '@app/modules/book/views'
];

$config['modules']['faq'] = [
    'class' => 'app\modules\faq\Module'
];
$config['modules']['translator'] = [
    'class' => 'app\modules\translator\Module'
];

$config['modules'] = array_merge($config['modules'], require 'modules.php');

return $config;
