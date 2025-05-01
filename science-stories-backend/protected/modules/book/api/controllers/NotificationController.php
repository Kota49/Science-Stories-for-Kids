<?php
/**
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author	 : Shiv Charan Panjeta < shiv@ozvid.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of OZVID Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\book\api\controllers;

use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\User;
use app\modules\api\components\ApiBaseController;
use app\modules\rating\models\Rating;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\book\models\Detail;
use app\modules\book\models\Payment;
use yii\data\ArrayDataProvider;
use app\modules\notification\models\Notification;

class NotificationController extends ApiBaseController
{

    public $modelClass = "app\modules\notification\models\Notification";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class
                ],
                'rules' => [
                    [
                        'actions' => [
                            'notification-list',
                            'clear-notification',
                            'notification-on'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isUser();
                        }
                    ]
                ]
            ]
        ];
    }

    /**
     *
     * @OA\Post(path="/notification/notification-list",
     *   summary="",
     *   tags={"Book Management"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *    @OA\Parameter(
     *     name="lang",
     *     in="header",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Notification List of the charging space",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionNotificationList()
    {
        $query = Notification::find()->where([
            'state_id' => User::STATE_INACTIVE
        ])->my('to_user_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    /**
     *
     * @OA\Get(path="/notification/clear-notification",
     *   summary="",
     *   tags={"Book Management"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="lang",
     *     in="header",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="delete all notifications of logged in user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionClearNotification()
    {
        $data = [];
        $this->setStatus(400);
        $query = Notification::find()->my('to_user_id');
        if ($query->count() > User::STATE_INACTIVE) {
            Notification::updateAll([
                'state_id' => Notification::STATE_DELETED
            ], [
                'AND',
                [
                    'to_user_id' => \Yii::$app->user->id
                ]
            ]);

            $this->setStatus(200);
            $data['message'] = User::getMessage('All Notifications Cleared Successfully');
        } else {
            $data['message'] = User::getMessage('No Notification Found');
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/notification/notification-on",
     *   summary="",
     *   tags={"Book Management"},
     *    security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="lang",
     *     in="header",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Notification",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *     ),
     *   ),
     * )
     */
    public function actionNotificationOn()
    {
        $data = [];
        $model = \Yii::$app->user->identity;
        if ($model->notification_enabled == User::NOTIFICATION_ON) {
            $model->notification_enabled = User::NOTIFICATION_OFF;
            $data['message'] = User::getMessage('Notification is off');
        } else {
            $model->notification_enabled = User::NOTIFICATION_ON;
            $data['message'] = User::getMessage('Notification is on');
        }
        $model->updateAttributes([
            'notification_enabled'
        ]);
        $this->setStatus(200);
        // $data['detail'] = $model->asJson();
        $data['notification_enabled'] = $model->notification_enabled;
        return $data;
    }
}
