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

use app\components\TActiveQuery;
use yii\data\ActiveDataProvider;
use yii\helpers\Console;

/**
 * Setup Commands for first time
 */
trait TLogHelper
{

    public static function error($strings)
    {
        if (php_sapi_name() == "cli") {
            $out = time() . ': ' . get_called_class() . ' : ' . $strings . PHP_EOL;

            echo Console::ansiFormat($out, [
                Console::FG_RED
            ]);
        } else {
            \Yii::error(get_called_class() . ' : ' . $strings);
        }
    }

    public static function warning($strings)
    {
        if (php_sapi_name() == "cli") {
            $out = time() . ': ' . get_called_class() . ' : ' . $strings . PHP_EOL;
            echo Console::ansiFormat($out, [
                Console::FG_YELLOW
            ]);
        } else {
            \Yii::debug(get_called_class() . ' : ' . $strings);
        }
    }

    public static function debug($strings)
    {
        if (php_sapi_name() == "cli") {
            echo time() . ': ' . get_called_class() . ' : ' . $strings . PHP_EOL;
        } else {
            \Yii::debug(get_called_class() . ' : ' . $strings);
        }
    }

    public static function log($strings)
    {
        self::debug($strings);
    }

    public static function logQuery($query)
    {
        $sql = '';
        if ($query instanceof ActiveDataProvider) {
            $query = $query->query;
        }

        if ($query instanceof TActiveQuery) {
            $sql = $query->createCommand()->rawSql;
        }
        self::log($sql);
    }
}
