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
 
namespace app\modules\tugii\models;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Model;

class CrudForm extends Model {
	public $db_connection;
	public $models_path;
	public $models_search_path;
	public $controllers_path;
	public $override_controllers = true;
	public $exclude_controllers = 'User';
}
