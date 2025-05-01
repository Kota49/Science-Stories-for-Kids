<?php

/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\components\grid\assets;

use yii\web\AssetBundle;

/**
 * Asset for Grid icons.
 *
 */
class TGridViewAsset extends AssetBundle
{

    /**
     * 
     * @var string
     * @desc use for view path of asset
     */
    public $sourcePath = '@app/components/grid/assets/src';


    /**
     * 
     * @var array
     * @desc use for css path of asset
     */
    public $css = [
        'css/grid.css'
    ];
}
