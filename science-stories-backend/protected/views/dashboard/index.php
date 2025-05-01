<?php
use app\components\notice\Notices;
use app\models\EmailQueue;
use app\models\LoginHistory;
use app\modules\logger\models\Log;
use yii\helpers\Url;
use app\models\User;
use app\models\search\User as UserSearch;
use miloschuman\highcharts\Highcharts;
use app\modules\page\models\Page;
use app\modules\smtp\models\Account;
use app\modules\storage\models\File;
use app\modules\scheduler\models\Cronjob;
use app\base\TBaseDashBox;
/**
 *
 * @copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 * @author : Shiv Charan Panjeta < shiv@ozvid.com >
 */
/* @var $this yii\web\View */
// $this->title = Yii::t ( 'app', 'Dashboard' );

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Dashboard')
];
?>


<div class="wrapper">
	<!--state overview start-->
         <?php

         echo TBaseDashBox::widget([
            'items' => [
                [
                    'url' => Url::toRoute([
                        '/user'
                    ]),
                    'color' => ' card1',
                    'data' => User::findActive()->andWhere([
                        '!=',
                        'role_id',
                        User::ROLE_ADMIN
                    ])->count(),
                    'header' => 'Users'
                ],
                [
                    'url' => Url::toRoute([
                        '/smtp/email-queue'
                    ]),
                    'color' => 'card2',
                    'data' => EmailQueue::findActive(0)->count(),
                    'header' => 'Pending Emails',
                    'icon' => 'fa fa-envelope'
                ],
                [
                    'url' => Url::toRoute([
                        '/logger/log'
                    ]),
                    'color' => 'card3',
                    'data' => Log::find()->count(),
                    'header' => 'Logs',
                    'icon' => 'fa fa-sign-in'
                ],
                [
                    'url' => Url::toRoute([
                        '/login-history/index'
                    ]),
                    'color' => 'card4',
                    'data' => LoginHistory::find()->count(),
                    'header' => 'Login History',
                    'icon' => 'fa fa-history'
                ],
                [
                    'url' => Url::toRoute([
                        '/page'
                    ]),
                    'color' => 'card5',
                    'data' => Page::find()->count(),
                    'header' => 'Page',
                    'icon' => 'fa fa-file-text'
                ],
                [
                    'url' => Url::toRoute([
                        '/smtp/account'
                    ]),
                    'color' => 'card6',
                    'data' => Account::find()->count(),
                    'header' => 'Account',
                    'icon' => 'fa fa-user'
                ],
                [
                    'url' => Url::toRoute([
                        '/storage/file'
                    ]),
                    'color' => 'card7',
                    'data' => File::find()->count(),
                    'header' => 'Files',
                    'icon' => 'fa fa-file'
                ],
                [
                    'url' => Url::toRoute([
                        '/scheduler/cronjob'
                    ]),
                    'color' => 'card1',
                    'data' => Cronjob::find()->count(),
                    'header' => 'Cronjobs',
                    'icon' => 'fa fa-list'
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
            $data = UserSearch::monthly();
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
                            'name' => 'Users',
                            'data' => array_values($data)
                        ]
                    ]
                ]
            ]);
            ?>
		</div>
	</div>
</div>