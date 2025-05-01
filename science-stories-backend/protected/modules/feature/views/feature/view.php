<?php
use app\modules\comment\widgets\CommentsWidget;
use app\modules\feature\models\Vote;
use yii\helpers\Html;
use app\models\User;
use app\components\useraction\UserAction;
/* @var $this yii\web\View */
/* @var $model app\modules\feature\models\Feature */

/* $this->title = $model->label() .' : ' . $model->title; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Features'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card">

		<div class="feature-view">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
        </div>
	</div>

	<div class="card">
		<div class=" card-body ">
    <?php
    
    echo \app\components\TDetailView::widget([
        'id' => 'feature-detail-view',
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'icon',
            'order_id',
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
    'states' => $model->getStateOptions(),
   // 'visible' => User::isManager() 
]);

echo $model->getVoteButton();
?>

</div>
	</div>

<?php if (!User::isUser()) : ?>
	<div class=" card ">
		<div class=" card-body ">
			<div class="feature-panel">

<?php
    $this->context->startPanel();
    $this->context->addPanel('Votes', 'votes', 'Vote', $model);
    $this->context->endPanel();
    ?>
				</div>
		</div>
	</div>
<?php endif;?>


<?php echo CommentsWidget::widget(['model'=>$model]); ?>

</div>