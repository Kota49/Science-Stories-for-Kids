<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
use app\base\TranslatorWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\book\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'category-form'
]);
?>

<div class="row">
		<div class="col-md-6">
 <?= TranslatorWidget::widget(['type' => TranslatorWidget::TYPE_FORM,'model' => $model,'attribute' => 'title','label' =>Yii::t('app', 'Title').' in hebrew','form' => $form])?>

</div>
		<div class="col-md-6">
  <?php echo $form->field($model, 'title')->textInput(['maxlength' => 256]) ?>
</div>
		<div class="col-md-6">
       <?php if(User::isAdmin()){?>      <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
      <?php }?>    
</div>

	</div>


	<div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'category-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>





