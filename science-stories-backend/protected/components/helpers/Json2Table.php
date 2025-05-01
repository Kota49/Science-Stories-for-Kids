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
namespace app\components\helpers;

/**
 * Class convert Json to html table.
 * It help view json data directly.
 */
class Json2Table
{

    /**
     * return html content to json
     */
    public static function formatContent($content, $class = 'table table-bordered')
    {
        $html = "";
        if (empty($content)) {
            return $html;
        }

        if ($content != null) {
            $content = strip_tags($content);
            if ($content != null) {
                $arr = json_decode($content, true);

                if ($arr && is_array($arr)) {
                    $html .= self::arrayToHtmlTableRecursive($arr, $class);
                }
            }
        }
        return $html;
    }

    /**
     * return array content to html formt
     */
    public static function arrayToHtmlTableRecursive($arr, $class = 'table table-bordered')
    {
        $str = "<table class='$class'><tbody>";
        foreach ($arr as $key => $val) {
            $str .= "<tr>";
            $str .= "<td>$key</td>";
            $str .= "<td>";
            if (is_array($val)) {
                if (! empty($val)) {
                    $str .= self::arrayToHtmlTableRecursive($val, $class);
                }
            } else {
                if ($val) {
                    $val = nl2br($val);
                    $str .= "<strong>$val</strong>";
                }
            }
            $str .= "</td></tr>";
        }
        $str .= "</tbody></table>";

        return $str;
    }
}
