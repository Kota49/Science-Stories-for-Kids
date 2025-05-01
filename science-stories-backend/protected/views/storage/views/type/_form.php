<?php
use app\components\TActiveForm;
use app\models\User;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\storage\models\Type */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

   $form = TActiveForm::begin([

      'id' => 'type-form',
      // 'layout' => TActiveForm::LAYOUT_HORIZONTAL
   ]);
   // echo $form->errorSummary($model);
   ?>
   <div class="col-md-12">

      <?php echo $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>
      <?php echo $form->field($model, 'description')->widget(app\components\TRichTextEditor::className(), ['options' => ['rows' => 6], 'preset' => 'basic']); //$form->field($model, 'description')->textarea(['rows' => 6]); */ ?>
      <?php if (User::isAdmin()) { ?>
         <?php //echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
      <?php } ?>
      <?php //echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
   </div>
   <div class="col-md-12 text-end">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'type-form-submit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>