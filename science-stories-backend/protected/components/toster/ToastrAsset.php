<?php

/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\components\toster;

use yii\web\AssetBundle;

/**
 * Description of ToastrAsset
 *
 * @author Odai Alali <odai.alali@gmail.com>
 */
class ToastrAsset extends AssetBundle {
	public $sourcePath = '@webroot/protected/components/toster/assets';
	public $css = [ 
			'css/jquery.toast.css' 
	];
	public $js = [ 
			'js/jquery.toast.js' 
	];
	public $depends = [ 
			'yii\web\JqueryAsset' 
	];
}
