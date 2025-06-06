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

use yii\helpers\ArrayHelper;

/**
 * Array helper
 *
 *        
 */
class TArrayHelper extends ArrayHelper
{
    /**
     * @see \yii\grid\DataColumn::getDataCellValue()
     *
     */
    public static function first(&$array)
    {
        if (! is_array($array))
            return $array;
        if (! count($array))
            return null;
        reset($array);
        return $array[key($array)];
    }

    /**
     * @inheritdoc
     *
     */
    public static function last(&$array)
    {
        if (! is_array($array))
            return $array;
        if (! count($array))
            return null;
        end($array);
        return $array[key($array)];
    }
}
