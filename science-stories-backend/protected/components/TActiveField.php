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
namespace app\components;

use yii\bootstrap5\ActiveField;

/**
 *
 *  @see yii\bootstrap4\ActiveField;
 *
 */
class TActiveField extends ActiveField
{

    public function __construct($config = [])
    {
        $config['horizontalCssClasses'] = [
            'label' => [
                'col-sm-4',
                'col-form-label',
                'text-start', 
                'text-sm-end'
            ],
            'wrapper' => 'col-sm-4',
            'error' => '',
            'hint' => '',
            'field' => 'form-group row'
        ];

        parent::__construct($config);
    }
}
