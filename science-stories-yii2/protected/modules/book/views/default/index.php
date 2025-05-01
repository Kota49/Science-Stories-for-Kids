<?php
use app\components\TDashBox;
use yii\helpers\Url;
use miloschuman\highcharts\Highcharts;
use app\modules\book\models\Category;
use app\modules\book\models\BookPage;
use app\modules\book\models\Book;
use app\modules\book\models\Detail;
use app\modules\book\models\Audio;
use app\base\TBaseDashBox;
?>

<div class="wrapper">


         <?php

         echo TBaseDashBox::widget([
            'items' => [
                [
                    'url' => Url::toRoute([
                        '/book/category'
                    ]),
                    'data' => Category::find()->count(),
                    'header' => 'Category',
                    'icon' => 'fa fa-list',
                    'color' => 'card1'

                ],
                [
                    'url' => Url::toRoute([
                        '/book/detail'
                    ]),
                    'data' => Detail::find()->count(),
                    'header' => 'Books',
                    'icon' => 'fa fa-book',
                    'color' => 'card2'

                ],
                [
                    'url' => Url::toRoute([
                        '/book/book-page'
                    ]),
                    'data' => BookPage::find()->count(),
                    'header' => 'Pages',
                    'icon' => 'fa fa-file',
                    'color' => 'card3'
                  
                ]
                ,
                [
                    'url' => Url::toRoute([
                        '/book/audio'
                    ]),
                    'data' => Audio::find()->count(),
                    'header' => 'Audios',
                    'icon' => 'fa fa-file-audio-o',
                    'color' => 'card4'
                ]
            ]
        ]);
        ?>
        	<div class="card">
		<div class="card-heading">
			<span class="tools pull-right"> </span>
		</div>
		<div class="card-body">
            <?php
            $data = Detail::monthly();
            echo Highcharts::widget([
                'options' => [
                    'credits' => array(
                        'enabled' => false
                    ),

                    'title' => [
                        'text' => 'Monthly'
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
                            'name' => 'Books',
                            'data' => array_values($data)
                        ]
                    ]
                ]
            ]);
            ?>
		</div>
	</div>

</div>