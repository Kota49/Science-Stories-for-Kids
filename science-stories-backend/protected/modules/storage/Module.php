<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\storage;

use app\components\TController;
use app\components\TModule;

/**
 * storage module definition class
 */
class Module extends TModule
{

    const NAME = 'storage';

    public $controllerNamespace = 'app\modules\storage\controllers';

    // public $defaultRoute = 'storage';
    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Storage List'), '#', 'database ', Module::isManager(), [
            TController::addMenu(\Yii::t('app', 'Home'), '/storage/default/index', 'home', Module::isManager()),
            TController::addMenu(\Yii::t('app', 'Providers'), '/storage/provider', 'list', Module::isManager()),
            TController::addMenu(\Yii::t('app', 'Files'), '/storage/file', 'file', Module::isManager()),
            TController::addMenu(\Yii::t('app', 'Types'), '/storage/type', 'list-check', Module::isManager())
        ]);
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    public static function getRules()
    {
        return [
            'file/<action:[A-Za-z-]+>/<id:\d+>/<title>' => 'storage/file/<action>',
            'file/<action:[A-Za-z-]+>/<id:\d+>' => 'storage/file/<action>',
            'file/<action:[A-Za-z-]+>' => 'storage/file/<action>',
            'file/<id:\d+>/<title>' => 'storage/file/view'
        ];
    }
}
