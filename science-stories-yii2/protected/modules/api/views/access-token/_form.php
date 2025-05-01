<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\api\models\AccessToken */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php    $form = TActiveForm::begin([
    
   'id' => 'access-token-form',
   ]);
   echo $form->errorSummary($model);    
   ?>
                  <?php echo $form->field($model, 'access_token')->textInput(['maxlength' => 256]) ?>
                              <?php echo $form->field($model, 'device_token')->textInput(['maxlength' => 256]) ?>
                              <?php /*echo $form->field($model, 'device_name')->textInput(['maxlength' => 256]) */ ?>
                              <?php echo $form->field($model, 'device_type')->textInput(['maxlength' => 256]) ?>
                              <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */ ?>
                  <div
      class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'access-token-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>