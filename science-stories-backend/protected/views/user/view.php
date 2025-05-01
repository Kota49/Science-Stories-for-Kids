<?php
// use app\components\useraction\UserAction;
use app\components\useraction\UserAction;
use app\models\User;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

/* $this->title = $model->label() .' : ' . $model->id; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Users'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
    <?php
    echo \app\components\PageHeader::widget([
        'model' => $model
    ]);
    ?>
    <div class="content-section clearfix">
		<div class="widget light-widget">
			<div class="user-view">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-sm-4 col-xl-3 col-xxl-2">
								<div class="profile-view-image">
                                    <?php
                                    echo Html::img($model->getImageUrl(150), [
                                        'class' => 'img-responsive',
                                        'alt' => $model,
                                        'width' => '150',
                                        'height' => '150'
                                    ])?><br /> <br />
								</div>
							</div>


							<div class="col-sm-8 col-xl-9 col-xxl-10">
                                <?php
                                echo \app\components\TDetailView::widget([
                                    'model' => $model,
                                    'options' => [
                                        'class' => 'table table-bordered'
                                    ],
                                    'attributes' => [
                                        'id',
                                        // 'full_name',
                                        'email:email',
                                        // 'contact_no',
                                        // 'country_code',
                                        [
                                            'attribute' => 'contact_no',
                                            'format' => 'raw',
                                            'value' => $model->country_code . '' . $model->contact_no,
                                            'visible' => ($model->role_id != User::ROLE_ADMIN) ? true : false
                                        ],
                                        [
                                            'label' => 'Country Code',
                                            'attribute' => 'country',
                                            'format' => 'raw',
                                            'value' => ! empty($model->country) ? '+' . $model->country : '',
                                            'visible' => ($model->role_id != User::ROLE_ADMIN) ? true : false
                                        ],
                                        /*
                                         * 'password',
                                         * 'date_of_birth:date',
                                         * 'gender',
                                         * 'about_me',
                                         * 'contact_no',
                                         * 'address',
                                         * 'latitude',
                                         * 'longitude',
                                         * 'city',
                                         * 'country',
                                         * 'zipcode',
                                         * 'language',
                                         * 'profile_file',
                                         * 'tos:boolean',
                                         */

                                        [
                                            'attribute' => 'role_id',
                                            'format' => 'raw',
                                            'value' => $model->getRole()
                                        ],
                                        [
                                            'attribute' => 'state_id',
                                            'format' => 'raw',
                                            'value' => $model->getStateBadge()
                                        ],
                                        /* [
                                                'attribute' => 'type_id',
                                                'value' => $model->getType ()
                                        ], */
                                       /*  'last_visit_time:datetime',
                                        'last_action_time:datetime',
                                        'last_password_change:datetime', */
                                        // 'login_error_count',
                                        /* 'activation_key', */
                                        // 'timezone',
                                        'created_on:datetime'

                                        /*
                                     * [
                                     * 'attribute' => 'created_by_id',
                                     * 'format' => 'raw',
                                     * 'value' => $model->getRelatedDataLink('created_by_id')
                                     * ]
                                     */
                                    ]
                                ])?>
                            </div>
						</div>
					</div>
				</div>

                <?php

                if ($model->role_id == User::ROLE_USER) {
                    ?>
                    <?php
                    echo UserAction::widget([
                        'model' => $model,
                        'attribute' => 'state_id',
                        'states' => $model->getStateOptions(),
                        'visible' => User::isAdmin()
                    ]);
                    ?>
                <?php
                }
                ?>
                
                  <div class="card">
					<div class="card-body">
						<div class="chatscript-panel">
            <?php
            $this->context->startPanel();
            $this->context->addPanel('Activities', 'feeds', 'Feed', $model /* ,null,true */);
            $this->context->addPanel('Login History', 'loginHistories', 'LoginHistory', $model /* ,null,true */);
            $this->context->addPanel('Access Token', 'accessToken', 'AccessToken', $model /* ,null,true */);

            $this->context->endPanel();
            ?>
         </div>
					</div>
				</div>



                <?php
                // echo \app\modules\comment\widgets\CommentsWidget::widget(['model' => $model]); ?>

            </div>
		</div>
	</div>
</div>
