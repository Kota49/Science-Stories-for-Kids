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
use app\modules\book\models\BookPage;
use app\modules\book\models\search\BookPage as BookPageSearch;
use app\components\TController;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\User;
use yii\web\HttpException;
use app\components\TActiveForm;
use app\modules\book\models\Book;
use app\modules\book\models\Detail;
use app\base\TranslatorWidget;

/**
 * BookPageController implements the CRUD actions for BookPage model.
 */
class BookPageController extends TController
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
                            'ajax',
                            'index',
                            'add',
                            'view',
                            'update',
                            'export',
                            'book-name',
                            'final-delete'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [

                            'ajax',
                            'image',
                            'pageimage'
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

    /**
     * Lists all BookPage models.
     *
     * @return mixed
     */
    public function actions()
    {
        return [
            'image' => [
                'class' => 'app\components\actions\ImageAction',
                'modelClass' => BookPage::class,
                'attribute' => 'image_file',
                'default' => true
            ],
            'pageimage' => [
                'class' => 'app\components\actions\ImageAction',
                'modelClass' => BookPage::class,
                'attribute' => 'page_image',
                'default' => true
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new BookPageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single BookPage model.
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

    /**
     * Creates a new BookPage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd($id = null)
    {
        $model = new BookPage();
        $model->loadDefaultValues();
        $model->state_id = BookPage::STATE_ACTIVE;
        if (! empty($id)) {
            $detail = Detail::findOne($id);
            if (! empty($detail)) {
                $model->category_id = $detail->category_id;
            }
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
        $model->book_id = $id;

        $model->checkRelatedData([
            'book_id' => Detail::class,
            'created_by_id' => User::class
        ]);
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post)) {

            $model->saveUploadedFile($model, 'image_file');
            $model->saveUploadedFile($model, 'page_image');
            if ($model->save()) {
                TranslatorWidget::widget([
                    'type' => TranslatorWidget::TYPE_SAVE,
                    'model' => $model,
                    'dataAttribute' => [
                        'title',
                        'description'
                    ]
                ]);
                \Yii::$app->session->setFlash('success', 'Page added successfully!');
                return $this->redirect($model->getUrl());
            } else {
                \Yii::$app->session->setFlash('error', $model->getErrorsString());
            }
        }

        $this->updateMenuItems();
        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionBookName()
    {
        // Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // $out = [];
        // $data = [];
        // if (isset($_POST['depdrop_parents'])) {
        // $parents = $_POST['depdrop_parents'];
        // if ($parents != null) {
        // $cat_id = $parents[0];
        // $query = Category::find()->where([
        // 'category_id' => $cat_id
        // ]);

        // foreach ($query->each() as $value) {
        // if (! empty($value)) {
        // $data[] = [
        // 'id' => $value->book_id,
        // 'name' => $value->title
        // ];
        // }
        // }
        // return [
        // 'output' => $data,
        // 'selected' => ''
        // ];
        // }
        // }
        // return [
        // 'output' => '',
        // 'selected' => ''
        // ];
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        $data = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $query = Detail::find()->where([
                    'category_id' => $cat_id
                ]);

                foreach ($query->each() as $value) {
                    if (! empty($value)) {
                        $data[] = [
                            'id' => $value->id,
                            'name' => $value->title
                        ];
                    }
                }
                return [
                    'output' => $data,
                    'selected' => ''
                ];
            }
        }
        return [
            'output' => '',
            'selected' => ''
        ];
    }

    /**
     * Updates an existing BookPage model.
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
        $old_page_image = $model->page_image;
        if ($model->load($post)) {
            $model->image_file = $old_image;
            $model->page_image = $old_page_image;
            $model->saveUploadedFile($model, 'image_file');
            $model->saveUploadedFile($model, 'page_image');
            if ($model->save()) {
                TranslatorWidget::widget([
                    'type' => TranslatorWidget::TYPE_SAVE,
                    'model' => $model,
                    'dataAttribute' => [
                        'title',
                        'description'
                    ]
                ]);
                \Yii::$app->session->setFlash('success', 'Page Updated successfully!');
                return $this->redirect($model->getUrl());
            } else {
                \Yii::$app->session->setFlash('error', $model->getErrorsString());
            }
        }
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing BookPage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->state_id = BookPage::STATE_DELETED;
        $model->updateAttributes([
            'state_id'
        ]);
        \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'BookPage Deleted Successfully.'));
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
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'BookPage Deleted Successfully.'));
            return $this->redirect([
                'index'
            ]);
        }
        return $this->render('final-delete', [
            'model' => $model
        ]);
    }

    /**
     * Truncate an existing BookPage model.
     * If truncate is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClear($truncate = true)
    {
        $query = BookPage::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            BookPage::truncate();
        }
        \Yii::$app->session->setFlash('success', 'BookPage Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the BookPage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return BookPage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = BookPage::findOne($id)) !== null) {

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
