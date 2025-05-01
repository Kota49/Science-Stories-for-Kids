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
 * Password validator
 */
class TPasswordValidator extends Validator
{

    public $length = 8;

    public function validateAttribute($model, $attribute)
    {
        $pattern = '/^(?=.*[a-zA-Z0-9]).{5,}$/';
        // $pattern = '/^(?=.*\d(?=.*\d))(?=.*[a-zA-Z](?=.*[a-zA-Z])).{5,}$/';
        if (strlen($model->$attribute) < $this->length)
            $model->addError($attribute, "Your password must be $this->length characters long.");
        if (! preg_match($pattern, $model->$attribute))
            $model->addError($attribute, 'Your password is not strong enough!');
    }
}
