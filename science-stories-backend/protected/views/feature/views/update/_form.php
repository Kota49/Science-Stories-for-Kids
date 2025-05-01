<?php

use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\feature\models\Update */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
    <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">

    <?php
    $form = TActiveForm::begin([
        'id' => 'update-form',
        'options' => [
            'class' => 'row'
        ]
    ]); ?>
    <div class="col-sm-12">
        <?php echo $form->errorSummary($model);
        ?>
    </div>
    <div class="col-sm-6 offset-sm-3">

        <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
        <?php echo $form->field($model, 'description')->widget(app\components\TRichTextEditor::className(), ['options' => ['rows' => 6], 'preset' => 'basic']); //$form->field($model, 'description')->textarea(['rows' => 6]); ?>
        <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
        <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
        <div class="text-center">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'update-form-submit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    </div>
    <?php TActiveForm::end(); ?>

</div>