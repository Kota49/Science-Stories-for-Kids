<?php
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap5\ActiveForm */

// $this->title = 'Signup';
?>
<?php
$fieldOptions1 = [
    'inputTemplate' => "{input}<span  class='fa fa-fw fa-eye-slash field-icon toggle-password' toggle='#user-password' id='password-reveal1'></span>"
];
$fieldOptions2 = [
    'inputTemplate' => "{input}<span  class='fa fa-fw fa-eye-slash field-icon toggle-password' toggle='#user-confirm_password' id='password-reveal2'></span>"
];
?>

<div class="login-wrap">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="auth-card">
                    <div class="card-box border-0 mb-0">
                        <a href="<?= Url::home(); ?>" class="logo-title">
                            <h2 class="text-center mb-3 fw-bold">
                                <?= Yii::$app->name ?>
                            </h2>
                        </a>
                        <div class="card-body p-0">
                            <h3 class="fw-bold text-start">Sign Up </h3>

                            <?php
                            $form = TActiveForm::begin([
                                'id' => 'form-signup',
                                'options' => [
                                    'class' => 'form-signin'
                                ]
                            ]);
                            ?>
                            <span id="reauth-email" class="reauth-email"></span>

                            <div class="form-group">
                            <label for="Full Name" class="form-label">Full Name</label>
                                <?= $form->field($model, 'full_name', ['template' => '{input}{error}'])->textInput(['maxlength' => true, 'placeholder' => 'Full Name'])->label(false) ?>
                            </div>

                            <div class="form-group">
                            <label for="Email" class="form-label">Email</label>
                                <?= $form->field($model, 'email', ['template' => '{input}{error}'])->textInput(['maxlength' => true, 'placeholder' => 'Email'])->label(false) ?>
                            </div>
                            <div class="form-group">
                                <?= $form->field($model, 'password', $fieldOptions1)->passwordInput(['maxlength' => true, 'placeholder' => 'Password'])->label(true) ?>
                            </div>
                            <div class="form-group">
                                <?= $form->field($model, 'confirm_password', $fieldOptions2)->passwordInput(['maxlength' => true, 'placeholder' => 'Confirm Password'])->label(true) ?>
                            </div>
                            <div class="my-3">
                                <?= $form->field($model, 'tos')->checkbox(['required' => true])->label('I accept the 
										 <a target="_blank" class="sign-link"
										href="' . Url::toRoute(["/site/terms"]) . '">Terms and
										Conditions</a>'); ?>
                            </div>

                            <?= Html::submitButton('Sign Up', ['class' => 'btn-theme w-100', 'name' => 'signup-button']) ?>

                            <?php TActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(".toggle-password").click(function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>