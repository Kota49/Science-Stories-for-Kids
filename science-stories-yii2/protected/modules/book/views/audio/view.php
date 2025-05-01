<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use app\modules\book\models\Audio;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\modules\book\models\Audio */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Books'),
    'url' => [
        '/book'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Audios'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;

?>
<div class="wrapper">
	<div class="card">
		<div class="audio-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         			<?php

            echo \app\components\TDetailView::widget([
                'id' => 'audio-detail-view',
                'model' => $model,
                'attributes' => [
                    'id',
                                        /*'description:html',*/
                                        [
                        'attribute' => 'book_id',
                        'format' => 'raw',
                        'value' => $model->getRelatedDataLink('book_id')
                    ],
                    [
                        'label' => 'Audio',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $html = '';
                            if (! empty($model->getImageUrl())) {
                                $html = '<div>';
                                $html .= "<audio controls='0' width='400' height='250'><source src=" . $model->getImageUrl();
                                "></audio>" . ' ';
                                $html .= '</div>';
                            }
                            return $html;
                        }
                    ],
                    [
                        'attribute' => 'page_id',
                        'format' => 'raw',
                        'value' => $model->getRelatedDataLink('page_id')
                    ],
                                        /*[
                            			'attribute' => 'state_id',
                            			'format'=>'raw',
                            			'value' => $model->getStateBadge(),],*/
                                       
                    'created_on:datetime',
                    'updated_on:datetime',
                    [
                        'attribute' => 'created_by_id',
                        'format' => 'raw',
                        'value' => $model->getRelatedDataLink('created_by_id')
                    ]
                ]
            ])?>
                    <!-- <h4> Description(en)</h4> -->
            
         <?php  // $model->description;?>
         
         <br> <br>

			<!-- <h4>Description(he)</h4> -->
         
         
         <?php

        // $he_desc = ! empty($model->getTranslation('he', 'description', $model)) ? $model->getTranslation('he', 'description', $model) : '';

        // echo $he_desc;

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
			<div class="audio-panel">
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