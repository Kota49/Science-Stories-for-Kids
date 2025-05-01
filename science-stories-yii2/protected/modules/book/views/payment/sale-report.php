<?php
use app\components\TDashBox;
use yii\helpers\Url;
use app\models\User;
use miloschuman\highcharts\Highcharts;
use yii\jui\DatePicker;
use app\components\TActiveForm;
use app\modules\book\models\Payment;
use Symfony\Component\Finder\Iterator\DateRangeFilterIterator;
use dosamigos\datepicker\DateRangePicker;
use kartik\datetime\DateTimePicker;
/**
 *
 * @copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 * @author : Shiv Charan Panjeta < shiv@ozvid.com >
 */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Sales')
];
?>
<style>
.value h3{
color:black;
</style>
<div class="wrapper">
	<!--state overview start-->
	<?php
$model = new Payment();
$form = TActiveForm::begin([

    'id' => 'sale-form'
]);
?>
<div class="row">
	<div class="col-md-6">
	<?php

$start = (! empty(Yii::$app->request->get('date'))) ? Yii::$app->request->get('date') : 'Select Date Range';
echo $form->field($model, 'date')
    ->widget(DateTimePicker::classname(), [
    'options' => [
        'placeholder' => $start,
        'class' => 'form-control'
    ],
    'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autoclose' => true
    ]
])
    ->label(false);

?>
<div class="calendar-icon">
                  <i
                    class="fa fa-calendar field-icon"></i>
                </div>
	</div>
	</div>
	
	<?php
TActiveForm::end();
?>
	
<?php
$adminsale = Payment::find();
if (! empty($to_date) && ! empty($from_date)) {
    $adminsale->andWhere([
        'between',
        'created_on',
        $to_date,
        $from_date
    ]);
}

echo TDashBox::widget([
    'items' => [
        [
            'url' => Url::toRoute([
                '/book/detail/index'
            ]),
            'color' => 'green',
            'data' => '$ ' . $adminsale->sum('amount'),
            'header' => 'Admin Earning',
            'icon' => 'fa fa-money'
        ]
    ]
]);
?>
        <div class="row">
		<div class="col-md-6">
			<div class="card">
				<div class="card-heading">
					<span class="tools pull-right"> </span>
				</div>
				<div class="card-body">
					
					
 <?php
if (! empty(Yii::$app->request->get('date'))) {
    $sale = Payment::SaleReport(User::ROLE_ADMIN, $to_date, $from_date);
    echo Highcharts::widget([
        'htmlOptions' => [
            'id' => 'customuser'
        ],
        'options' => [
            'credits' => array(
                'enabled' => false
            ),

            'title' => [
                'text' => 'Sales'
            ],

            'chart' => [
                'type' => 'spline'
            ],
            'xAxis' => [
                'categories' => array_keys($sale)
            ],
            'yAxis' => [
                'title' => [
                    'text' => 'Sales'
                ]
            ],
            'series' => [
                [
                    'name' => 'Admin Sale',
                    'data' => array_values($sale)
                ]
            ]
        ]
    ]);
} else {
    $sale = Payment::monthly(User::ROLE_ADMIN);
    echo Highcharts::widget([
        'options' => [
            'credits' => array(
                'enabled' => false
            ),

            'title' => [
                'text' => 'Sales'
            ],
            'chart' => [
                'type' => 'spline'
            ],
            'xAxis' => [
                'categories' => array_keys($sale)
            ],
            'yAxis' => [
                'title' => [
                    'text' => 'Sales'
                ]
            ],
            'series' => [
                [
                    'name' => 'Admin Sales',
                    'data' => array_values($sale)
                ]
            ]
        ]
    ]);
}
?>


				</div>

			</div>



		</div>

		<div class="col-md-6">
			<div class="card">
				<div class="card-body">
<?php

echo Highcharts::widget([
    'scripts' => [
        'highcharts-3d',
        'modules/exporting'
    ],
    'options' => [
        'credits' => array(
            'enabled' => false
        ),
        'chart' => [
            'plotBackgroundColor' => null,
            'plotBorderWidth' => null,
            'plotShadow' => false,
            'type' => 'pie'
        ],
        'title' => [
            'text' => 'Sales'
        ],
        'tooltip' => [
            'valueSuffix' => ''
        ],
        'plotOptions' => [
            'pie' => [
                'allowPointSelect' => true,
                'cursor' => 'pointer',
                'dataLabels' => [
                    'enabled' => true
                ],
                'showInLegend' => true
            ]
        ],

        'htmlOptions' => [
            'style' => 'min-width: 100%; height: 400px; margin: 0 auto'
        ],
        'colors' => [
            '#ff763b',
            '#C56ED4'
        ],
        'series' => [
            [
                'name' => 'Total',
                'colorByPoint' => true,

                'data' => [
                    [
                        'name' => 'Admin Sales',
                        'y' => ! empty($adminsale->sum('amount')) ? round($adminsale->sum('amount'), 2) : 0,
                        'sliced' => true,
                        'selected' => true
                    ],
                    [
                        'name' => 'Total Sale',
                        'y' => ! empty($adminsale->sum('amount')) ? round($adminsale->sum('amount'), 2) : 0,
                        'sliced' => true,
                        'selected' => true
                    ]
                ]
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
<script>
$('#earning-date').on('change', function () {
      var date = $(this).val();
      var url = '<?=Url::toRoute(['/book/payment/sale-report'])?>?date='+date;
      
      console.log(url);
      window.location = url;
  });
  
</script>
