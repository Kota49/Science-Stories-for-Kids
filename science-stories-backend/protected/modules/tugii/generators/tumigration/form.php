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
/* @var $generator yii\gii\generators\form\Generator */
echo $form->field ( $generator, 'moduleName' );
echo $form->field ( $generator, 'migrateName' );
echo $form->field ( $generator, 'sql_up' )->textarea ();
echo $form->field ( $generator, 'enableDown' )->checkbox ();
echo $form->field ( $generator, 'sql_down' )->textarea ();
echo $form->field ( $generator, 'clearCache' )->checkbox ();
echo $form->field ( $generator, 'clearAssets' )->checkbox ();

?>
