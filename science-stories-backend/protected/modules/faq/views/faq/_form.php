<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
use app\base\TranslatorWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\faq\models\Faq */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php

echo strtoupper(Yii::$app->controller->action->id);
?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'faq-form'
]);
?>
<div class="row">
		<div class="col-lg-6">
   <?= TranslatorWidget::widget(['type' => TranslatorWidget::TYPE_FORM,'model' => $model,'attribute' => 'question','label' =>Yii::t('app', 'Question').' in hebrew', 'inputType' => [ 'inputField' => TranslatorWidget::TYPE_EDITOR],'form' => $form])?>                  
</div>

		<div class="col-lg-6">
    <?php

    echo $form->field($model, 'question')->widget(app\components\TRichTextEditor::className(), [
        'options' => [
            'rows' => 6
        ],
        'preset' => 'basic'
    ]);
    ?>
    </div>
		<div class="col-lg-6">
   <?= TranslatorWidget::widget(['type' => TranslatorWidget::TYPE_FORM,'model' => $model,'attribute' => 'answer','label' =>Yii::t('app', 'Answer').' in hebrew', 'inputType' => [ 'inputField' => TranslatorWidget::TYPE_EDITOR],'form' => $form])?>                  
</div>
		<div class="col-lg-6">
    <?php

    echo $form->field($model, 'answer')->widget(app\components\TRichTextEditor::className(), [
        'options' => [
            'rows' => 6
        ],
        'preset' => 'basic'
    ]);
    ?>
    </div>
		<div class="col-md-12 text-right">
      <?=Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'faq-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
   </div>
	</div>
                 
                              
                 
   <?php

TActiveForm::end();
?>
</div>