<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
use app\components\TActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<div class="wrapper">
    
	<div class="content-section card">
        <div class="card-body">
			<?php
            $form = TActiveForm::begin([
                'id' => 'import-form',
                'options' => [
                    'class' => 'import-form row',
                    'enctype' => 'multipart/form-data'
                ]
            ]);
            ?>
                <div class="col-md-6 offset-md-3">
                    <?php echo $form->field($import, 'file')->fileInput()?>
                        <?=Html::submitButton ( 'Import', [ 'class' => 'btn btn-success','name' =>'Import button'] )?>
                </div>
            <?php TActiveForm::end ()?>
		</div>

</div>

</div>

