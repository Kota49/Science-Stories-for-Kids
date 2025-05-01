<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
use app\base\TranslatorWidget;
/* @var $this yii\web\View */
/* @var $model app\models\Banner */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'banner-form'
]);
?>
<?= TranslatorWidget::widget(['type' => TranslatorWidget::TYPE_FORM,'model' => $model,'attribute' => 'title','label' =>Yii::t('app', 'Title').' in hebrew','form' => $form])?>
                  <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
                 <?= TranslatorWidget::widget(['type' => TranslatorWidget::TYPE_FORM,'model' => $model,'attribute' => 'description','label' =>Yii::t('app', 'Description').' in hebrew', 'inputType' => [ 'inputField' => TranslatorWidget::TYPE_EDITOR],'form' => $form])?>                  
                  
                              <?php echo  $form->field($model, 'description')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); $form->field($model, 'description')->textarea(['rows' => 6]); ?>
                              <?php echo $form->field($model, 'image_file')->fileInput(['onchange' => 'ValidateSingleInput(this)']) ?>
                         <?php if(User::isAdmin()){?><?php //echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
                         <?php }?>  
                      <div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'banner-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>
<script>
    var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".png"];
    function ValidateSingleInput(oInput) {
        if (oInput.type == "file") {
            var sFileName = oInput.value;
            if (sFileName.length > 0) {
                var blnValid = false;
                for (var j = 0; j < _validFileExtensions.length; j++) {
                    var sCurExtension = _validFileExtensions[j];
                    if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                        blnValid = true;
                        break;
                    }
                }

                if (!blnValid) {
                    alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                    oInput.value = "";
                    $('#user-form').yiiActiveForm('validateAttribute', 'banner-image_file')
                    return false;
                }
            }
        }
        return true;
    }
</script>