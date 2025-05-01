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
namespace app\components;

use app\components\editors\suneditor\SunEditor;
use yii\helpers\HtmlPurifier;

/**
 * Text editor
 */
class TRichTextEditor extends SunEditor
{

    public $preset;

    public function init(): void
    {
        parent::init();
    }

    public static function process($content)
    {
        if ($content != null) {

            $out = str_replace('<table>', '<table class="table table-bordered">', $content);
            $css = '
            <style>
                li ol {
                    counter-reset: item;
                }
                li ol li {
                    display: block;
                    position: relative;
                }
                li ol li:before {
                content: counter(item, lower-alpha)".";
                    counter-increment: item;
                    position: absolute;
                    margin-right: 100%;
                    right: 10px;
                }
            </style>
        ';
            $content = $css . HtmlPurifier::process($out);
        }
        return $content;
    }

    public static function print($content)
    {
        $css = '
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #dee2e6;
                    padding: 8px;
                    text-align: left;
                }
 
            </style>
        ';

        $out = self::process($content);
        return $css . $out;
    }
}
