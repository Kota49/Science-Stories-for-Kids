<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use app\base\TranslatorWidget;
use app\modules\book\models\Book;
use app\modules\book\models\Detail;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\modules\book\models\BookPage */
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

    'id' => 'book-page-form'
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
                ])?>
                    </div>
		<div class="col-md-6">
       <?php
    echo $form->field($model, 'category_id')->dropDownList($model->getCategoryOptions(), [
        'prompt' => '',
        'disabled'=> !empty(Yii::$app->request->get('id')) ? true : false
    ])?>  
</div>

		<div class="col-md-6">
        <?php

        echo $form->field($model, 'book_id')->widget(DepDrop::classname(), [
            'data' => $model->getBookList(),
            'options' => [
                'id' => 'bookpage-book_id',
               
            ],
            'pluginOptions' => [
                'depends' => [
                    'bookpage-category_id'
                ],
                'initialize' => $model->isNewRecord ? false : true,
                'placeholder' => 'Select Book',
                'url' => Url::to([
                    '/book/book-page/book-name'
                ]),
                'prompt'=> "Select",
            ], 
           
            'disabled'=> !empty(Yii::$app->request->get('id')) ? true : false
            
        ]);

        ?>   
</div>


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
    $form->field($model, 'description')->textarea([
        'rows' => 6
    ]);
    ?>
</div>

		<div class="col-md-6">
       <?php

    echo $form->field($model, 'page_image')->fileInput([
        'onchange' => 'ValidatePageInput(this)'
    ])?>
</div>

		<div class="col-md-6">
       <?php

    if (User::isAdmin()) {
        ?>      <?php

        echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), [
            'prompt' => ''
        ])?>
                                  <?php
    }
    ?>
</div>


	</div>



</div>
<div class="col-md-12 text-right">
      <?=Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'book-page-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
   </div>
<?php

TActiveForm::end();
?>
</div>
<script>
    var _validFileExtensions = [".pdf"];

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
    
    var _validFileExtension = [".jpg", ".jpeg", ".bmp", ".png"];
    function ValidatePageInput(oInput) {
        if (oInput.type == "file") {
            var sFileName = oInput.value;
            if (sFileName.length > 0) {
                var blnValid = false;
                for (var j = 0; j < _validFileExtension.length; j++) {
                    var sCurExtension = _validFileExtension[j];
                    if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                        blnValid = true;
                        break;
                    }
                }

                if (!blnValid) {
                    alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtension.join(", "));
                    oInput.value = "";
                    $('#user-form').yiiActiveForm('validateAttribute', 'book-page_image')
                    return false;
                }
            }
        }
        return true;
    }
</script>