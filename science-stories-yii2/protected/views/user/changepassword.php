<?php
use app\components\TActiveForm;
use yii\helpers\Html;

// $this->title = 'Change Password';

$this->params['breadcrumbs'][] = Yii::t('app', 'Change Password');
?>
<div class="wrapper">
    <div class="card clearfix">
        <header class="card-header">
            Please fill out the following fields to change password
        </header>
        <div class="card-body">
            <div class="site-changepassword">
                <?php
                $form = TActiveForm::begin([
                    'id' => 'changepassword-form',
                    'options' => [
                        'class' => 'row'
                    ]
                ]);
                ?>
                <div class="col-lg-6 offset-lg-3">
                    <div class="position-relative">
                        <?=$form->field($model, 'password', ['inputOptions' => ['placeholder' => '','value' => '']])->label()->passwordInput()?>
            		<div class="eye-icon">
							<i toggle="#user-password"
								class="fa toggle-password fa-eye-slash"></i>

						</div>
					</div>
                    <div class="position-relative">
                        <?=$form->field($model, 'confirm_password', ['inputOptions' => ['placeholder' => '']])->label()->passwordInput()?>
                        <div class="eye-icon">
							<i toggle="#user-confirm_password"
								class="fa toggle-password fa-eye-slash"></i>

						</div>
                    </div>
                    <div class="form-group text-center">
                        <?=Html::submitButton('Change password', ['class' => 'btn btn-primary ','name' => 'changepassword-button'])?>
                    </div>
                </div>
                <?php

TActiveForm::end();
                ?>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs('

$(".toggle-password-new").click(function() {
   $(this).toggleClass("fa-eye fa-eye-slash ");
      var input = $($(this).attr("toggle"));
   if (input.attr("type") == "password") {
     input.attr("type", "text");
   } else {
     input.attr("type", "password");
   }

});

$(".toggle-password").click(function() {
   $(this).toggleClass("fa-eye fa-eye-slash ");
      var input = $($(this).attr("toggle"));
   if (input.attr("type") == "password") {
     input.attr("type", "text");
   } else {
     input.attr("type", "password");
   }
});
');

?>