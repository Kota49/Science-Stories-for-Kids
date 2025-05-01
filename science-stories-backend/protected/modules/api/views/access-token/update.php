<?php


/* @var $this yii\web\View */
/* @var $model app\modules\api\models\AccessToken */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Apis'), 'url' => ['/api']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Access Tokens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wrapper">
	<div class="card">
		<div class="access-token-update">
			<?=  \app\components\PageHeader::widget(['model' => $model]); ?>
		</div>
	</div>
	<div class="card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>

