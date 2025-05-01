<?php
/**
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author	 : Shiv Charan Panjeta < shiv@ozvid.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of OZVID Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\faq;

use app\components\TController;
use app\components\TModule;

/**
 * faq module definition class
 */
class Module extends TModule
{

    const NAME = 'faq';

    public $controllerNamespace = 'app\modules\faq\controllers';

    // public $defaultRoute = 'faq';
    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Faqs'), '//faq/faq', 'question ', Module::isAdmin(), [ // TController::addMenu(\Yii::t('app', 'Home'), '//faq', 'lock', Module::isAdmin()),
        ]);
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    public static function getRules()
    {
        return [
            'faq/<id:\d+>/<title>' => 'faq/post/view',
            'faq/<action>' => 'faq/<action>/index',
            'faq/<action>/view/<id:\d+>' => 'faq/<action>/view'
        ];
    }
}
