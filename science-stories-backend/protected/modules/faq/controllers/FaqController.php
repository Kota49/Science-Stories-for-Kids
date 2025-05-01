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
namespace app\modules\faq\controllers;

use app\components\TActiveForm;
use app\components\TController;
use app\models\User;
use app\modules\faq\models\Faq;
use app\modules\faq\models\search\Faq as FaqSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\db\Exception;
use app\base\TranslatorWidget;

/**
 * FaqController implements the CRUD actions for Faq model.
 */
class FaqController extends TController
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
                            'index',
                            'add',
                            'view',
                            'update',
                            'ajax',
                            'clear',
                            'delete',
                            'final-delete'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
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
     * Lists all Faq models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FaqSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Faq model.
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
     * Creates a new Faq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd(/* $id*/)
    {
        $model = new Faq();
        $model->loadDefaultValues();
        $model->state_id = Faq::STATE_ACTIVE;
        $model->checkRelatedData([
            'created_by_id' => User::class
        ]);
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post)) {
            $model->question = strip_tags($model->question);
            $model->answer = strip_tags($model->answer);
            if ($model->save()) {
                TranslatorWidget::widget([
                    'type' => TranslatorWidget::TYPE_SAVE,
                    'model' => $model,
                    'dataAttribute' => [
                        'question',
                        'answer'
                    ]
                ]);
                \Yii::$app->session->setFlash('success', 'Faq saved successfully.');
                return $this->redirect($model->getUrl());
            } else {
                \Yii::$app->session->setFlash('error', 'Error!!' . $model->getErrorsString());
            }
        }
        $this->updateMenuItems();
        return $this->render('add', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Faq model.
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
        if ($model->load($post)) {
            $model->question = strip_tags($model->question);
            $model->answer = strip_tags($model->answer);
            if ($model->save()) {
                TranslatorWidget::widget([
                    'type' => TranslatorWidget::TYPE_SAVE,
                    'model' => $model,
                    'dataAttribute' => [
                        'question',
                        'answer'
                    ]
                ]);
                \Yii::$app->session->setFlash('success', 'Faq updated successfully.');
                return $this->redirect($model->getUrl());
            } else {
                \Yii::$app->session->setFlash('error', 'Error!!' . $model->getErrorsString());
            }
        }
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Faq model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        $model->state_id = Faq::STATE_DELETED;
        if ($model->save()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Faq Deleted Successfully.'));
        } else {
            \Yii::$app->getSession()->setFlash('error', "Error !!" . $model->getErrorsString());
        }
        return $this->redirect([
            'index'
        ]);
    }

    public function actionFinalDelete($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        if (\Yii::$app->request->isPost) {
            try {
                if ($model->delete()) {

                    \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Faq Deleted Successfully.'));
                    return $this->redirect([
                        'index'
                    ]);
                } else {

                    \Yii::$app->getSession()->setFlash('error', $model->getErrorString());
                }
            } catch (Exception $e) {
                \Yii::$app->getSession()->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('final-delete', [
            'model' => $model
        ]);
    }

    /**
     * Truncate an existing Faq model.
     * If truncate is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClear($truncate = true)
    {
        $query = Faq::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            Faq::truncate();
        }
        \Yii::$app->session->setFlash('success', 'Faq Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the Faq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Faq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = Faq::findOne($id)) !== null) {

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
                        'visible' => false
                    ];
                }
                break;
            case 'update':
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
                    if ($model != null) {
                        $this->menu['update'] = [
                            'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                            'title' => Yii::t('app', 'Update'),
                            'url' => $model->getUrl('update')
                            // 'visible' => User::isAdmin ()
                        ];
                        $this->menu['delete'] = [
                            'label' => '<span class="glyphicon glyphicon-trash"></span>',
                            'title' => Yii::t('app', 'Final Delete'),
                            'url' => $model->getUrl('final-delete')
                            // 'visible' => User::isAdmin ()
                        ];
                    }
                }
        }
    }
}
