<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\feature\models\Update */

/* $this->title = $model->label() .' : ' . $model->title; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Updates'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">

    <div class="update-view">
        <?php echo \app\components\PageHeader::widget(['model' => $model]); ?>
    </div>

    <div class="card">
        <div class="card-body">
            <?php

            echo \app\components\TDetailView::widget([
                'id' => 'update-detail-view',
                'model' => $model,
                'attributes' => [
                    'id',
                    'title',
                    /*'description:html',*/
                    [
                        'attribute' => 'type_id',
                        'value' => $model->getType()
                    ],
                    [
                        'attribute' => 'state_id',
                        'format' => 'raw',
                        'value' => $model->getStateBadge()
                    ],
                    'created_on:datetime',
                    'updated_on:datetime',
                    [
                        'attribute' => 'created_by_id',
                        'format' => 'raw',
                        'value' => $model->getRelatedDataLink('created_by_id')
                    ]
                ]
            ]) ?>


            <?php echo $model->description; ?>



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