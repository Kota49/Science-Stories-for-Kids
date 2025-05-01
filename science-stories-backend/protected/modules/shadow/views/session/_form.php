<?php
   use yii\helpers\Html;
   use app\components\TActiveForm;
   
   /* @var $this yii\web\View */
   /* @var $model app\modules\shadow\models\Shadow */
   /* @var $form yii\widgets\ActiveForm */
   ?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php 
      $form = TActiveForm::begin([
      		'id'	=> 'shadow-form',
      		'options'=>[
      			'class'=>'row'
      		]
      						]);
      ?>
      <div class="col-md-6 offset-md-3">
   <?php echo $form->field($model, 'to_id')->dropDownList($model->getToOptions(), ['prompt' => '']) ?>
	<div class="form-group">
     <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'shadow-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
      </div>
</div>
   <?php TActiveForm::end(); ?>
</div>