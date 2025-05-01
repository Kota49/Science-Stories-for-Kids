<?php
/**
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author    : Shiv Charan Panjeta < shiv@ozvid.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of OZVID Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\book\controllers;

use Yii;
use app\modules\book\models\Detail;
use app\modules\book\models\search\Detail as DetailSearch;
use app\components\TController;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\User;
use yii\web\HttpException;
use app\components\TActiveForm;
use app\modules\book\models\Category;
use app\modules\notification\models\Notification;
use app\modules\book\models\Sendnotification;
use app\modules\book\models\Book;
use app\base\TranslatorWidget;
use app\modules\notification\models\PushNotification;
use app\modules\translator\models\search\Translator;

/**
 * DetailController implements the CRUD actions for Detail model.
 */
class DetailController extends TController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className()
                ],
                'rules' => [
                    [
                        'actions' => [
                            'clear',
                            'delete',
                            'add',
                            'view',
                            'update',
                            'ajax',
                            'notification',
                            'final-delete'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'image',
                            'mass'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {

                            return User::isAdmin() || User::isUser() || User::isGuest();
                        }
                    ]
                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'image' => [
                'class' => 'app\components\actions\ImageAction',
                'modelClass' => Detail::class,
                'attribute' => 'image_file',
                'default' => true
            ]
        ];
    }

    /**
     * Lists all Detail models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Detail model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionNotification($id)
    {
        $notification = new Sendnotification();
        $notification->book_id = $id;
        $notification->title = \Yii::t('app', 'NEW BOOK LAUNCH');
        $notification->state_id = Detail::STATE_INACTIVE;
        if ($notification->save()) {
            \Yii::$app->session->setFlash('success', 'Notification Send successfully!');
            return $this->redirectBack();
        } else {
            \Yii::$app->session->setFlash('error', $notification->getErrorsString());
        }
    }

    /**
     * Creates a new Detail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd($id = null)
    {
        $model = new Detail();
        $model->loadDefaultValues();
        $model->state_id = Detail::STATE_ACTIVE;
        if (! empty($id)) {
            $model->category_id = $id;
        }

        /*
         * if (is_numeric($id)) {
         * $post = Post::findOne($id);
         * if ($post == null)
         * {
         * throw new NotFoundHttpException('The requested post does not exist.');
         * }
         * $model->id = $id;
         *
         * }
         */

        $model->checkRelatedData([
            'category_id' => Category::class,
            'created_by_id' => User::class
        ]);
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }

        if ($model->load($post)) {
            $price_id = $model->price_id;
            if ($price_id == 2) {
                $model->price = 0;
            }
            if ($price_id == 1 && empty($model->price)) {
                \Yii::$app->session->setFlash('error', 'Price Should Not Be Empty In Case Of Paid !');
            } else {
                $model->saveUploadedFile($model, 'image_file');
                if ($model->save()) {

                    TranslatorWidget::widget([
                        'type' => TranslatorWidget::TYPE_SAVE,
                        'model' => $model,
                        'dataAttribute' => [
                            'title',
                            'description',
                            'author_name'
                        ]
                    ]);

                    $pushNotificationModel = new PushNotification();
                    $pushNotificationModel->title = 'New book has been added (' . $model->title . ')';
                    $pushNotificationModel->state_id = PushNotification::STATE_INACTIVE;
                    $pushNotificationModel->value = (string) $model->id;

                    if (! $pushNotificationModel->save()) {
                        \Yii::$app->session->setFlash('error', $pushNotificationModel->getErrorsString());
                        return $this->redirectBack();
                    }

                    $translatorModel = new \app\modules\translator\models\Translator();
                    $translatorModel->model_id = $pushNotificationModel->id;
                    $translatorModel->model_type = PushNotification::className();
                    $translatorModel->language = 'he';
                    $translatorModel->text = User::getMessage("New book has been added", 'he') . ' (' . $model->getHebrewTitle() . ')';
                    $translatorModel->attribute_type = 'title';
                    $translatorModel->save();

                    \Yii::$app->session->setFlash('success', 'Book added successfully!');
                    return $this->redirect($model->getUrl());
                } else {
                    \Yii::$app->session->setFlash('error', $model->getErrorsString());
                }
            }
        }
        $this->updateMenuItems();
        return $this->render('add', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Detail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        $old_image = $model->image_file;
        if ($model->load($post)) {
            $price_id = $model->price_id;

            if ($price_id == 2) {
                $model->price = 0;
            }
            if ($price_id == 1 && empty($model->price)) {

                \Yii::$app->session->setFlash('error', 'Price Should Not  Empty In Case Of Paid !');
            } else {
                $model->image_file = $old_image;
                $model->saveUploadedFile($model, 'image_file');
                if ($model->save()) {
                    TranslatorWidget::widget([
                        'type' => TranslatorWidget::TYPE_SAVE,
                        'model' => $model,
                        'dataAttribute' => [
                            'title',
                            'description',
                            'author_name'
                        ]
                    ]);
                    \Yii::$app->session->setFlash('success', 'Book updated successfully!');
                    return $this->redirect($model->getUrl());
                } else {
                    \Yii::$app->session->setFlash('error', $model->getErrorsString());
                }
            }
        }
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Detail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->state_id = Detail::STATE_DELETED;
        $model->updateAttributes([
            'state_id'
        ]);
        \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Book Deleted Successfully.'));
        return $this->redirect([
            'index'
        ]);
    }

    public function actionFinalDelete($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        if (\Yii::$app->request->isPost) {
            $model->delete();
            if (\Yii::$app->request->isAjax) {
                return true;
            }
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Book Deleted Successfully.'));
            return $this->redirect([
                'index'
            ]);
        }
        return $this->render('final-delete', [
            'model' => $model
        ]);
    }

    /**
     * Truncate an existing Detail model.
     * If truncate is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClear($truncate = true)
    {
        $query = Detail::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            Detail::truncate();
        }
        \Yii::$app->session->setFlash('success', 'Detail Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the Detail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Detail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = Detail::findOne($id)) !== null) {

            if ($accessCheck && ! ($model->isAllowed()))
                throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function updateMenuItems($model = null)
    {
        switch (\Yii::$app->controller->action->id) {

            case 'add':
                {
                    $this->menu['index'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                }
                break;
            case 'index':
                {
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'Add'),
                        'url' => [
                            'add'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    $this->menu['clear'] = [
                        'label' => '<span class="glyphicon glyphicon-remove"></span>',
                        'title' => Yii::t('app', 'Clear'),
                        'url' => [
                            'clear'
                        ],
                        'htmlOptions' => [
                            'data-confirm' => "Are you sure to delete these items?"
                        ],
                        'visible' => User::isAdmin()
                    ];
                }
                break;
            case 'update':
                {
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'add'),
                        'url' => [
                            'add'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    $this->menu['index'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                }
                break;

            default:
            case 'view':
                {
                    $this->menu['index'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];

                    $this->menu['notification'] = [
                        'label' => '<span>Send Notification</span>',
                        'title' => Yii::t('app', 'Send Notification'),
                        'url' => $model->getUrl('notification'),
                        'visible' => false
                    ];
                    $this->menu['update'] = [
                        'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                        'title' => Yii::t('app', 'Update'),
                        'url' => $model->getUrl('update')
                        // 'visible' => User::isAdmin ()
                    ];
                    $this->menu['delete'] = [
                        'label' => '<span class="glyphicon glyphicon-trash"></span>',
                        'title' => Yii::t('app', 'Delete'),
                        'url' => $model->getUrl('final-delete'),
                        'visible' => true
                    ];
                }
        }
    }
}
