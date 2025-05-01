<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\modules\shadow\controllers;

use app\components\TController;
use app\models\User;
use app\modules\shadow\models\Shadow;
use app\modules\shadow\models\search\Shadow as ShadowSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * ShadowController implements the CRUD actions for Shadow model.
 */
class SessionController extends TController
{

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
                            'delete',
                            'ajax',
                            'mass',
                            'login'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'logout'
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ]
                ]
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'delete' => [
                        'post'
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Shadow models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShadowSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Deletes an existing Shadow model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the Shadow model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Shadow the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = Shadow::findOne($id)) !== null) {

            if ($accessCheck && ! ($model->isAllowed()))
                throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findUserModel($id)
    {
        if (($model = User::findOne($id)) !== null) {

            if (! $model->isActive() || ! $model->isAllowed())
                throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function updateMenuItems($model = null)
    {}

    public function actionLogin($id)
    {
        if ($id == Yii::$app->user->id) {
            \Yii::$app->session->setFlash('error', ' Shadow on your own account not Allowed');
            return $this->goBack();
        }
        $user = $this->findUserModel($id);
        if ($user->hasAttribute('email_verified') && $user->email_verified == 0) {
            \Yii::$app->session->setFlash('user-action-error', 'Please verify your email to access all the features.');
            return $this->goBack();
        }
        $shadow = new Shadow();
        $shadow->to_id = $user->id;
        $shadow->state_id = Shadow::STATE_ACTIVE;
        $shadow->created_by_id = \Yii::$app->user->id;
        if ($shadow->save()) {
            if (method_exists("\app\components\WebUser", 'getIsAdminMode') && Yii::$app->user->isAdminMode) {
                Yii::$app->user->setIsAdminMode(false);
            }
            Yii::$app->user->switchIdentity($user);
            Yii::$app->session->set(Shadow::SESSION_KEY_NAME, $shadow->id);
        }
        return $this->goHome();
    }

    public function actionLogout($id)
    {
        $shadow = $this->findModel($id, false);
        if ($shadow) {
            $shadow->state_id = Shadow::STATE_INACTIVE;
            if ($shadow->updateAttributes([
                'state_id'
            ])) {
                // if (method_exists("\app\components\WebUser", 'getIsAdminMode')) {
                // Yii::$app->user->setIsAdminMode(true);
                // }
                Yii::$app->user->switchIdentity($shadow->createUser);
                Yii::$app->session->remove(Shadow::SESSION_KEY_NAME);
                $shadow->delete();
            }
        }
        return $this->goHome();
    }
}
