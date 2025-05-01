<?php
use app\components\TDashBox;
use yii\helpers\Url;
use miloschuman\highcharts\Highcharts;
use app\modules\company\models\Profile;
use app\components\TDashboard;
use app\controllers\FeedController;
use app\modules\blog\models\Post;
use app\modules\blog\models\Category;
use app\modules\feature\models\Type;
use app\modules\feature\models\Update;
use app\modules\feature\models\Feature;
use app\modules\feature\models\Vote;
?>
<div class="wrapper">
	<div class="card">
		<div class="card-body">
         <?php

        echo TDashBox::widget([
            'items' => [
                [
                    'url' => Url::toRoute([
                        '/feature/type/index'
                    ]),
                    'data' => Type::find()->count(),
                    'header' => 'Types',
                    'icon' => 'fa fa-list'
                ],
                [
                    'url' => Url::toRoute([
                        '/feature/feature/index'
                    ]),
                    'data' => Feature::find()->count(),
                    'header' => 'Features',
                    'icon' => 'fa fa-list'
                ],
                [
                    'url' => Url::toRoute([
                        '/feature/vote/index'
                    ]),
                    'data' => Vote::find()->count(),
                    'header' => 'Votes',
                    'icon' => 'fa fa-list'
                ]
            ]
        ]);
        ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-12">
               <?php

            $data = Feature::monthly();
            echo Highcharts::widget([
                'options' => [
                    'credits' => array(
                        'enabled' => false
                    ),
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
                            'name' => 'Lists',
                            'data' => array_values($data)
                        ]
                    ]
                ]
            ]);
            ?>
           
			</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
         <?php

        $searchModel = new \app\modules\feature\models\search\Feature();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // $dataProvider->pagination->pageSize = 5;
        echo $this->render('/feature/_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
        ?>
      </div>
		</div>
		<?php if ( method_exists(FeedController::class, 'actionModule')){?>
			<div class="card">
			<div class="card-body">
 <?php
    echo TDashboard::widget([
        'items' => [
            [
                'label' => 'Recent Activities',
                'url' => Url::toRoute([
                    '/feed/module',
                    'id' => $this->context->module->id
                ])
            ]
        ]
    ]);
    ?>

      </div>
		</div>
<?php } ?>
	</div>