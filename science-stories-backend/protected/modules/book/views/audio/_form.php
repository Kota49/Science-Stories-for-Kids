<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
use yii\helpers\ArrayHelper;
use app\modules\book\models\BookPage;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use app\base\TranslatorWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\book\models\Audio */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'audio-form'
]);
?>


<div class="row">
		<div class="col-md-6">
                              <?php
                              
                              
                              if(!empty($book_id)){
                                  $model->book_id = $book_id;
                              }

echo $form->field($model, 'book_id')->dropDownList($model->getBookOptions(), [
                                'prompt' => '',
                                'disabled' => ! empty(Yii::$app->request->get('id')) ? true : false
                            ])?>

</div>
		<div class="col-md-6">
<?php

if(!empty($page_id)){
    $model->page_id = $page_id;
}

echo $form->field($model, 'page_id')
    ->widget(DepDrop::classname(), [
    'data' => ArrayHelper::Map(BookPage::find()->where([
        'book_id' => $model->book_id
    ])
        ->all(), 'id', 'title'),
    'options' => [
        'placeholder' => 'Select',
        'id' => 'audio-page_id'
    ],
    'pluginOptions' => [
        'depends' => [
            'audio-book_id'
        ],
        'placeholder' => 'Choose Page',
        'url' => Url::to([
            '/book/audio/book-page'
        ])
    ]
])
    ->label();
?> </div>
		<div class="col-md-6">
      							  <?php echo $form->field($model, 'image_file')->fileInput(['onchange' => 'ValidateSingleInput(this)', 'required' => true])?>
    
</div>

	</div>



                                

                                          <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */?>
                  <div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'audio-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>
<script>
    var _validFileExtensions = [".MP3",".wma",".aac",".wav",".ogg", ".mp3"];

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
                    $('#user-form').yiiActiveForm('validateAttribute', 'image_file')
                    return false;
                }
            }
        }
        return true;
    }
</script>
