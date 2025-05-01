<?php

/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ArrayDataProvider
 */
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\StringHelper;
use app\modules\translator\components\Helper;

$this->title = Yii::t('app', 'I18n');
$this->params['breadcrumbs'] = [
    $this->title
];

?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
   
        <?php
        Pjax::begin();
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label' => Yii::t('app', 'Alias'),
                    'value' => function ($model, $key, $index, $column) {
                        return $key;
                    }
                ],
                [
                    'label' => Yii::t('app', 'Local file'),
                    'value' => function ($model, $key, $index, $column) {

                        return substr($model, 75, 100);
                    }
                ],
                [
                    'class' => ActionColumn::className(),
                    'options' => [
                        'width' => '50px'
                    ],
                    'template' => '{update}',
                    'buttons' => [
                        [
                            'url' => 'update',
                            'icon' => 'pencil',
                            'class' => 'btn-primary',
                            'label' => Yii::t('app', 'Edit')
                        ]
                    ]
                ]
            ]
        ]);
        Pjax::end();
        ?>
   
</div>