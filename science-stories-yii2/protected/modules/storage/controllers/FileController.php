<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\storage\controllers;

use alexantr\elfinder\CKEditorAction;
use alexantr\elfinder\ConnectorAction;
use alexantr\elfinder\InputFileAction;
use app\components\TActiveForm;
use app\components\TController;
use app\models\User;
use app\modules\storage\models\File;
use app\modules\storage\models\search\File as FileSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * FileController implements the CRUD actions for File model.
 */
class FileController extends TController
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
                            'update',
                            'delete',
                            'index'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'add',
                            'view',
                            'update',
                            'clone',
                            'ajax',
                            'upload',
                            'project',
                            'detail',
                            'files',
                            'image',
                            'connector',
                            'input',
                            'ckeditor'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isManager();
                        }
                    ],
                    [
                        'actions' => [
                            'files'
                        ],
                        'allow' => true,
                        'roles' => [
                            '?',
                            '*'
                        ]
                    ]
                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'connector' => [
                'class' => ConnectorAction::className(),
                'options' => [
                    'roots' => [
                        [
                            'driver' => 'LocalFileSystem',
                            'path' => UPLOAD_PATH,
                            'URL' => Url::to([
                                'file/files',
                                'file' => ''
                            ]),
                            'mimeDetect' => 'internal',
                            'imgLib' => 'gd',
                            'debug' => true,
                            'accessControl' => function ($attr, $path) {
                                // hide files/folders which begins with dot
                                return (strpos(basename($path), '.') === 0) ? ! ($attr == 'read' || $attr == 'write') : null;
                            }
                        ]
                    ]
                ]
            ],
            'image' => [
                'class' => 'app\components\actions\ImageAction',
                'modelClass' => File::class,
                'attribute' => 'id'
            ],
            'input' => [
                'class' => InputFileAction::className(),
                'connectorRoute' => 'connector'
            ],
            'ckeditor' => [
                'class' => CKEditorAction::className(),
                'connectorRoute' => 'connector'
            ] /*
               * ,
               * 'tinymce' => [
               * 'class' => TinyMCEAction::className(),
               * 'connectorRoute' => 'connector',
               * ],
               */
        ];
    }

    /**
     * Lists all File models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Lists all File models.
     *
     * @return mixed
     */
    public function actionProject($id = null, $model = null)
    {
        $searchModel = new FileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($id) {
            $dataProvider->query->orWhere([

                'f.project_id' => $id
            ]);

            $dataProvider->query->andFilterWhere([
                'model_type' => $model
            ]);
        }
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single File model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->actionDownload($id);
    }

    /**
     * Displays a single Type model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDetail($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * For get file
     *
     * @param string $file
     * @return mixed
     */
    public function actionFiles($file)
    {
        if (strstr($file, '..')) {
            throw new NotFoundHttpException(\Yii::t('app', "File not found"));
        }
        $image_path = UPLOAD_PATH . $file;
        if (! file_exists($image_path)) {
            throw new NotFoundHttpException(\Yii::t('app', "File not found" . $image_path));
        }
        return \yii::$app->response->sendFile($image_path, $file);
    }

    public function actionDownload($id, $thumbnail = false)
    {
        $model = $this->findModel($id, false);
        $provider = File::getProvider($model->account_id);
        if ($provider) {

            $content = null;
            try {
                $content = $provider->get($model->key);
            } catch (\Exception $e) {
                File::log($e->getMessage());
                File::log($e->getTraceAsString());
            }
            if ($content) {
                File::log('found in S3');
                @ob_clean();
                return Yii::$app->response->sendContentAsFile($content, $model->name);
            }
            File::log('NOT found in S3');
        }
        $file = $model->getFullPath();
        if (is_file($file)) {
            if ($thumbnail) {
                // TODO
            }
            File::log('found in uploads' . $file);
            @ob_clean();
            return Yii::$app->response->sendFile($file);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new File model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd(/* $id*/)
    {
        $model = new File();
        $model->loadDefaultValues();
        // $model->state_id = File::STATE_ACTIVE;

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
            'created_by_id' => User::class
        ]);
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post) && $model->save()) {
            return $this->redirect($model->getUrl());
        }
        $this->updateMenuItems();
        return $this->render('add', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing File model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->hasModule('onlyoffice')) {
            return $this->redirect([
                '/onlyoffice/doc/update',
                'id' => $id
            ]);
        }

        $model = $this->findModel($id);

        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post) && $model->save()) {
            return $this->redirect($model->getUrl());
        }
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Clone an existing File model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClone($id)
    {
        $old = $this->findModel($id);

        $model = new File();
        $model->loadDefaultValues();
        $model->state_id = File::STATE_ACTIVE;

        // $model->id = $old->id;
        $model->name = $old->name;
        $model->size = $old->size;
        $model->key = $old->key;
        $model->model_type = $old->model_type;
        $model->model_id = $old->model_id;
        $model->type_id = $old->type_id;
        // $model->created_on = $old->created_on;
        // $model->created_by_id = $old->created_by_id;

        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post) && $model->save()) {
            return $this->redirect($model->getUrl());
        }
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing File model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (\yii::$app->request->post()) {
            $model->delete();
            return $this->redirect([
                'index'
            ]);
        }
        return $this->render('delete', [
            'model' => $model
        ]);
    }

    /**
     * Truncate an existing File model.
     * If truncate is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClear($truncate = true)
    {
        $query = File::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            File::truncate();
        }
        \Yii::$app->session->setFlash('success', 'File Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }

    public function actionUpload($model_id, $model_type)
    {
        \Yii::$app->response->format = 'json';

        $model = $model_type::findOne($model_id);
        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if (! $model->isAllowed()) {
            throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));
        }
        $response = [
            'success' => false,
            'url' => $model->getUrl()
        ];

        $images = UploadedFile::getInstancesByName('qqfile');
        foreach ($images as $image) {

            $file = File::add($model, $image);

            if (! $file) {
                $response['error'] = $model->getErrors();
            } else {
                $response['success'] = true;
                $response['id'] = $model->id;
            }
        }

        return $response;
    }

    /**
     * Finds the File model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return File the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = File::findOne($id)) !== null) {

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
                        ],
                        'visible' => false // User::isAdmin ()
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
                    if ($model != null) {
                        $this->menu['clone'] = [
                            'label' => '<span class="glyphicon glyphicon-copy">Clone</span>',
                            'title' => Yii::t('app', 'Clone'),
                            'url' => $model->getUrl('clone')
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
                            'url' => $model->getUrl('delete')
                            // 'visible' => User::isAdmin ()
                        ];
                    }
                }
        }
    }
}
