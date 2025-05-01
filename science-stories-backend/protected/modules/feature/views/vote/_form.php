<?php
use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\feature\models\Vote */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="card-body">

    <?php
    $form = TActiveForm::begin([
        'id' => 'vote-form',
        'options' => [
            'class' => 'row'
        ]
    ]);

    ?>
	<div class="col-md-6 offset-md-3">
	<?php
echo $form->errorSummary($model);
?>
 <?php echo $form->field($model, 'feature_id')->dropDownList($model->getFeatureOptions(), ['prompt' => '']) ?>
	 	<?php echo $form->field($model, 'comment')->textInput(['maxlength' => 512]) ?>
	 	 <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
	<div class="form-group text-center">
	<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'vote-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
