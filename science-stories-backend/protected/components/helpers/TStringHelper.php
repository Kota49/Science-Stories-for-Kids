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
namespace app\components\helpers;

use yii\helpers\StringHelper;

/**
 * @inheritdoc
 *
 * 
 */
class TStringHelper extends StringHelper
{

    public const STOP_WORDS = array(
        'i',
        'a',
        'about',
        'an',
        'and',
        'are',
        'as',
        'at',
        'be',
        'by',
        'com',
        'de',
        'en',
        'for',
        'from',
        'how',
        'in',
        'is',
        'it',
        'la',
        'of',
        'on',
        'or',
        'that',
        'the',
        'this',
        'to',
        'was',
        'what',
        'when',
        'where',
        'who',
        'will',
        'with',
        'und',
        'the',
        'www'
    );

    public static function extractCommonWords($string)
    {
        $string = preg_replace('/\s\s+/i', '', $string); // replace whitespace
        $string = trim($string); // trim the string
        $string = preg_replace('/[^a-zA-Z0-9 -]/', '', $string); // only take alphanumerical characters, but keep the spaces and dashes too�
        $string = strtolower($string); // make it lowercase

        preg_match_all('/\b.*?\b/i', $string, $matchWords);
        $matchWords = $matchWords[0];

        foreach ($matchWords as $key => $item) {
            if ($item == '' || in_array(strtolower($item), self::STOP_WORDS) || strlen($item) <= 3) {
                unset($matchWords[$key]);
            }
        }
        $wordCountArr = array();
        if (is_array($matchWords)) {
            foreach ($matchWords as $key => $val) {
                $val = strtolower($val);
                if (isset($wordCountArr[$val])) {
                    $wordCountArr[$val] ++;
                } else {
                    $wordCountArr[$val] = 1;
                }
            }
        }
        arsort($wordCountArr);
        $wordCountArr = array_slice($wordCountArr, 0, 10);
        return $wordCountArr;
    }
    public static function removeSpaces($name)
    {
        return preg_replace("/\s+/i", '', $name);
    }
}
