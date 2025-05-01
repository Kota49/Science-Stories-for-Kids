<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use app\modules\logger\models\Log;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\logger\models\Log */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Loggers'),
    'url' => [
        '/logger'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Logs'),
    'url' => [
        'index'
    ]
];
?>
<div class="wrapper">

    <div class="log-view">
        <?= \app\components\PageHeader::widget(['title' => 'System Info']); ?>
    </div>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs info-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#general">General</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#technical">Technical</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content mt-4">
                <div id="general" class="tab-pane active">
                    <?php

                    echo \app\components\TDetailView::widget([
                        'id' => 'generic-view',
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'App Name',
                                'format' => 'raw',
                                'value' => \Yii::$app->name
                            ],
                            [
                                'attribute' => 'App ID',
                                'format' => 'raw',
                                'value' => PROJECT_ID
                            ],
                            [
                                'attribute' => 'Company Name',
                                'format' => 'raw',
                                'value' => \Yii::$app->params['company']
                            ],
                            [
                                'attribute' => 'Environment',
                                'format' => 'raw',
                                'value' => Log::getEnvBadge() . ' ' . "<a href=" . Url::toRoute([
                                    'default/toggle-env'
                                ]) . ">
					<button class='btn btn-warning mr-3'>
						<i class='fa fa-refresh'></i> Toggle Env</button>
				</a>"
                            ]
                        ]
                    ]) ?>
                </div>
                <div id="technical" class="tab-pane fade">
                    <br>
                    <iframe frameBorder="0" src="<?php echo Url::toRoute(['default/php-info']) ?>" width="100%"
                        height="500px"></iframe>


                </div>
            </div>
        </div>
    </div>

</div>