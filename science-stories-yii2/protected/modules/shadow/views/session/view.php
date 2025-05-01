<?php

use app\components\useraction\UserAction;

/* @var $this yii\web\View */
/* @var $model app\modules\shadow\models\Shadow */

/*$this->title =  $model->label() .' : ' . $model->id; */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shadows'), 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>

<div class="wrapper">
	<div class="card">
		<div class="shadow-view">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
    <?php echo \app\components\TDetailView::widget([
    	'id'	=> 'shadow-detail-view',
        'model' => $model,
        'attributes' => [
            'id',
            'to_id',
            [
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],
            'created_on:datetime',
            'created_by_id',
        ],
    ]) ?>


<?php  ?>


		<?php				echo UserAction::widget ( [
						'model' => $model,
						'attribute' => 'state_id',
						'states' => $model->getStateOptions ()
				] );
				?>

		</div>
</div>

</div>
