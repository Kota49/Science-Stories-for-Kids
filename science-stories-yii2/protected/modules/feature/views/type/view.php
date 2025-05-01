<?php
use app\modules\comment\widgets\CommentsWidget;
use app\components\useraction\UserAction;
/* @var $this yii\web\View */
/* @var $model app\modules\feature\models\Type */

/* $this->title = $model->label() .' : ' . $model->title; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Types'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card">

		<div class="type-view">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
    <?php

echo \app\components\TDetailView::widget([
        'id' => 'type-detail-view',
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            /*'description:html',*/
            //'icon',
           // 'order_id',
           /*  [
			'attribute' => 'type_id',
			'value' => $model->getType(),
			], */
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


<?php  echo $model->description;?>


		<?php

echo UserAction::widget([
    'model' => $model,
    'attribute' => 'state_id',
    'states' => $model->getStateOptions()
]);
?>

		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<div class="type-panel">

<?php
$this->context->startPanel();
$this->context->addPanel('Activities', 'feeds', 'Feed', $model /* ,null,true */);
$this->context->endPanel();
?>
				</div>
		</div>
	</div>


<?php echo CommentsWidget::widget(['model'=>$model]); ?>

</div>
