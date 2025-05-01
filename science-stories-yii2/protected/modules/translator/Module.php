<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\translator;

use app\components\TController;
use app\components\TModule;
use app\models\User;

/**
 * translator module definition class
 */
class Module extends TModule
{

    const NAME = 'language-translator';

    public $controllerNamespace = 'app\modules\translator\controllers';

    public $defaultRoute = 'translator';

    public static function subNav()
    {
        if (method_exists("\app\components\WebUser", 'getIsAdminMode'))
            if (\Yii::$app->user->isAdminMode) {
                return self::adminSubNav();
            }
        return TController::addMenu(\Yii::t('app', 'Translators'), '#', 'language ', (Module::isManager()), [
            TController::addMenu(\Yii::t('app', 'Translators'), '/translator/language-option/index', 'language', (Module::isManager())),
            TController::addMenu(\Yii::t('app', 'i18n'), '/translator/i18n/index', 'lock', (Module::isManager()))
        ]);
    }

    public static function adminSubNav()
    {
        return TController::addMenu(\Yii::t('app', 'Translators'), '#', 'language ', (Module::isAdmin()), [
            TController::addMenu(\Yii::t('app', 'Translators'), '/translator/language-option/index', 'language', (Module::isAdmin())),
            TController::addMenu(\Yii::t('app', 'i18n'), '/translator/i18n/index', 'language', (Module::isAdmin()))
        ]);
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    public static function getRules()
    {
        return [
            'translator/<id:\d+>/<title>' => 'translator/post/view'
        ];
    }
}
