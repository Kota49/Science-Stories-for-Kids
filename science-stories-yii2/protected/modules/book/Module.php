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
namespace app\modules\book;
use app\components\TController;
use app\components\TModule;
use app\models\User;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;

/**
 * book module definition class
 */
class Module extends TModule
{
    const NAME = 'book';

    public $controllerNamespace = 'app\modules\book\controllers';
	
	//public $defaultRoute = 'book';
	


    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Books'), '#', 'key ', Module::isAdmin(), [
           // TController::addMenu(\Yii::t('app', 'Home'), '//book', 'lock', Module::isAdmin()),
        ]);
    }
    
    
    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }
    
    
    public static function getRules()
    {
        return [
            'book/<id:\d+>/<title>' => 'book/post/view',
            'book/<action>' => 'book/<action>/index',
            'book/<action>/view/<id:\d+>' => 'book/<action>/view',    
        
        ];
    }
    
    
}
