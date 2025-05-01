<?php
use app\components\TActiveForm;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
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
		<div class="text-center">
			<h2>Are you sure you want to delete this item? All related data is
				deleted</h2>
		</div>
		<div class="type-view">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>



		</div>
	</div>
	<div class=" card ">
		<div class=" card-body ">
    <?php

echo \app\components\TDetailView::widget([
        'id' => 'type-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            'title',
            /*'description:html',*/
            'icon',
            'order_id',
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
$form = TActiveForm::begin([

    'id' => 'type-form'
]);

echo $form->errorSummary($model);
?>

	 <div class="form-group">
				<div
					class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
			
        <?= Html::submitButton('Confirm', ['id'=> 'type-form-submit','class' =>'btn btn-success']) ?>
    </div>
			</div>

    <?php TActiveForm::end(); ?>

		</div>
	</div>



	<div class=" card ">
		<div class=" card-body ">
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
