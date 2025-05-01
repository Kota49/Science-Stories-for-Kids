<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
use app\components\TActiveForm;
use yii\helpers\Html;
?>



<div class="col-md-12">
	
    <?php $form = TActiveForm::begin(['id' => 'user-actions-form',]); ?>
    <div class="row">

		<div class="col-md-2" style="margin-top: 10px;">
			<?= $title?>
			</div>
		<div class="col-md-9">
		<?= Html::dropDownList('workflow',null,array_combine($allowed,$allowed),['class' => 'form-control']);?>
	</div>
		<div class="col-md-1">
		<?= Html::submitButton(Yii::t('app', 'Go') , ['id'=> 'project-form-submit','class' => 'btn btn-success']);?>
	</div>
	<?php

/*
 * foreach ( $allowed as $id => $act ) {
 *
 * if ($id != $model->{$attribute}) {
 *
 * echo '';
 * echo Html::submitButton ( $act, array (
 * 'name' => 'workflow',
 * 'value' => $act,
 * 'class' => 'btn ' . $this->context->getButtonColor ( $act )
 * ) );
 * echo '';
 * }
 * }
 */

?>

	</div>
<?php TActiveForm::end(); ?>

<?php if(\Yii::$app->session->hasFlash('user-action')): ?>
<div class="col-md-6">
		<div class="alert alert-info ">
			<div class="flash-success alert-link">
	<?php echo \Yii::$app->session->getFlash('user-action'); ?>
		</div>
		</div>
	</div>
<?php endif;?>
</div>
