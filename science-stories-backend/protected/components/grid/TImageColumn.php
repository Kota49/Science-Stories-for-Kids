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
namespace app\components\grid;

use yii\grid\DataColumn;
use yii\helpers\Html;

/**
 * pass value as image url
 * 
 *          [
 *               'class' => 'app\components\grid\TImageColumn',
 *               'width' => '100px',
 *              'attribute' => 'profile_file',
 *               'format' => 'raw',
 *               'value' => function ($data) {
 *                   return $data->getImageUrl(250);
 *               }
 *           ],
 * 
 * 
 * 
 * 
 */
class TImageColumn extends DataColumn
{

    public $class;

    public $width;

    /**
     * pass value as image url
     *
     * @see \yii\grid\DataColumn::getDataCellValue() 
     * 
     */
    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (! $this->class) {
            $this->class = 'img-fluid';
        }
        if (! $this->width) {
            $this->width = '100px';
        }
        
        return $this->value = Html::img($value, [
            'class' => $this->class,
            'alt' => $model,
            'width' => $this->width
        ]);
    }
}
