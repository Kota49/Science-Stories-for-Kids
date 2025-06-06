<?php


/* @var $this yii\web\View */
/* @var $model app\modules\scheduler\models\Type */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Scheduler'), 'url' => ['/scheduler']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wrapper">
	
		<div class="type-update">
			<?=  \app\components\PageHeader::widget(['model' => $model]); ?>
		</div>
	<div class="card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>

