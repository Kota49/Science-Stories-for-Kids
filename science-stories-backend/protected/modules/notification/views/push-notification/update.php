<?php


/* @var $this yii\web\View */
/* @var $model app\modules\notification\models\PushNotification */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notifications'), 'url' => ['/notification']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Push Notifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wrapper">
	<div class="card">
		<div class="push-notification-update">
			<?=  \app\components\PageHeader::widget(['model' => $model]); ?>
		</div>
	</div>
	<div class="card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>

