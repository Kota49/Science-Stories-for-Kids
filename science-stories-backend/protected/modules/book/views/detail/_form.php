<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
use app\modules\book\models\Detail;
use app\base\TranslatorWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\book\models\Detail */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php

echo strtoupper(Yii::$app->controller->action->id);
?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'detail-form'
]);
?>

<div class="row">
		<div class="col-md-6">
<?= TranslatorWidget::widget(['type' => TranslatorWidget::TYPE_FORM,'model' => $model,'attribute' => 'title','label' =>Yii::t('app', 'Title').' in hebrew','form' => $form])?>

</div>
		<div class="col-md-6">
 <?php

echo $form->field($model, 'title')->textInput([
    'maxlength' => 255
])?></div>


		<div class="col-md-6">
 <?php

echo $form->field($model, 'category_id')->dropDownList($model->getCategoryOptions(), [
    'prompt' => '',
    'value' => Yii::$app->request->get('id'),
    'disabled'=> !empty(Yii::$app->request->get('id')) ? true : false
])?></div>

		<div class="col-md-6">
 <?php

echo $form->field($model, 'author_name')->textInput([
    'maxlength' => 255
])?></div>

		<div class="col-md-6">
                              <?= TranslatorWidget::widget(['type' => TranslatorWidget::TYPE_FORM,'model' => $model,'attribute' => 'description','label' =>Yii::t('app', 'Description').' in hebrew', 'inputType' => [ 'inputField' => TranslatorWidget::TYPE_EDITOR],'form' => $form])?>                  
</div>


		<div class="col-md-6">
 <?php

echo $form->field($model, 'description')->widget(app\components\TRichTextEditor::className(), [
    'options' => [
        'rows' => 6
    ],
    'preset' => 'basic'
]);

?></div>

		<div class="col-md-6">
                            <?= TranslatorWidget::widget(['type' => TranslatorWidget::TYPE_FORM,'model' => $model,'attribute' => 'author_name','label' =>Yii::t('app', 'Author Name').' in hebrew','form' => $form])?>
</div>



		<div class="col-md-6">
 <?php

echo $form->field($model, 'age')->dropDownList($model->getAgeOptions(), [
    'prompt' => ''
])?></div>

		<div class="col-md-6">
 <?php

echo $form->field($model, 'price_id')->dropDownList($model->getPriceOptions(), [
    'prompt' => ''
])?></div>

		<div class="col-md-6">
  <?php

echo $form->field($model, 'price')->textInput([
    'maxlength' => 16
])?></div>

		<div class="col-md-6">
  
                              <?php

                            echo $form->field($model, 'image_file')->fileInput([
                                'onchange' => 'ValidateSingleInput(this)'
                            ]);
                            ?></div>

		<div class="col-md-6">
  
                             <?php

                            if (User::isAdmin()) {
                                ?>      <?php

                                $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), [

                                    'prompt' => ''
                                ])?>                      
                        
      <?php
                            }
                            ?></div>


	</div>


	<div class="col-md-12 text-right">
      <?=Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'detail-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
   </div>
   <?php

TActiveForm::end();
?>
</div>
<script>



$( document ).ready(function() {
  var value = $("#detail-price_id").val();

if(value == '<?=Detail::PAID?>'){

$(".field-detail-price").show();

}else{

$(".field-detail-price").hide();

}
});


$( "#detail-price_id" ).on( "change", function() {

var value = $("#detail-price_id").val();

if(value == '<?=Detail::PAID?>'){

$(".field-detail-price").show();

}else{

$(".field-detail-price").hide();

}

} );

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
                    $('#user-form').yiiActiveForm('validateAttribute', 'book-image_file')
                    return false;
                }
            }
        }
        return true;
    }
</script>
