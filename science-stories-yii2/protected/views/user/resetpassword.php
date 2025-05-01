<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use yii\helpers\Url;

// $this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->session->hasFlash('success')) { ?>
    <div class="alert alert-success">
        <?php echo Yii::$app->session->getFlash('success') ?>
    </div>
    
<?php } else { ?>

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
                                <h3 class="fw-bold text-start">Change Password</h3>
                                <p class="fw-regular">
                                    Please fill out the following fields to change password :
                                </p>
                                <?php

                                $form = TActiveForm::begin([
                                    'id' => 'changepassword-form',
                                    'options' => [
                                        'class' => 'form-horizontal'
                                    ],
                                    'fieldConfig' => [
                                        'template' => "{label}
                                    {input}
                                    {error}"
                                    ]
                                ]);
                                ?>
                                <?= $form->field($model, 'password', ['inputOptions' => ['placeholder' => '']])->passwordInput() ?>
                                <?= $form->field($model, 'confirm_password', ['inputOptions' => ['placeholder' => '']])->passwordInput(['class' => 'form-control']) ?>
                                <div class="text-center">
                                    <?= Html::submitButton('Change password', ['class' => 'btn-theme']) ?>
                                </div>
                                <?php TActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>