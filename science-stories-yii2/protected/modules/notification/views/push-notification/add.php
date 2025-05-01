<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\notification\models\PushNotification */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notifications'), 'url' => ['/notification']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Push Notifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="push-notification-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


