<?php
/**
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author	 : Shiv Charan Panjeta < shiv@ozvid.com >
 */
namespace app\base;

use yii\validators\Validator;
use app\components\validators\TPasswordValidator;

class TBasePasswordValidator extends TPasswordValidator
{
	public $length = 8;
	public function validateAttribute($model, $attribute) {
		//$pattern = '/^(?=.*[a-zA-Z0-9]).{5,}$/';
		// $pattern = '/^(?=.*\d(?=.*\d))(?=.*[a-zA-Z](?=.*[a-zA-Z])).{5,}$/';
		$pattern = '/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/';
		
		if (strlen ( $model->$attribute ) < $this->length)
			$model->addError ( $attribute, "Your password must be $this->length characters long." );
		if (! preg_match ( $pattern, $model->$attribute ))
			$model->addError ( $attribute, 'Your password is not strong enough!' );
	}
}