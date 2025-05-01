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
namespace app\modules\storage\assets;

use yii\web\AssetBundle;

class UploadAsset extends AssetBundle
{

    public $sourcePath = '@app/modules/storage/assets/src';

    public $baseUrl = '@web/storage';

    public $js = [
        'js/jquery.fine-uploader.min.js'
    ];

    public $css = [
        'css/fine-uploader-new.min.css'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset'
    ];
}