<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\storage\models\File */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Storages'),
    'url' => [
        '/storage'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Files'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
    <div class="file-view">
        <?php echo \app\components\PageHeader::widget(['model' => $model]); ?>
    </div>
    <div class="card">
        <div class="card-body">
            <?php

            echo \app\components\TDetailView::widget([
                'id' => 'file-detail-view',
                'model' => $model,
                'attributes' => [
                    'id',
                    'name',
                    'size',
                    'key',
                    'model_type',
                    'model_id',
                    [
                        'attribute' => 'account_id',
                        'format' => 'raw',
                        'value' => $model->getRelatedDataLink('account_id')
                    ],
                    [
                        'attribute' => 'model_id',
                        'format' => 'raw',
                        'value' => $model->getModel()->linkify()
                    ],
                    [
                        'attribute' => 'type_id',
                        'value' => $model->getType()
                    ],
                    'created_on:datetime',
                    [
                        'attribute' => 'created_by_id',
                        'format' => 'raw',
                        'value' => $model->getRelatedDataLink('created_by_id')
                    ]
                ]
            ]) ?>
            <?php ?>
        </div>
    </div>
    <?php

    echo UserAction::widget([
        'model' => $model,
        'attribute' => 'state_id',
        'states' => $model->getStateOptions()
    ]);
    ?>

    <?php echo CommentsWidget::widget(['model' => $model]); ?>
</div>