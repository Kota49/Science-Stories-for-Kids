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
 * Email validator
 *
 */
class TEmailValidator extends Validator
{

    public $pattern = "/[a-z0-9_.\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i";

    public function validateAttribute($model, $attribute)
    {
        if (! preg_match($this->pattern, $model->$attribute) || (preg_match('/[a-z0-9_.\-\+]+@indeed.com/i', $model->$attribute)) || (preg_match('/[a-z0-9_.\-\+]+@invalid.com/i', $model->$attribute)) || (preg_match('/[a-z0-9_.\-\+]+@example.com/i', $model->$attribute))) {

            $model->addError($attribute, $model->getAttributeLabel($attribute) . ' is invalid.');
        }
    }

    public function validateValue($value)
    {
        if (! preg_match($this->pattern, $value) || (preg_match('/[a-z0-9_.\-\+]+@invalid.com/i', $value)) || (preg_match('/[a-z0-9_.\-\+]+@example.com/i', $value))) {
            return false;
        }
        return true;
    }
}
