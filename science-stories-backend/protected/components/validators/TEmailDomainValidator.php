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
 * Email Domain validator
 */
class TEmailDomainValidator extends Validator
{

    public $allowedDomains = [];

    public $notAllowedDomains = [];

    public function validateAttribute($model, $attribute)
    {
        $email = trim($model->$attribute);
        try {
            list ($username, $domain) = explode('@', $email);
        } catch (\Exception $e) {
            $model->addError($attribute, 'Invalid Email');
            return false;
        }
        if (! empty($this->allowedDomains) && ! in_array($domain, $this->allowedDomains)) {
            $model->addError($attribute, $model->getAttributeLabel($attribute) . ' has domain name that is not allowed. Allowed domains are :' . implode(', ', $this->allowedDomains));
        }
        if (! empty($this->notAllowedDomains) && in_array($domain, $this->notAllowedDomains)) {
            $model->addError($attribute, $model->getAttributeLabel($attribute) . ' has domain name that is not allowed');
        }
        if (empty($this->getMXServer($domain))) {
            $model->addError($attribute, 'Invalid Email Domain');
            return false;
        }
    }

    public function validateValue($value)
    {
        $email = trim($value);
        try {
            list ($username, $domain) = explode('@', $email);
        } catch (\Exception $e) {
            $model->addError($attribute, 'Invalid Email');
            return false;
        }

        if (! empty($this->allowedDomains) && ! in_array($domain, $this->allowedDomains)) {
            return false;
        }
        if (! empty($this->notAllowedDomains) && in_array($domain, $this->notAllowedDomains)) {
            return false;
        }
        if (empty($this->getMXServer($domain))) {
            return false;
        }
        return true;
    }

    public function getMXServer($domain)
    {
        if (empty($domain)) {
            return null;
        }
        $mxhosts = [];
        if (dns_get_mx($domain, $mxhosts)) {

            return $mxhosts[0];
        }
        return null;
    }
}
