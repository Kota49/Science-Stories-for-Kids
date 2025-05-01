<?php

namespace app\modules\feature\assets;

use yii\web\AssetBundle;



class RemoteFeatureAssets extends AssetBundle {
	
	/**
	 * @inheritdoc
	 */
	public $sourcePath = '@app/modules/feature/assets';
	
	/**
	 * @inheritdoc
	 */
	public $css = [ 
			'css/remote-feature-style.css' 
	];
}
