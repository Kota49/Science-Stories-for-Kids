<?php
use app\components\TDashBox;
use app\modules\storage\models\Provider;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
use app\modules\storage\models\Type;
use app\modules\storage\models\File;

?>
<div class="wrapper">
    <?php

    echo TDashBox::widget([
        'items' => [
            [
                'url' => Url::toRoute([
                    '/storage/provider'
                ]),
                'data' => Provider::find()->count(),
                'header' => 'Provider',
                'icon' => 'fa fa-users',
                'color' => ' card1',
            ],
            [
                'url' => Url::toRoute([
                    '/storage/type'
                ]),
                'data' => Type::find()->count(),
                'header' => 'Type',
                'icon' => 'fa fa-list',
                'color' => ' card2',
            ],
            [
                'url' => Url::toRoute([
                    '/storage/file'
                ]),
                'data' => File::find()->count(),
                'header' => 'File',
                'icon' => 'fa fa-file',
                'color' => ' card3',
            ]
        ]
    ]);
    ?>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?php

                    $data = File::monthly();
                    echo Highcharts::widget([
                        'options' => [
                            'credits' => [
                                'enabled' => false
                            ],
                            'title' => [
                                'text' => 'Monthly  '
                            ],
                            'chart' => [
                                'type' => 'spline'
                            ],
                            'xAxis' => [
                                'categories' => array_keys($data)
                            ],
                            'yAxis' => [
                                'title' => [
                                    'text' => 'Count'
                                ]
                            ],
                            'series' => [
                                [
                                    'name' => 'Files',
                                    'data' => array_values($data)
                                ]
                            ]
                        ]
                    ]);
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>