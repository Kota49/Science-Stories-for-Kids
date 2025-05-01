<?php
use app\components\TDashBox;
use app\components\TDashboard;
use app\controllers\FeedController;
use app\modules\notification\models\Notification;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
?>
<div class="wrapper">
	<div class="card">
		<div class="card-body">
         <?php

        echo TDashBox::widget([
            'items' => [
                [
                    'url' => Url::toRoute([
                        'email/index'
                    ]),
                    'data' => Notification::findActive()->count(),
                    'header' => Notification::label(2),
                    'icon' => 'fa fa-inbox'
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

            $data = Notification::monthly();
            $dataFiltered = Notification::monthly(Notification::STATE_ACTIVE);
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
                            'name' => Notification::label(2),
                            'data' => array_values($data)
                        ],
                        [
                            'name' => Notification::label(2) . ' Active',
                            'data' => array_values($dataFiltered)
                        ]
                    ]
                ]
            ]);
            ?>
            </div>

			</div>
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