<?php
use app\components\grid\TGridView;
use yii\helpers\Html;
use yii\helpers\Url;

use app\models\User;

use yii\grid\GridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\book\models\search\Payment $searchModel
 */

?>

<?php Pjax::begin(['id'=>'payment-pjax-grid']); ?>
    <?php

echo TGridView::widget([
        'id' => 'payment-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            /* 'title',*/
            /* 'email:email',*/
            /* 'description:html',*/
            [
                'attribute' => 'book_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('book_id');
                }
            ],
            /* 'amount',*/
            'currency',
            /* 'transaction_id',*/
            /* 'payer_id',*/
            /* 'value:html',*/
            /* 'gateway_type',*/
            /* 'payment_status',*/
            [
                'attribute' => 'type_id',
                'filter' => isset($searchModel) ? $searchModel->getTypeOptions() : null,
                'value' => function ($data) {
                    return $data->getType();
                }
            ],
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
                }
            ],
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('created_by_id');
                }
            ],
            /* 'created_on:datetime',*/
            [
                'class' => 'app\components\TActionColumn',
                'template'=>'{view} {update}',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>