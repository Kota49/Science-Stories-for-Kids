<?php

/* @var $this yii\web\View */
/* @var $model app\modules\storage\models\Type */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Storages'),
    'url' => [
        '/storage'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Types'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
    <div class="type-create">
        <?= \app\components\PageHeader::widget(); ?>
    </div>
    <div class="content-section clearfix card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>

</div>