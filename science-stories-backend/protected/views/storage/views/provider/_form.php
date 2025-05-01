<?php
use app\components\TActiveForm;
use app\models\User;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\storage\models\Provider */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
   .nav.nav-tabs.customtab {
      border: 0;
      margin-bottom: 10px;
   }

   .nav.nav-tabs.customtab li {
      display: inline-block;
      width: auto;
      padding: 0 5px 0 0;
   }

   .nav.nav-tabs.customtab li a {
      display: block;
      min-width: 120px;
      background: #111;
      padding: 10px;
      border-radius: 5px;
      text-align: center;
      color: #fff;
   }

   .nav.nav-tabs.customtab li a.active {
      background: #28a745;
   }
</style>

<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

   $form = TActiveForm::begin([

      'id' => 'provider-form',
      // 'layout' => TActiveForm::LAYOUT_HORIZONTAL
   ]);
   echo $form->errorSummary($model);
   ?>
   <div class="row">

      <div class="col-md-6">
         <?php echo $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>
      </div>
      <div class="col-md-6">
         <div class="row">
            <div class="col-md-6">
               <?php echo $form->field($model, 'key')->textInput(['maxlength' => 64]) ?>
            </div>
            <div class="col-md-6">
               <?php echo $form->field($model, 'secret')->textInput(['maxlength' => 64]) ?>
            </div>
         </div>
      </div>
      <div class="col-md-6">
         <?php echo $form->field($model, 'endpoint')->textInput(['maxlength' => 512]) ?>
      </div>
      <?php /*echo $form->field($model, 'read_write')->textInput(['maxlength' => 32]) */?>
      <?php /*echo $form->field($model, 'location')->textInput(['maxlength' => 32]) */?>
      <?php if (User::isAdmin()) { ?>
         <?php //echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
      <?php } ?>
      <div class="col-md-6">
         <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions()) ?>
      </div>
   </div>
   <div class="col-md-12 text-end">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'provider-form-submit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
</div>
<?php TActiveForm::end(); ?>
</div>