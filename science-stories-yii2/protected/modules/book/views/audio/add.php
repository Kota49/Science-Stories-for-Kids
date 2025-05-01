<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\book\models\Audio */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Books'),
    'url' => [
        '/book'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Audios'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="audio-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?=$this->render('_form', ['model' => $model,'page_id' => $page_id,'book_id' => $book_id])?>
	</div>
</div>


