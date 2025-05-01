<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\select2\Select2;
use app\modules\book\models\Promocode;

?>
<header class="card-header">
    <?php echo strtoupper(Yii::t('app', 'Add')); ?>
</header>
<div class="card-body">
    <?php

    $form = TActiveForm::begin([
        //
        'id' => 'offer-form'
    ]);
    ?>
    <div class="row">
		<div class="col-md-6">
       <?php  if (Yii::$app->controller->action->id == 'add') {?>
			<div class="row">
				<div class="col-md-9">
                    <?php echo $form->field($model, 'code')->textInput(['readOnly' => true]) ?>
                </div>
				<div class="col-lg-3 d-flex align-items-center mt-4">
					<div class="form-group">
						<a href="javascript:void(0);" class="btn btn-success mb-4 w-100"
							onclick="makeid(6)"><?= Yii::t('app', 'Generate Code') ?></a>
					</div>
				</div>



			</div>
			<?php }?>
            <?php

            echo $form->field($model, 'description')->widget(app\components\TRichTextEditor::className(), [
                'options' => [
                    'rows' => 6
                ],
                'preset' => 'basic'
            ]); // $form->field($model, 'description')->textarea(['rows' => 6]); */
            ?>


        </div>
		<div class="col-md-6">

            <?php echo $form->field($model, 'value')->textInput(['maxlength' => 64]) ?>
            <div class="row">
				<div class="col-md-6">
                    <?php

                    echo $form->field($model, 'start_date')
                        ->widget(yii\jui\DatePicker::class, [
                        // 'dateFormat' => 'php:Y-m-d',
                        'options' => [
                            'class' => 'form-control'
                        ],
                        'clientOptions' => [
                            'minDate' => \date('Y-m-d'),
                            'maxDate' => \date('Y-m-d', strtotime('+1 year')),
                            'changeMonth' => true,
                            'changeYear' => true
                        ]
                    ])
                        ->label(Yii::t('app', 'Start Date'))?>

                </div>
				<div class="col-md-6">

                    <?php

                    echo $form->field($model, 'end_date')
                        ->widget(yii\jui\DatePicker::class, [
                        // 'dateFormat' => 'php:Y-m-d',
                        'options' => [
                            'class' => 'form-control'
                        ],
                        'clientOptions' => [
                            'minDate' => \date('Y-m-d'),
                            'maxDate' => \date('Y-m-d', strtotime('+1 year')),
                            'changeMonth' => true,
                            'changeYear' => true
                        ]
                    ])
                        ->label(Yii::t('app', 'End Date'))?>
                </div>
			</div>


            <?=  $form->field($model, 'min_value')->textInput(['maxlength' => 10]) ?>
                   <?php  if (Yii::$app->controller->action->id == 'add') ?>
            
        </div>

		<div class="col-md-12 text-right">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'offer-form-submit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php TActiveForm::end(); ?>
    </div>
	<script>
        function makeid(length) {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() *
                    charactersLength));
            }
            $("#promocode-code").val(result);
        }
    </script>


	<script>

	$(".offer").change(function(){
	
	 var country_id = $("#offer-country").val();
	 var gender = $("#offer-gender").val();
	      
      $.ajax({
       url: "<?= Url::toRoute('/offer/get-patient?country=')?>"+country_id+ '&' + 'gender='+gender,
          async: false,
          
           success: function(result){
              
           	var $el = 	$('#offer-user_id');
           	var prevValue = $el.val();
           	$el.empty();
          if(result.status == 'OK'){
           
           	$.each(result.data, function(key, value) {
           	   $el.append($('<option></option>').attr('value', key).text(value));
           	   if (value === prevValue){
           	       $el.val(value);
           	   }
           	});
        		}
           }
      })
    
	});
</script>