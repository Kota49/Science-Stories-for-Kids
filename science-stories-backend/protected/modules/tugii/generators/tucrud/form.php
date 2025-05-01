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
 
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\crud\Generator */
echo $form->field ( $generator, 'modelClass' );
// echo $form->field($generator, 'searchModelClass');

echo $form->field ( $generator, 'controllerClass' );
echo $form->field ( $generator, 'baseControllerClass' );
echo $form->field ( $generator, 'moduleID' );
echo $form->field ( $generator, 'indexWidgetType' )->dropDownList ( [ 
		'grid' => 'GridView',
		'list' => 'ListView' 
] );
echo $form->field ( $generator, 'enableUserMode' )->checkbox ();
echo $form->field ( $generator, 'enableAdminMode' )->checkbox ();
echo $form->field ( $generator, 'enableI18N' )->checkbox ();
echo $form->field ( $generator, 'enablePjax' )->checkbox ();
echo $form->field ( $generator, 'enableComment' )->checkbox ();
echo $form->field ( $generator, 'enablePanel' )->checkbox ();
echo $form->field ( $generator, 'messageCategory' );

?>
