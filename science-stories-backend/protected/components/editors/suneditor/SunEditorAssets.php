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
namespace app\components\editors\suneditor;

use yii\web\AssetBundle;

class SunEditorAssets extends AssetBundle
{

    public $sourcePath = '@app/components/editors/suneditor/dist';

    public $css = [
        'css/suneditor.min.css',
        '//cdn.jsdelivr.net/npm/codemirror@5.49.0/lib/codemirror.min.css',
        '//cdn.jsdelivr.net/npm/katex@0.11.1/dist/katex.min.css'
    ];

    public $js = [
        'suneditor.min.js',
        '//cdn.jsdelivr.net/npm/codemirror@5.49.0/lib/codemirror.min.js',
        '//cdn.jsdelivr.net/npm/codemirror@5.49.0/mode/htmlmixed/htmlmixed.js',
        '//cdn.jsdelivr.net/npm/codemirror@5.49.0/mode/xml/xml.js',
        '//cdn.jsdelivr.net/npm/codemirror@5.49.0/mode/css/css.js',
        '//cdn.jsdelivr.net/npm/katex@0.11.1/dist/katex.min.js',
        'suneditor.config.js'
    ];

    public $depends = [];
}