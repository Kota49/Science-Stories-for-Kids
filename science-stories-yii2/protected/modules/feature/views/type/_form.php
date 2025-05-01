<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\feature\models\Type */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="card">
	<div class="card-header">

<?php echo strtoupper(Yii::$app->controller->action->id= 'add'); ?>
</div>

	<div class="card-body">
    <?php
    $form = TActiveForm::begin([
        // 'layout' => 'horizontal',
        'id' => 'type-form',
        'options' => [
            'class' => 'row'
        ]
    ]);

    ?>

<div class="col-sm-6 offset-sm-3">
 <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
<div class="text-center">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'type-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
		</div>

    <?php TActiveForm::end(); ?>

</div>
</div>