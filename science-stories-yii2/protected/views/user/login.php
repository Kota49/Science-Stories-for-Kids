<?php
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

// $this->title = 'Sign In';
?>
<style>


.logo-title {
  color: #FE9723 !important;
}
</style>
<?php

$fieldOptions1 = [
    'options' => [
        'class' => 'form-group has-feedback'
    ],

    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => [
        'class' => 'form-group has-feedback'
    ],
    'inputTemplate' => "{input}<span class='fa fa-lock form-control-feedback'></span>"
];
$fieldOptions3 = [
    'inputTemplate' => "{input}<span  class='fa fa-fw fa-eye field-icon toggle-password' toggle='#loginform-password' id='password-reveal'></span>"
];
?>

<?php

if (Yii::$app->session->hasFlash('success')) :
    ?>
    <div class="alert alert-success">
        <?php

    echo Yii::$app->session->getFlash('success')?>
    </div>

<?php endif;

?>
<div class="login-wrap">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="auth-card">
                    <div class="card-box border-0 mb-0">
                        <span class="logo-title">
                            <h2 class="text-center mb-3 fw-bold">
                                <?=Yii::$app->name?>
                            </h2>
                        </span>
                        <div class="card-body p-0">
                            <h3 class="fw-bold text-start">Log In </h3>
                            <?php

                            $form = TActiveForm::begin([
                                'id' => 'login-form',
                                'enableAjaxValidation' => false,
                                'enableClientValidation' => false,
                                'options' => [
                                    'class' => 'form-signin'
                                ]
                            ]);
                            ?>

                            <span id="reauth-email" class="reauth-email"></span>

                            <div class="mb-3">
                                <?=$form->field($model, 'username', $fieldOptions1)->label(true)->textInput(['placeholder' => $model->getAttributeLabel('email')])->label('Email')?>
                            </div>
                            <div class="form-group">
                                <?=$form->field($model, 'password', $fieldOptions3)->label(true)->passwordInput(['placeholder' => $model->getAttributeLabel('password')])?>
                            </div>


                            <div class="row my-4">
                                <div class="col-md-6">
                                    <div id="remember" class="checkbox">
                                        <?php

                                        echo $form->field($model, 'rememberMe')->checkbox();
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6 text-start text-lg-end">
                                    <a class="forgot-password float-none float-md-right"
                                        href="<?php

                                        echo Url::toRoute([
                                            'user/recover'
                                        ])?>">Forgot
                                        Password? </a>
                                </div>
                            </div>

                            <?=Html::submitButton('Login', ['class' => 'btn-theme w-100 mt-4 mt-md-0','id' => 'login','name' => 'login-button'])?>

                            <?php

                            TActiveForm::end()?>
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
            console.log('teete');
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>