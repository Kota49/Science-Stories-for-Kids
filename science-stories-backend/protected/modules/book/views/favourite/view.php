<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\book\models\Favourite */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Books'),
    'url' => [
        '/book'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Favourites'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="favourite-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

echo \app\components\TDetailView::widget([
            'id' => 'favourite-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                'model_id',
                'model_type',
            /*'title',*/
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            [
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ],
                'created_on:datetime',
                'updated_on:datetime',
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
	<div class="card">
		<div class="card-body">
			<div class="favourite-panel">
            <?php
            $this->context->startPanel();
            $this->context->addPanel('Feeds', 'feeds', 'Feed', $model /* ,null,true */);
            $this->context->endPanel();
            ?>
         </div>
		</div>
	</div>
        
  <?php echo CommentsWidget::widget(['model'=>$model]); ?>
  </div>