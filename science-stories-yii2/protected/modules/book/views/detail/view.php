<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\modules\book\models\Detail */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Books'),
    'url' => [
        '/book'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Details'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="detail-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'detail-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                [
                    'label' => Yii::t('app', 'Title') . ' in hebrew',
                    'format' => 'raw',
                    'value' => ! empty($model->getTranslation('he', 'title', $model)) ? $model->getTranslation('he', 'title', $model) : Yii::t('app', 'N/A')
                ],
                [
                    'attribute' => 'category_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('category_id')
                ],
                'author_name',
                [
                    'label' => Yii::t('app', 'Author Name') . ' in hebrew',
                    'format' => 'raw',
                    'value' => ! empty($model->getTranslation('he', 'author_name', $model)) ? $model->getTranslation('he', 'author_name', $model) : Yii::t('app', 'N/A')
                ],
                [
                    'attribute' => 'image_file',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::img($model->getImageUrl(), [
                            'width' => '70px'
                        ]);
                    }
                ],
                [
                    'attribute' => 'age',
                    'value' => $model->getAge()
                ],
                [
                    'attribute' => 'price_id',
                    'value' => $model->getPrice()
                ],

                'price',

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
                <h4>Description(en)</h4>
        
          <?php  echo $model->description;?>
         
         <br> <br>

			<h4>Description(he)</h4>
         
         
         <?php

        $he_desc = ! empty($model->getTranslation('he', 'description', $model)) ? $model->getTranslation('he', 'description', $model) : '';

        echo $he_desc;

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
			<div class="detail-panel">
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

</div>