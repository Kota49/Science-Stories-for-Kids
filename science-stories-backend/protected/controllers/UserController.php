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
namespace app\controllers;

use Imagine\Image\ManipulatorInterface;
use app\components\TActiveForm;
use app\components\TController;
use app\components\helpers\TEmailTemplateHelper;
use app\models\EmailQueue;
use app\models\LoginForm;
use app\models\User;
use app\models\search\User as UserSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\imagine\Image;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends TController
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
                            'view',
                            'logout',
                            'changepassword',
                            'profileImage',
                            'toggle',
                            'download',
                            'dashboard',
                            'recover',
                            'image-manager',
                            'image-upload',
                            'theme-param',
                            'update',
                            'email-resend',
                            'image'
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'update',
                            'delete',
                            'profileImage',
                            'clear',
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
                            'view',
                            'update',
                            'changepassword'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isManager();
                        }
                    ],
                    [
                        'actions' => [
                            /* 'signup', */
                            'image'
                        ],
                        'allow' => (! defined('ENABLE_ERP')) ? true : false,
                        'roles' => [
                            '?',
                            '*'
                        ]
                    ],
                    [
                        'actions' => [
                            'login',
                            'recover',
                            'resetpassword',
                            'profileImage',
                            'download',
                            'add-admin',
                            'captcha',
                            'confirm-email'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isManager() || User::isUser() || User::isGuest() || User::IsAdmin();
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

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction'
                // 'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null
            ],
            'image' => [
                'class' => 'app\components\actions\ImageAction',
                'modelClass' => User::class,
                'attribute' => 'profile_file',
                'default' => true
            ]
        ];
    }

    /**
     * Clear runtime and assets
     *
     * @return \yii\web\Response
     */
    public function actionClear()
    {
        $runtime = Yii::getAlias('@runtime');
        $this->cleanRuntimeDir($runtime);

        $this->cleanAssetsDir();
        return $this->goBack();
    }

    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single User model.
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
     * Create an Admin
     *
     * @return \yii\web\Response|array|array[]|NULL[]|string
     */
    public function actionAddAdmin()
    {
        $this->layout = "guest-main";
        $count = User::find()->count();
        if ($count != 0) {
            return $this->redirect([
                '/'
            ]);
        }
        $model = new User();
        $model->scenario = 'add-admin';
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->role_id = User::ROLE_ADMIN;
            $model->state_id = User::STATE_ACTIVE;
            if ($model->validate()) {
                $model->setPassword($model->password);
                $model->generatePasswordResetToken();
                if ($model->save()) {
                    return $this->redirect([
                        'login'
                    ]);
                }
            }
        }
        return $this->render('signup', [
            'model' => $model
        ]);
    }

    /**
     * Recove password
     *
     * @throws HttpException
     * @return string
     *
     */
    public function actionRecover()
    {
        $this->layout = 'guest-main';
        $model = new User();
        $model->scenario = 'token_request';
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $user = User::findByEmail($model->email);
            if (! empty($user)) {
                $user->scenario = 'token_request';
                $user->generatePasswordResetToken();
                if (! $user->save()) {
                    throw new HttpException("Cant Generate Authentication Key");
                }
                $email = $user->email;
                $sub = "Password Reset";
                EmailQueue::add([
                    'from' => \Yii::$app->params['adminEmail'],
                    'to' => $email,
                    'subject' => $sub,
                    'type_id' => EmailQueue::TYPE_KEEP_AFTER_SEND,
                    'html' => TEmailTemplateHelper::renderFile('@app/mail/passwordResetToken.php', [
                        'user' => $user
                    ])
                ], true);
            }
            return $this->render('thankyou');
        }
        $this->updateMenuItems($model);
        return $this->render('requestPasswordResetToken', [
            'model' => $model
        ]);
    }

    /**
     * It's generate token for reset password
     *
     * @param string $token
     * @return \yii\web\Response|string
     */
    public function actionResetpassword($token)
    {
        $this->layout = 'guest-main';
        $model = User::findByPasswordResetToken($token);
        if (! ($model)) {

            \Yii::$app->session->setFlash('error', 'This URL is expired.');
            return $this->redirect([
                'user/recover'
            ]);
        }
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        $newModel = new User([
            'scenario' => 'resetpassword'
        ]);

        if ($newModel->load(Yii::$app->request->post()) && $newModel->validate()) {
            $model->setPassword($newModel->password);
            $model->removePasswordResetToken();
            $model->generateAuthKey();
            $model->last_password_change = date('Y-m-d H:i:s');

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'New password is saved successfully.');
            } else {
                \Yii::$app->session->setFlash('error', 'Error while saving new password.');
            }
            return $this->goHome();
        }
        $this->updateMenuItems($model);
        return $this->render('resetpassword', [
            'model' => $newModel
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->layout = 'main';
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $post = \yii::$app->request->post();
        $old_image = $model->profile_file;
        $password = $model->password;

        if (Yii::$app->request->isAjax && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }

        if ($model->load($post)) {
            if (! empty($post['User']['password']))
                $model->setPassword($post['User']['password']);
            else
                $model->password = $password;
            $model->profile_file = $old_image;
            $model->saveUploadedFile($model, 'profile_file');
            if ($model->save())
                return $this->redirect($model->getUrl());
        }

        $model->password = '';
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);

        if (\Yii::$app->user->id == $model->id) {
            \Yii::$app->session->setFlash('user-action-error', 'You are not allowed to perform this operation.');
            return $this->goBack();
        }
        // Disable hard-delete user
        // $model->delete();
        $model->state_id = User::STATE_DELETED;
        $model->save();
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionFinalDelete($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        if (\Yii::$app->request->isPost) {
            $model->delete();
            if (\Yii::$app->request->isAjax) {
                return true;
            }
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'User Deleted Successfully.'));
            return $this->redirect([
                'index'
            ]);
        }
        return $this->render('final-delete', [
            'model' => $model
        ]);
    }

    /**
     * Signup for guest user
     *
     * @return array|array[]|NULL[]|\yii\web\Response|string
     */
    public function actionSignup()
    {
        $this->layout = "guest-main";
        $model = new User([
            'scenario' => 'signup'
        ]);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $model->scenario = 'signup';
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->state_id = User::STATE_ACTIVE;
            $model->role_id = User::ROLE_USER;
            if ($model->validate()) {
                $model->scenario = 'add';
                $model->setPassword($model->password);
                $model->generatePasswordResetToken();
                if ($model->save()) {
                    $model->sendRegistrationMailtoAdmin();
                    \Yii::$app->user->login($model);
                    return $this->redirect([
                        '/dashboard'
                    ]);
                }
            }
        }
        return $this->render('signup', [
            'model' => $model
        ]);
    }

    /**
     * Login for everyone
     *
     * @return \yii\web\Response|string
     */
    public function actionLogin()
    {
        $this->layout = "guest-main";

        if (! \Yii::$app->user->isGuest) {
            if (User::isUser()) {
                return $this->goBack([
                    '/dashboard/index'
                ]);
            }
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // TODO: change redirect to return url
            if (! User::isAdmin()) {
                return $this->goBack([
                    '/dashboard/index'
                ]);
            } else {

                return $this->goHome();
            }
        }
        return $this->render('login', [
            'model' => $model
        ]);
    }

    /**
     *
     * @return \Faker\Provider\Image
     */
    public function actionProfileImage()
    {
        return Yii::$app->user->identity->getProfileImage();
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Authenticated user can change the password
     *
     * @param integer $id
     * @throws \yii\web\HttpException
     * @return array|array[]|NULL[]|\yii\web\Response|string
     */
    public function actionChangepassword($id)
    {
        $model = $this->findModel($id);
        if (! ($model->isAllowed()))
            throw new \yii\web\HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));

        $newModel = new User([
            'scenario' => 'changepassword'
        ]);
        if (Yii::$app->request->isAjax && $newModel->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return TActiveForm::validate($newModel);
        }
        if ($newModel->load(Yii::$app->request->post()) && $newModel->validate()) {
            $model->setPassword($newModel->password);
            $model->last_password_change = date('Y-m-d H:i:s');
            $model->generateAuthKey();
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Password Changed');
                return $this->redirect([
                    'view',
                    'id' => $model->id
                ]);
            } else {
                \Yii::$app->getSession()->setFlash('error', "Error !!" . $model->getErrorsString());
            }
        }
        $this->updateMenuItems($model);
        return $this->render('changepassword', [
            'model' => $newModel
        ]);
    }

    /**
     * Todo:
     */
    public function actionThemeParam()
    {
        $is_collapsed = Yii::$app->session->get('is_collapsed', 'sidebar-collapsed');
        $is_collapsed = empty($is_collapsed) ? 'sidebar-collapsed' : '';
        Yii::$app->session->set('is_collapsed', $is_collapsed);
    }

    /**
     * Resend verification email to user
     *
     * @return string
     */
    public function actionEmailResend()
    {
        $model = \Yii::$app->user->identity;
        $model->sendVerificationMailtoUser(true);
        \Yii::$app->session->setFlash('success', 'Email send successfully');
        return $this->goBack([
            '/dashboard/index'
        ]);
    }

    /**
     * Email confirmation via user
     *
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionConfirmEmail($id)
    {
        $user = User::find()->where([
            'activation_key' => $id
        ])->one();
        if (! empty($user)) {

            $user->email_verified = User::EMAIL_VERIFIED;
            $user->state_id = User::STATE_ACTIVE;
            if ($user->save()) {
                \Yii::$app->cache->flush();
                $user->refresh();
                if (Yii::$app->user->login($user, 3600 * 24 * 30)) {
                    \Yii::$app->getSession()->setFlash('success', 'Congratulations! your email is verified');
                    return $this->goBack([
                        '/dashboard/index'
                    ]);
                }
            }
        }
        \Yii::$app->getSession()->setFlash('expired', 'Token is Expired Please Resend Again');
        return $this->goBack([
            '/dashboard/index'
        ]);
    }

    /**
     * Check user details from here
     *
     * @param integer $id
     * @throws HttpException
     * @throws NotFoundHttpException
     * @return \app\models\User|NULL
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {

            if (! ($model->isAllowed()))
                throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Add a button and give permission to the user
     *
     * {@inheritdoc}
     * @see \app\components\TController::updateMenuItems()
     */
    protected function updateMenuItems($model = null)
    {
        switch (\Yii::$app->controller->action->id) {
            case 'index':
                {
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'Add'),
                        'url' => [
                            'add'
                        ],
                        'visible' => false
                    ];
                }
                break;
            case 'add':
                {
                    $this->menu['manage'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ],
                        'visible' => false
                    ];
                }
                break;
            default:
            case 'view':

                if ($model != null && $model->id != 1)
                    $this->menu['shadow/session/login'] = [
                        'label' => '<span class="glyphicon glyphicon-refresh ">Shadow</span>',
                        'title' => Yii::t('app', 'Login as ' . $model),
                        'url' => [
                            '/shadow/session/login',
                            'id' => $model->id
                        ],
                    /* 'htmlOptions'=>[], */
                    'visible' => false
                    ];
                $this->menu['add'] = [
                    'label' => '<span class="glyphicon glyphicon-plus"></span>',
                    'title' => Yii::t('app', 'Add'),
                    'url' => [
                        'add'
                    ],
                    'visible' => false
                ];

                if ($model != null)
                    $this->menu['changepassword'] = [
                        'label' => '<span class="glyphicon glyphicon-paste"></span>',
                        'title' => Yii::t('app', 'Change Password'),
                        'url' => [
                            'changepassword',
                            'id' => $model->id
                        ],

                        'visible' => User::isManager()
                    ];
                if ($model != null)
                    $this->menu['update'] = [
                        'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                        'title' => Yii::t('app', 'Update'),
                        'url' => [
                            'update',
                            'id' => $model->id
                        ],

                        'visible' => false
                    ];

                $this->menu['manage'] = [
                    'label' => '<span class="glyphicon glyphicon-list"></span>',
                    'title' => Yii::t('app', 'Manage'),
                    'url' => [
                        'index'
                    ],
                    'visible' => User::isManager()
                ];
                $this->menu['final-delete'] = [
                    'label' => '<span class="glyphicon glyphicon-trash"></span>',
                    'title' => Yii::t('app', 'Final Delete'),
                    'url' => $model->getUrl('final-delete'),
                    'class' => 'btn btn-danger',
                    'visible' => false
                ];
        }
    }
}
