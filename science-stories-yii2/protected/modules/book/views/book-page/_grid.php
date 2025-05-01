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
 * @var app\modules\book\models\search\BookPage $searchModel
 */

?>
<?php
$gridColumns = [

    'id',
    'title',
    [
        'label' => Yii::t('app', 'Title') . ' in hebrew',
        'format' => 'raw',
        'value' => function ($data) {
            return ! empty($data->getTranslation('he', 'title', $data)) ? $data->getTranslation('he', 'title', $data) : Yii::t('app', 'N/A');
        }
    ],
    [
        'attribute' => 'category_id',
        'format' => 'raw',
        'value' => function ($data) {
            return $data->getRelatedDataLink('category_id');
        }
    ],
/*     'description:html',
 */
    [
        'attribute' => 'state_id',
        'format' => 'raw',
        'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
        'value' => function ($data) {
            return $data->getStateBadge();
        }
    ],
    [
        'attribute' => 'created_on',
        'format' => 'datetime',
        'value' => function ($data) {
            return $data->getConvertTime('created_on');
        }
    ],
    [
        'attribute' => 'created_by_id',
        'format' => 'raw',
        'value' => function ($data) {
            return ! empty($data->createdBy) ? $data->createdBy->full_name : "";
        }
    ]
]?>

<?php Pjax::begin(['id'=>'book-page-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'book-page-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'exportColumns' => $gridColumns,
        'emptyCell' => '',
        'exportable' => ($dataProvider->getCount() > 0) ? true : false,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'title',
            [
                'label' => Yii::t('app', 'Title') . ' in hebrew',
                'attribute' => 'title',
                'filter' => false,
                'format' => 'raw',
                'value' => function ($data) {
                    return ! empty($data->getTranslation('he', 'title', $data)) ? $data->getTranslation('he', 'title', $data) : Yii::t('app', 'N/A');
                }
            ],
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('category_id');
                }
            ],
         /*    [
                'attribute' => 'description',
                'format' => 'html',
                'value' => function ($data) {
                    return substr($data->description, 0, 150);
                }
            ], */

            [
                'attribute' => 'book_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('book_id');
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
                'template' => '{view} {update} {delete}',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>