<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
use app\components\TActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Comment */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="comment-form">
    <?php
    $form = TActiveForm::begin([
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'options' => [
            'data-pjax' => false
        ]
    ]);
    ?>
    <section class="panel profile-info">
    <?php echo $form->field($model, 'message')->textarea(['rows'=>10,'cols'=>5]);?>
    <?php echo $form->field($model, 'file')->fileInput()->label("Upload File");?>
   <div class="panel-footer float-end">
	<?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-success']) ?>
	</div>
    <?php TActiveForm::end(); ?>
	</section>
</div>
