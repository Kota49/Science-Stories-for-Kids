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
namespace app\components\editors\sceditor;

use yii\web\AssetBundle;

class ScEditorAssets extends AssetBundle
{

    public $sourcePath = '@app/components/editors/sceditor/dist';

    public $css = [
         'themes/default.min.css',
       # 'themes/modern.min.css'
        # 'themes/office.min.css'
    ];

    public $js = [
        'sceditor.min.js',
        'icons/monocons.js',
        'formats/xhtml.js',
        'jquery.sceditor.min.js',
        'jquery.sceditor.xhtml.min.js'
    ];

    public $depends = [];
}