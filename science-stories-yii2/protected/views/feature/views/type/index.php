<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\feature\models\search\Type */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* $this->title = Yii::t('app', 'Index'); */
$this->params['breadcrumbs'][] = [
	'label' => Yii::t('app', 'Types'),
	'url' => [
		'index'
	]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');
?>

<div class="wrapper">
	<div class="type-index">
		<?= \app\components\PageHeader::widget(); ?>
	</div>
	<?php echo $this->render('_form', ['model' => $model]) ?>
	<div class="card">
		<header class="card-header head-border">
			<?php echo strtoupper(Yii::$app->controller->action->id = 'index'); ?>
		</header>
		<div class="card-body">
			<div class="content-section clearfix">
				<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
			</div>
		</div>
	</div>
</div>