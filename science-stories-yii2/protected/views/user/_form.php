<?php
use app\components\TActiveForm;
use app\models\User;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
    <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="card-body">

    <?php
    $form = TActiveForm::begin([
        'id' => 'user-form',
        'options' => [
            'class' => 'row'
        ]
    ]);
    ?>

    <div class="col-lg-6">
    			
    		 <?php echo $form->field($model, 'full_name')->textInput(['maxlength' => 256]) ?>
    
    		 <?php echo $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
    		 <?php if(Yii::$app->controller->action->id == 'add'){?>
    		 <?php echo $form->field($model, 'password')->passwordInput(['maxlength' => 128]) ?>
    		 <?php }?>
    </div>
	<div class="col-lg-6">
        <?php echo $form->field($model, 'profile_file')->fileInput(['onchange' => 'ValidateSingleInput(this)']) ?>
		 
		 <?php if (User::isManager() && $model->role_id != User::ROLE_ADMIN){?>
	 		
	 		<?php echo $form->field($model, 'role_id')->dropDownList($model->getRoleOptions(), ['prompt' => '']) ?>
	 				    
		<?php }?>
	 			</div>
	<div class="form-group col-lg-12 text-end">
	
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'user-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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
                    $('#user-form').yiiActiveForm('validateAttribute', 'user-profile_file')
                    return false;
                }
            }
        }
        return true;
    }
</script>