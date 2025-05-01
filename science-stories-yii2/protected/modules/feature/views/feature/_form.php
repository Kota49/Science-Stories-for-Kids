<?php
use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\feature\models\Feature */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php
$form = TActiveForm::begin([
    'id' => 'feature-form',
    'enableClientValidation' => false,
    'options' => [
        'class' => 'row'
    ]
]);

?>
   <div class="col-md-6">
      <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
      <?php echo $form->field($model, 'icon')->textInput(['maxlength' => 255]) ?>
      <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions()) ?>
      <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions()) ?>
      <?php echo $form->field($model, 'order_id')->textInput(['maxlength' => 255]) ?>
   </div>
	<div class="col-md-6">
      <?= $form->field($model, 'summary')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 2 ],'preset' => 'full' ] ); ?> 
   </div>
	<div class="col-md-12">
      <?= $form->field($model, 'description')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'full' ] ); ?> 
   </div>
	<div class="form-group col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'feature-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>