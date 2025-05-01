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

/**
 * Phone number validator
 */
class TPhoneValidator extends Validator
{

    public $length = 6;

    public $maxlength = 16;

    public function validateAttribute($model, $attribute)
    {
        $pattern = '/^([+]{0,1}?[0-9.-]+)$/';
        if (strlen($model->$attribute) < $this->length)
            $model->addError($attribute, \Yii::t('app', "Phone number must be {$this->length} characters long"));
        else if (strlen($model->$attribute) > $this->maxlength)
            $model->addError($attribute, \Yii::t('app', "Phone number cannot be {$this->maxlength} characters long"));
        if (! preg_match($pattern, $model->$attribute))
            $model->addError($attribute, \Yii::t('app', 'Phone number does not seem to be a valid phone number'));
    }

    public function validateValue($value)
    {
        $pattern = '/^([+]{0,1}?[0-9.-]+)$/';
        if (strlen($value) < $this->length)
            return false;
        else if (strlen($value) > $this->maxlength)
            return false;
        if (! preg_match($pattern, $value))
            return false;

        return true;
    }
}
