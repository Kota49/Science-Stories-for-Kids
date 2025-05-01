<?php
use app\components\grid\TGridView;
use yii\helpers\Html;
use yii\helpers\Url;

use app\models\User;

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\faq\models\search\Faq $searchModel
 */

?>

<?php

Pjax::begin([
    'id' => 'faq-pjax-grid'
]);
?>
    <?php

    echo TGridView::widget([
        'id' => 'faq-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'question:html',
            [
                'label' => Yii::t('app', 'Question') . ' in hebrew',
                'attribute' => 'question',
                'filter' => false,
                'format' => 'raw',
                'value' => function ($data) {
                    return ! empty($data->getTranslation('he', 'question', $data)) ? $data->getTranslation('he', 'question', $data) : Yii::t('app', 'N/A');
                }
            ],
            'answer:html',
            [
                'label' => Yii::t('app', 'Answer') . ' in hebrew',
                'attribute' => 'answer',
                'filter' => false,
                'format' => 'raw',
                'value' => function ($data) {
                    return ! empty($data->getTranslation('he', 'answer', $data)) ? $data->getTranslation('he', 'answer', $data) : Yii::t('app', 'N/A');
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
                'attribute' => 'created_on',
                'format' => 'datetime',
                'filter' => DatePicker::widget([
                    'inline' => false,
                    'clientOptions' => [
                        'autoclose' => true
                    ],
                    'model' => $searchModel,
                    'attribute' => 'created_on',
                    'options' => [
                        'id' => 'created_on',
                        'class' => 'form-control',
                        'autocomplete' => 'off'
                    ]
                ]),
                'value' => function ($data) {
                    return $data->getConvertTime('created_on');
                }
            ],
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('created_by_id');
                }
            ],

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
<?php

Pjax::end();
?>