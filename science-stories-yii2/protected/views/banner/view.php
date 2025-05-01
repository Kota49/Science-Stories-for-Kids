<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
use app\models\Banner;
/* @var $this yii\web\View */
/* @var $model app\models\Banner */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Banners'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="banner-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">

         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'banner-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                [
                    'label' => Yii::t('app', 'Title') . ' in hebrew',
                    'format' => 'raw',
                    'value' => ! empty($model->getTranslation('he', 'title', $model)) ? $model->getTranslation('he', 'title', $model) : Yii::t('app', 'N/A')
                ],
                'description:html',
                [
                    'attribute' => 'image_file',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::img($model->getImageUrl(), [
                            'width' => '70px'
                        ]);
                    }
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
			<div class="banner-panel">
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