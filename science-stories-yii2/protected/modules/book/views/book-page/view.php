<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\modules\book\models\BookPage */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Books'),
    'url' => [
        '/book'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Book Pages'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="book-page-view">
         <?php

        echo \app\components\PageHeader::widget([
            'model' => $model
        ]);
        ?>
        
      </div>
	</div>
	<div class="content-section clearfix">
		<div class="widget light-widget">
			<div class="user-view">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-xl-2 col-lg-3 col-md-3">
								<div class="pro_img_wrapp mt-5 text-center">
									<div class="pro_img_wrapp">
              
                                <?php
                                if (! empty($model->page_image)) {
                                    echo Html::img($model->getPageImageUrl(), [
                                        'alt' => $model,
                                        'width' => '150'
                                    ])?>
                                <?php
                                } else {
                                    ?>
                                    <?php
                                    echo Html::img($this->theme->getUrl('img/default.jpg'), [
                                        'alt' => $model
                                    ]);
                                }

                                ?>

                                    
                </div>
								</div>
							</div>
							<div class="col-xl-10 col-lg-9 col-md-9">

         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'book-page-detail-view',
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
                    'value' => ! empty($model->category) ? $model->category->title : ''
                ],
            /*'description:html',*/
            [
                    'attribute' => 'book_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('book_id')
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
			</div>

		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<div class="book-page-panel">
            <?php
            $this->context->startPanel();
            $this->context->addPanel('Audios', 'audios', 'Audio', $model /* ,null,true */);
            $this->context->addPanel('Feeds', 'feeds', 'Feed', $model /* ,null,true */);
            $this->context->endPanel();
            ?>
         </div>
		</div>
	</div>

</div>