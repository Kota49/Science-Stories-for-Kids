<?php

/* @var $this yii\web\View */
/* @var $model app\modules\smtp\models\Unsubscribe */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'SMTP'),
    'url' => [
        '/smtp'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Unsubscribes'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
    <div class="unsubscribe-create">
        <?= \app\components\PageHeader::widget(); ?>
    </div>
    <div class="content-section clearfix card">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>