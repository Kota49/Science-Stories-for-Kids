<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
if (php_sapi_name() != "cli") {
    echo 'Please run this file from command line interface !!!';
    exit();
}

$params = require (__DIR__ . '/params.php');

$config = [
    'id' => PROJECT_ID,
    'name' => PROJECT_NAME,
    'basePath' => PROTECTED_PATH,
    'runtimePath' => RUNTIME_PATH,
    'bootstrap' => [
        'log',
        'app\components\TBootstrap'
    ],
    'vendorPath' => VENDOR_PATH,
    'timeZone' => date_default_timezone_get(),
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'clear' => 'app\components\commands\ClearController',
        'module' => 'app\components\commands\ModuleController',
        'user' => 'app\components\commands\UserController',
        'email-queue' => 'app\modules\smtp\commands\EmailQueueController'
    ],
    'components' => [

        'cache' => [
            'class' => 'yii\caching\FileCache'
        ],
        'settings' => [
            'class' => 'app\modules\settings\components\Keys'
        ],
        'formatter' => [
            'class' => 'app\components\formatter\TFormatter',
            'thousandSeparator' => ',',
            'decimalSeparator' => '.',
            'defaultTimeZone' => date_default_timezone_get(),
            'datetimeFormat' => 'php:Y-m-d h:i:s A',
            'dateFormat' => 'php:Y-m-d'
        ],
        'log' => [
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
        'firebase' => [
            'class' => 'app\modules\notification\components\FireBaseNotification'
        ],
        'mailer' => [
            'class' => 'app\modules\smtp\components\SmtpMailer'
        ]
    ],
    'modules' => [],
    'params' => $params,
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset'
    ]
];

$config['components']['urlManager'] = require 'url-manager.php';

if (file_exists(DB_CONFIG_FILE_PATH)) {
    $config['components']['db'] = require (DB_CONFIG_FILE_PATH);
}

$config['modules']['installer'] = [
    'class' => 'app\modules\installer\Module',
    'sqlfile' => [
        DB_BACKUP_FILE_PATH . '/install.sql'
    ]
];
$config['modules'] = array_merge($config['modules'], require 'modules.php');

return $config;
