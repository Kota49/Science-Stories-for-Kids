<?php
/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

$id = $generator->moduleID ;

?>
{
	"name" : "jiwebtech/<?=$id?>",
	"description" : "<?=$id?>",
	"type" : "library",
	"authors" : [{
			"name" : "Shiv Charan Panjeta",
			"email" : "shiv@jiwebtech.com"
		}
	],
	"require" : {
		"php" : ">=8.0.0",
		"yiisoft/yii2" : "*"
	}
}