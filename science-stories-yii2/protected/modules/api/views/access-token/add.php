<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\api\models\AccessToken */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Apis'), 'url' => ['/api']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Access Tokens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="access-token-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


