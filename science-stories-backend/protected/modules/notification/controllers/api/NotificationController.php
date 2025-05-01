<?php
namespace app\modules\notification\controllers\api;

use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\modules\api2\components\ApiTxController;
use app\modules\notification\models\search\Notification;
use yii\web\NotFoundHttpException;
use app\models\User;

/**
 * NotificationController implements the API actions for Notification model.
 */
class NotificationController extends ApiTxController
{

    public $modelClass = "app\modules\notification\models\Notification";

    public $modelSearchClass = "app\modules\notification\models\search\Notification";

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
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                        return User::isAdmin();
                        }
                    ]
                ]
            ]
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        $model = new Notification();
        $dataProvider = $model->search(\Yii::$app->request->bodyParams);
        $dataProvider->query->andWhere([
            'to_user_id' => \Yii::$app->user->id
        ]);
        $count = $dataProvider->getTotalCount();
        if ($count == 0) {
            throw new NotFoundHttpException('No new notification');
        }
        return $dataProvider;
    }
}
