<?php
use app\components\TActiveForm;
use app\models\User;
use yii\helpers\Html;
use Spatie\SchemaOrg\AlignmentObject;
/* @var $this yii\web\View */
/* @var $model app\modules\smtp\models\Account */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
<?php
$this->registerCss("
    #buttonCopyText {
	top: 1px !important;
	right: 27% !important;
}
");
?>
   <?php

$form = TActiveForm::begin([
    'layout' => TActiveForm::LAYOUT_HORIZONTAL,
    'id' => 'account-form',
    'options' => [
        'class' => 'row'
    ]
]);

?>
         <div class="col-md-6">
                  <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
                  <?php echo $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
                  <div class="position-relative">
                 <?php

                echo $form->field($model, 'password')->passwordInput([
                    'autocomplete' => 'off',

                    'maxlength' => 256
                ])?>	
                <?= Html::button('<i class="fas fa-copy"></i>', ['id' => 'buttonCopyText','class' => 'btn btn-success position-absolute ms-3','title' => 'Copy password'])?>
                </div>
                 <?php if($model->isNewRecord){?>
		   <div class="row">
			<div class="col-md-6 text-md-end text-center">
                        <?= Html::checkbox('reveal-password', false, ['id' => 'reveal-password']) ?> <?= Html::label('Show password', 'reveal-password') ?>
        	</div>
		</div>
		      <?php }?>
		<br>
                  <?php echo $form->field($model, 'server')->textInput(['maxlength' => 255]) ?>
                
                     </div>
	<div class="col-md-6">
			  <?php echo $form->field($model, 'port')->textInput() ?>
             <?php echo $form->field($model, 'encryption_type')->dropDownList($model->getEncryptionOptions(), ['prompt' => 'Select'])  ?><?php echo $form->field($model, 'limit_per_email')->textInput() ?>
             <?php if(User::isAdmin()){?>    
                   <?php //echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
      	     <?php }?>                        
             <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions()) ?>
        </div>
	<div class="col-md-12 text-end">
             <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'account-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
     </div>
   <?php TActiveForm::end(); ?>
</div>
<?php
$this->registerJs("jQuery('#reveal-password').change(function(){jQuery('#account-password').attr('type',this.checked?'text':'password');});
$('#buttonCopyText').click(function () {
    var copyText = document.getElementById('account-password');
    navigator.clipboard.writeText(copyText.value);
})
");
?>


