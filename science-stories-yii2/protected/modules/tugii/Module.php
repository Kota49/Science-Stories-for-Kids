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
namespace app\modules\tugii;

use yii\helpers\Html;


class Module extends \yii\gii\Module
{

    public $controllerNamespace = 'app\modules\tugii\controllers';

    public function init()
    {
        parent::init();
        
        // custom initialization code goes here
    }

    protected function coreGenerators()
    {
        $local = [
            'tumodel' => [
                'class' => 'app\modules\tugii\generators\tumodel\Generator'
            ],
            'tucrud' => [
                'class' => 'app\modules\tugii\generators\tucrud\Generator'
            ],
            'tumigration' => [
                'class' => 'app\modules\tugii\generators\tumigration\Generator'
            ],
            'tuapi' => [
                'class' => 'app\modules\tugii\generators\tuapi\Generator'
            ],
            'tutest-case' => [
                'class' => 'app\modules\tugii\generators\tutestcase\Generator'
            ],
            'tumodule' => [
                'class' => 'app\modules\tugii\generators\tumodule\Generator'
            ]
        
        ];
        
        return array_merge($local, parent::coreGenerators());
    }
    
    public static function logo()
    {
        return Html::img(base64_decode('aHR0cDovL3lpaS5ndXJ1L3lpaTI='), ['class' => 'img-fluid','alt' => 'Image']);
    }
}
