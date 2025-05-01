<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\shadow;

use app\components\TController;
use app\components\TModule;

/**
 * shadow module definition class
 */
class Module extends TModule
{

    /**
     *
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\shadow\controllers';

    public $defaultRoute = 'session';

    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Shadows'), '//shadow/session', 'sign-in', (Module::isAdmin()));
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }
}
