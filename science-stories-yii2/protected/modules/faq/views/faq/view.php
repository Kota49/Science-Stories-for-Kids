<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\modules\faq\models\Faq */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Faqs'),
    'url' => [
        'index'
    ]
];

$this->params['breadcrumbs'][] = html_entity_decode($model->question);
?>
<div class="wrapper">
	<div class="card">
		<div class="faq-view">
         <?php

        echo \app\components\PageHeader::widget([
            'model' => $model,
            'title' => html_entity_decode($model->question)
        ]);
        ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'faq-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                'question:html',
                [
                    'label' => Yii::t('app', 'Question') . ' in hebrew',
                    'format' => 'raw',
                    'value' => ! empty($model->getTranslation('he', 'question', $model)) ? $model->getTranslation('he', 'question', $model) : Yii::t('app', 'N/A')
                ],
                'answer:html',
                [
                    'label' => Yii::t('app', 'Answer') . ' in hebrew',
                    'format' => 'raw',
                    'value' => ! empty($model->getTranslation('he', 'answer', $model)) ? $model->getTranslation('he', 'answer', $model) : Yii::t('app', 'N/A')
                ],
                [
                    'attribute' => 'created_on',
                    'format' => 'raw',
                    'value' => $model->getConvertTime('created_on')
                ],
                [
                    'attribute' => 'created_by_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('created_by_id')
                ]
            ]
        ])?>
         <?php

        ?>
         <?php

        echo UserAction::widget([
            'model' => $model,
            'attribute' => 'state_id',
            'states' => $model->getStateOptions()
        ]);
        ?>
      </div>
	</div>
</div>