<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\book\models\Book */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Books'),
    'url' => [
        '/book'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Books'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="book-view">
         <?php

        echo \app\components\PageHeader::widget([
            'model' => $model
        ]);
        ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'book-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
            /*'title',*/
            [
                    'attribute' => 'category_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('category_id')
                ],
            /*'description:html',*/
            'image_file',
                'age',
                'price',
                [
                    'attribute' => 'price',
                    'value' => ! empty($model->price) ? $model->price : 0
                ],

                [
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ],
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            'created_on:datetime',
                [
                    'attribute' => 'created_by_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('created_by_id')
                ]
            ]
        ])?>
         <?php

        echo $model->description;
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
	<div class="card">
		<div class="card-body">
			<div class="book-panel">
            <?php
            $this->context->startPanel();
            $this->context->addPanel('Audios', 'audios', 'Audio', $model /* ,null,true */);
            $this->context->addPanel('Pages', 'pages', 'BookPage', $model /* ,null,true */);
            $this->context->addPanel('Feeds', 'feeds', 'Feed', $model /* ,null,true */);
            $this->context->endPanel();
            ?>
         </div>
		</div>
	</div>
        
  <?php

echo CommentsWidget::widget([
    'model' => $model
]);
?>
  </div>