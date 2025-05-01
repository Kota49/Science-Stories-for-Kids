<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace app\assets;
use yii\web\AssetBundle;

/**
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author	 : Shiv Charan Panjeta < shiv@ozvid.com >
 */
class AppAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [ ];
	public $jsOptions = [ 
			'position' => \yii\web\View::POS_HEAD 
	];
	/*
	 * public $js = [
	 * 'http://ads.ozvid.com/banner.js'
	 * ];
	 */
	public $depends = [ 
			'yii\web\YiiAsset',
			'yii\bootstrap5\BootstrapAsset',
			'yii\bootstrap5\BootstrapPluginAsset' 
	];
}
