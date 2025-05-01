<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\storage\models\File */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'file-form',
   //  'layout' => TActiveForm::LAYOUT_HORIZONTAL
]);
echo $form->errorSummary($model);
?>
<div class="col-md-6 offset-md-3">

                  <?php echo $form->field($model, 'name')->textInput(['maxlength' => 1024]) ?>
                              <?php echo $form->field($model, 'size')->textInput() ?>
                              <?php echo $form->field($model, 'key')->textInput(['maxlength' => 255]) ?>
                              <?php echo $form->field($model, 'model_type')->textInput(['maxlength' => 128]) ?>
                              <?php echo $form->field($model, 'model_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
                              <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
              
	<div class="text-end">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'file-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   </div>
   <?php TActiveForm::end(); ?>
</div>