<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\storage\models\Type */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Storages'),
    'url' => [
        '/storage'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Types'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
    <div class="type-view">
        <?php echo \app\components\PageHeader::widget(['model' => $model]); ?>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?php

                    echo \app\components\TDetailView::widget([
                        'id' => 'type-detail-view',
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'title',
                            /*'description:html',*/
                            /*[
                            'attribute' => 'state_id',
                            'format'=>'raw',
                            'value' => $model->getStateBadge(),],*/
                            /*  [
                                     'attribute' => 'type_id',
                                     'value' => $model->getType()
                                 ], */
                            'created_on:datetime',
                            [
                                'attribute' => 'created_by_id',
                                'format' => 'raw',
                                'value' => $model->getRelatedDataLink('created_by_id')
                            ]
                        ]
                    ]) ?>

                </div>
                <div class="col-md-6">
                    <?php echo $model->description; ?>

                </div>

            </div>


        </div>
    </div>
    <?php

    echo UserAction::widget([
        'model' => $model,
        'attribute' => 'state_id',
        'states' => $model->getStateOptions()
    ]);
    ?>
    <div class="card">
        <div class="card-body">
            <div class="type-panel">
                <?php
                $this->context->startPanel();
                $this->context->addPanel('Activity', 'feeds', 'Feed', $model /* ,null,true */);
                $this->context->endPanel();
                ?>
            </div>
        </div>
    </div>

    <?php echo CommentsWidget::widget(['model' => $model]); ?>
</div>