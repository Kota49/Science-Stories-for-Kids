<?php

/* @var $this yii\web\View */
/* @var $model app\modules\storage\models\Provider */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Storages'),
    'url' => [
        '/storage'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Providers'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="provider-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model,'typeModel' => $typeModel ] )?>
	</div>
</div>


