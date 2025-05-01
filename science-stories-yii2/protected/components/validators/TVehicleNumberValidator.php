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
namespace app\components\validators;

use yii\validators\Validator;

class TVehicleNumberValidator extends Validator
{

    public $countries = [
        'India',
        'UAE'
    ];

    public $patterns = [
        'India' => '/^[A-Z]{2}\d{2}[A-Z]{1,2}\d{4}$/', // Regular expression for Indian car number (e.g., MH12AB1234)
        'USA' => '/^[A-Z0-9]{6,7}$/', // Regular expression for US car number (e.g., 123ABC or ABC1234)
        'UAE' => '/^[0-9]{2}[A-Z]-[A-Z0-9]{5}$/' // Regular expression for UAE car number (e.g., 23A-12345 or ABC-12345)
    ];

    public $message = 'The vehicle number is not in a valid Indian format.';

    public function getPattern($country)
    {
        return $this->patterns[$country];
    }

    public function validateAttribute($model, $attribute)
    {
        foreach ($this->countries as $country) {
            $pattern = $this->getPattern($country);
            if (preg_match($pattern, strtoupper($model->$attribute))) {
                return;
            }
        }
        $model->addError($attribute, $model->getAttributeLabel($attribute) . ' is invalid.' . $this->message);
    }

    protected function validateValue($value)
    {
        foreach ($this->countries as $country) {
            $pattern = $this->getPattern($country);
            if (preg_match($pattern, strtoupper($value))) {
                return null;
            }
        }

        return [
            $this->message,
            []
        ];
    }
}