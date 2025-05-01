<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
use app\base\TranslatorWidget;
/* @var $this yii\web\View */
/* @var $model app\models\HelpSupport */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'help-support-form'
]);
?>
                  <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
                              <?php /*echo  $form->field($model, 'message')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'message')->textarea(['rows' => 6]); */ ?>
                              <?php /*echo $form->field($model, 'image_file')->fileInput() */ ?>
                              <?php //echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
                        <?php if(User::isAdmin()){?>      <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
      <?php }?>            <div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'help-support-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>