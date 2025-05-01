<?php
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap5\ActiveForm */

// $this->title = 'Request password reset';
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Request passwordReset'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = \yii\helpers\Inflector::camel2words(Yii::$app->controller->action->id);
?>
<div class="box-header with-border">
    <?php

    if (Yii::$app->session->hasFlash('success')) {
        ?>
        <div class="alert alert-success">
            <?php echo Yii::$app->session->getFlash('success') ?>
        </div>
        <?php
    }
    ?>
    <?php

    if (Yii::$app->session->hasFlash('error')) {
        ?>
        <div class="alert alert-danger">
            <?php echo Yii::$app->session->getFlash('error') ?>
        </div>
        <?php
    }
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
                                <h3 class="fw-bold text-start">Reset Password</h3>
                                <p class="fw-regular">
                                    Please fill out your email. A link to reset password will be sent there.
                                </p>
                                <?php

                                $form = TActiveForm::begin([
                                    'id' => 'request-password-reset-form',
                                    'enableAjaxValidation' => false,
                                    'enableClientValidation' => true
                                ]);
                                ?>
                                <div class="form-group">
                                    <?= $form->field($model, 'email') ?>
                                </div>
                                <?= Html::submitButton('Send', ['name' => 'send-button', 'class' => 'btn-theme w-100']) ?>

                                <?php TActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>