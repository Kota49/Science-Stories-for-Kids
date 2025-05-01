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
namespace app\modules\tugii\generators\tumodule;

use yii\gii\CodeFile;
use yii\helpers\StringHelper;

/**
 * This generator will generate the skeleton code needed by a module.
 *
 * @property string $controllerNamespace The controller namespace of the module. This property is read-only.
 * @property boolean $modulePath The directory that contains the module class. This property is read-only.
 *          
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\generators\module\Generator
{

    public $enableTest = false;

    public $migrateName;

    /**
     *
     * @inheritdoc
     */
    public function getName()
    {
        return 'TuGii Module Generator';
    }

    /**
     *
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator helps you to generate the skeleton code needed by a Yii module.';
    }

    public function beforeValidate()
    {
        if (empty($this->moduleClass)) {
            $this->moduleClass = str_replace('moduleName', $this->moduleID, 'app\\modules\\moduleName\\Module');
        }
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];

        $modulePath = $this->getModulePath();
        $files[] = new CodeFile($modulePath . '/' . StringHelper::basename($this->moduleClass) . '.php', $this->render("module.php"));
        $files[] = new CodeFile($modulePath . '/controllers/DefaultController.php', $this->render("controller.php"));
        $files[] = new CodeFile($modulePath . '/views/default/index.php', $this->render("view.php"));

        $this->migrateName = 'm' . date('ymd_Hmi_') . '_install_' . $this->moduleID;
        $files[] = new CodeFile($modulePath . '/migrations/' . $this->migrateName . '.php', $this->render('migration.php'));
        $files[] = new CodeFile($modulePath . '/composer.json', $this->render("composer.php"));

        return $files;
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [
                'migrateName',
                'safe'
            ]
        ]);
    }
}
