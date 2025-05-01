<?php


/* @var $this yii\web\View */
/* @var $model app\modules\feature\models\Vote */

/* $this->title = Yii::t('app', 'Update {modelClass}: ', [
	'modelClass' => 'Vote',
]) . ' ' . $model->id; */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Votes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wrapper">
	<div class="vote-update">
		<?= \app\components\PageHeader::widget(['model' => $model]); ?>
	</div>
	<div class="content-section clearfix card">
		<?= $this->render('_form', ['model' => $model]) ?>
	</div>
</div>