<?php
use app\components\TActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\scheduler\models\SettingsForm $model */
/** @var TActiveForm $form */
?>
<div class="wrapper">
	<div class="card">
		<header class="card-header"> 
			  <?php echo ucfirst(Yii::$app->controller->module->id); ?> 
			</header>
		<div class="card-body">	
   <?php

$form = TActiveForm::begin([
    'id' => 'setting-form',
    'layout' => TActiveForm::LAYOUT_HORIZONTAL,
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);
?>

<div class="row justify-content-center">
				<div class="col-xl-4 col-lg-6 col-md-6">
				<div class="form-check form-switch">
<?php
echo $form->field($model, 'enableEmails')
    ->checkbox([
    'id' => $model->getFormAttributeId('enableEmails'),
    'class' => 'form-check-input',
    'role' => 'switch',
    'onchange' => 'this.form.submit()'
])
    ->label(true, [
    'class' => 'form-check-label'
]);
?>
</div>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-xl-4 col-lg-6 col-md-6">
				<div class="form-check form-switch">
<?php
echo $form->field($model, 'keepAfterSend')
    ->checkbox([
    'id' => $model->getFormAttributeId('keepAfterSend'),
    'class' => 'form-check-input',
    'role' => 'switch',
    'onchange' => 'this.form.submit()'
])
    ->label(true, [
    'class' => 'form-check-label'
]);
?>
</div>
	</div>

			</div>
			<div class="row justify-content-center">
				<div class="col-xl-4 col-lg-6 col-md-6">
<?php
echo $form->field($model, 'clearSentAfterMonths')->textInput();
?>
</div>
			</div>

			<div class="text-center">
    	<?= Html::submitButton('Submit', ['id'=> 'setting-form-submit','class' => 'btn btn-primary']) ?>
  
</div>
		</div>
 <?php TActiveForm::end(); ?>
		
		<!-- settings -->

	</div>
</div>