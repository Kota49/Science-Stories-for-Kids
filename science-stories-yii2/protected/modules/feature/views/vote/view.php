<?php
use app\modules\comment\widgets\CommentsWidget;
use app\components\useraction\UserAction;
/* @var $this yii\web\View */
/* @var $model app\modules\feature\models\Vote */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Votes'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
    <div class="card">
     <div class="vote-view">
            <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
    <?php
    
echo \app\components\TDetailView::widget([
        'id' => 'vote-detail-view',
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'feature_id',
                'format' => 'raw',
                'value' => $model->getRelatedDataLink('feature_id')
            ],
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'value' => $model->getStateBadge()
            ],
            'created_on:datetime',
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => $model->getRelatedDataLink('created_by_id')
            ]
        ]
    ])?>


<?php  ?>


        <?php

echo UserAction::widget([
    'model' => $model,
    'attribute' => 'state_id',
    'states' => $model->getStateOptions()
]);
?>

        </div>
    </div>



<?php echo CommentsWidget::widget(['model'=>$model]); ?>

</div>
