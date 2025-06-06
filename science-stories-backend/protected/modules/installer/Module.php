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
namespace app\modules\installer;

use app\components\TModule;

/**
 * install module definition class
 */
class Module extends TModule
{

    public $exts = [];

    public $pkgs = [];

    public $sqlfile = null;

    public $layout = 'installer';

    /**
     *
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\installer\controllers';

    /**
     *
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (\Yii::$app instanceof \yii\web\Application) {
            $this->layoutPath = __DIR__ . '/views/layouts/';
        }

        if ($this->sqlfile == null) {
            $this->sqlfile = [
                dirname(__FILE__) . '/db/install.sql'
            ];
        }
        if (\Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\installer\command';
        }

        $this->sqlfile = is_array($this->sqlfile) ? $this->sqlfile : [
            $this->sqlfile
        ];
    }
}
