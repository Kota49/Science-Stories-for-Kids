<?php
use yii\helpers\Inflector;

/**
 * This is the template for generating a module class file.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

$className = $generator->moduleClass;
$pos = strrpos($className, '\\');
$ns = ltrim(substr($className, 0, $pos), '\\');
$className = substr($className, $pos + 1);

echo "<?php\n";
?>
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
namespace <?= $ns ?>;
use app\components\TController;
use app\components\TModule;
use app\models\User;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;

/**
 * <?= $generator->moduleID ?> module definition class
 */
class <?= $className ?> extends TModule
{
    const NAME = '<?= $generator->moduleID ?>';

    public $controllerNamespace = '<?= $generator->getControllerNamespace() ?>';
	
	//public $defaultRoute = '<?= $generator->moduleID ?>';
	


    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', '<?= Inflector::camel2words(Inflector::pluralize($generator->moduleID)) ?>'), '#', 'key ', Module::isAdmin(), [
           // TController::addMenu(\Yii::t('app', 'Home'), '//<?= $generator->moduleID ?>', 'lock', Module::isAdmin()),
        ]);
    }
    
    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }
    
    
    public static function getRules()
    {
        return [
            '<?= $generator->moduleID ?>/<id:\d+>/<title>' => '<?= $generator->moduleID ?>/post/view',
            '<?= $generator->moduleID ?>/<action>' => '<?= $generator->moduleID ?>/<action>/index',
            '<?= $generator->moduleID ?>/<action>/view/<id:\d+>' => '<?= $generator->moduleID ?>/<action>/view',    
        
        ];
    }
    
    
}
