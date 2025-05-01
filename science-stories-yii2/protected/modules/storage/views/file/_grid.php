<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\storage\models\search\File $searchModel
 */

?>

<?php Pjax::begin(['id'=>'file-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'file-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'name',
            'size:shortSize',
            // 'key',
            // 'model_type',
            // 'model_id',
            [
                'attribute' => 'model_id',
                'format' => 'raw',
                'value' => function ($data) {
                    $model = $data->getModel();
                    if ($model) {
                        return $model->linkify();
                    }
                }
            ],
            [
                'attribute' => 'account_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('account_id');
                }
            ],
            'created_on:datetime',
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('created_by_id');
                }
            ],

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                            'class' => 'btn btn-success'
                        ];
                        return Html::a('<span class="fa fa-eye"></span>', $model->getUrl('detail'), $options);
                    }
                ]
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>