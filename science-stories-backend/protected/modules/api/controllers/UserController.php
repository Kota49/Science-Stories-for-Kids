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
namespace app\modules\api\controllers;

use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\components\helpers\TEmailTemplateHelper;
use app\models\File;
use app\models\LoginForm;
use app\models\User;
use app\modules\api\components\ApiBaseController;
use app\modules\api\models\AccessToken;
use app\modules\contact\models\Information;
use app\modules\page\models\Page;
use app\modules\smtp\models\EmailQueue;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use yii\web\UploadedFile;
use app\modules\book\models\Book;
use app\modules\book\models\BookPage;
use app\modules\book\models\Audio;
use app\modules\book\models\Detail;
use app\modules\book\models\Category;
use app\models\Banner;
use app\modules\book\models\Promocode;
use app\modules\rating\models\Rating;
use app\models\HelpSupport;
use app\modules\notification\models\Notification;
use app\modules\book\models\ParentalControl;

/**
 * UserController implements the API actions for User model.
 */

/**
 *
 * @OA\Info(
 *   version="1.0",
 *   title="Application API",
 *   description="Userimplements the API actions for User model.",
 *   @OA\Contact(
 *     name="Shiv Charan Panjeta",
 *     email="shiv@toxsl.com",
 *   ),
 *
 * ),
 *  @OA\SecurityScheme(
 * securityScheme="bearerAuth",
 * in="header",
 * name="bearerAuth",
 * type="http",
 * scheme="bearer",
 * )
 *
 * @OA\Server(
 *   url="http://localhost/science-stories-yii2-1980/api",
 *   description="local server",
 * )
 * @OA\Server(
 *   url="http://192.168.2.155/science-stories-yii2-1980/api",
 *   description="local server",
 * )
 * @OA\Server(
 *   url="https://mars.ozvid.in/science-stories-yii2-1980/api",
 *   description="dev server",
 * )
 */
class UserController extends ApiBaseController
{

    public $modelClass = "app\models\User";

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
                            'check',
                            'profile-update',
                            'logout',
                            'change-password',
                            'clear-notification',
                            'contact-us',
                            'profile',
                            'book-list',
                            'book-detail',
                            'category-list',
                            'book-filters',
                            'page-list',
                            'audio-list',
                            'banner-list',
                            'generate-pin',
                            'delete-user-account',
                            'promocode-list',
                            'verify-pin',
                            'reset-pin',
                            'is-parental',
                            'help-support',
                            'help-support-list',
                            'check-notification',
                            'select-language',
                            'book-lock'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isUser();
                        }
                    ],

                    [
                        'actions' => [
                            'login',
                            'forgot-password',
                            'set-password',
                            'change-password',
                            'customer-signup',
                            'verify-otp',
                            'resend-otp',
                            'get-page',
                            'help-support',
                            'help-support-list'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isGuest();
                        }
                    ]
                ]
            ]
        ];
    }

    /**
     *
     * @OA\Get(path="/user/check",
     *   summary="",
     *   tags={"User"},
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
     *   @OA\Response(
     *     response=200,
     *     description="Returns user info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionCheck()
    {
        $data = [];
        $this->setStatus(400);
        if (! \Yii::$app->user->isGuest) {
            $this->setStatus(200);
            $data['access-token'] = \Yii::$app->user->identity->activation_key;

            $data['detail'] = \Yii::$app->user->identity->asJson();
        } else {
            $data['message'] = User::getMessage("User not authenticated. No token found");
        }

        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/login",
     *   summary="",
     *   tags={"User Authentication"},
     *   @OA\Parameter(
     *     name="lang",
     *     in="header",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"LoginForm[username]","LoginForm[password]","LoginForm[device_type]","LoginForm[device_token]","LoginForm[device_name]"},
     *              @OA\Property(property="LoginForm[username]", type="string",example="user@gmail.com",description="Email"),
     *              @OA\Property(property="LoginForm[password]", type="string", format="password", example="admin123",description="Password"),
     *              @OA\Property(property="LoginForm[device_type]", type="integer", example="1",description=""),
     *              @OA\Property(property="LoginForm[device_token]", type="string", example="3452435342534asdfdsf",description=""),
     *              @OA\Property(property="LoginForm[device_name]", type="string", example="android",description="")
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created user info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionLogin()
    {
        $data = [];
        $model = new LoginForm();
        $this->setStatus(400);
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $user = User::findByUsername($model->username);
            if ($user) {

                if ($user->role_id == User::ROLE_ADMIN) {

                    $data['message'] = User::getMessage('You are not allowed to login.');

                    return $data;
                }

                if ($user->state_id == User::STATE_ACTIVE) {
                    if ($model->applogin()) {
                        $this->setStatus(200);
                        $user->generateAuthKey();
                        $data['message'] = User::getMessage('Log in successfully');
                        $data['access-token'] = $user->getAuthKey();
                        AccessToken::add($model, $user->getAuthKey());
                        $user->updateAttributes([
                            'activation_key'
                        ]);
                        $data['access-token'] = $user->activation_key;
                        $data['detail'] = $user->asJson();
                    } else {
                        $this->setStatus(400);
                        // $data['message'] = \Yii::t('app', 'Incorrect password');
                        $data['message'] = User::getMessage('Incorrect password');
                    }
                } else {
                    $data['message'] = User::getMessage('Your account is ' . $user->getState() . ' please contact admin');
                }
            } else {
                $data['message'] = User::getMessage("Invalid Credentials");
            }
        } else {
            $data['message'] = User::getMessage("No data posted");
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/profile",
     *   summary="User",
     *   tags={"User"},
     *  security={
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
     * @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={},
     *              @OA\Property(property="User[profile_file]", type="file", example="Image File",description="Profile File"),
     *              @OA\Property(property="User[full_name]", type="string", example="test",description="Full Name"),
     *              @OA\Property(property="User[email]", type="string", example="test@gmail.com",description="Email"),
     *              @OA\Property(property="User[contact_no]", type="string", example="1234567890",description="Contact Number"),
     *              @OA\Property(property="User[country_code]", type="string", example="+91",description="Country Code"),
     *
     *           ),
     *       ),
     *   ),
     * @OA\Response(
     *     response=200,
     *     description="Profile Detail Update",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *     ),
     *   ),
     * )
     */
    public function actionProfile()
    {
        $this->setStatus(400);
        $data = [];
        $model = \Yii::$app->user->identity;
        $old_image = $model->profile_file;
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $model->full_name = $model->full_name;
            $model->saveUploadedFile($model, 'profile_file', $old_image);
            $model->is_profile_completed = User::IS_PROFILE_COMPLETED;
            if ($model->save()) {
                $this->setStatus(200);
                $data['message'] = User::getMessage("Profile Updated successfully");
                $data['detail'] = $model->asJson();
            } else {
                $data['message'] = $model->getErrors();
            }
        } else {
            $data['message'] = User::getMessage("Data Not Posted");
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/logout",
     *   summary="",
     *   tags={"User Authentication"},
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
     *   @OA\Response(
     *     response=200,
     *     description="Log out the logged in user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionLogout()
    {
        $data = [];
        $user = \Yii::$app->user->identity;
        if (\Yii::$app->user->logout()) {
            $user->generateAuthKey();
            $user->updateAttributes([
                'activation_key'
            ]);
            AccessToken::deleteOldAppData($user->id);
            $this->setStatus(200);
            $data['message'] = User::getMessage('Logout Successfully');
        }

        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/change-password",
     * summary="",
     * tags={"User"},
     * security={
     * {"bearerAuth": {}}
     * },
     *  @OA\Parameter(
     *     name="lang",
     *     in="header",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     * @OA\RequestBody(
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(
     * required={"User[password]","User[confirm_password]"},
     * @OA\Property(property="User[password]", type="string", format="password", example="admin123",description="Password"),
     * @OA\Property(property="User[confirm_password]", type="string", format="password", example="admin123",description="confirm Password"),
     * ),
     * ),
     * ), * @OA\Response(
     * response=200,
     * description="Change password message",
     * @OA\MediaType(
     * mediaType="application/json",
     * ),
     * ),
     * )
     */
    public function actionChangePassword()
    {
        $data = [];
        $this->setStatus(400);
        $model = \Yii::$app->user->identity;
        $newModel = new User([
            'scenario' => 'changepassword'
        ]);
        if ($newModel->load(Yii::$app->request->post())) {

            if ($newModel->password == $newModel->confirm_password) {
                $model->setPassword($newModel->password);
                $model->generateAuthKey();
                if ($model->updateAttributes([
                    'password'
                ])) {
                    $this->setStatus(200);
                    $data['message'] = User::getMessage('Password changed successfully');
                } else {
                    $data['message'] = $model->getErrors();
                }
            } else {
                $data['message'] = User::getMessage('Password not matched');
            }
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/forgot-password",
     *   summary="User",
     *   tags={"User"},
     *   @OA\Parameter(
     *     name="lang",
     *     in="header",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     * @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *
     *              @OA\Property(property="User[email]", type="email", example="test@toxsl.in",description="email"),
     *              @OA\Property(property="User[country_code]", type="input", example="+91",description="Country code"),
     *           ),
     *       ),
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Recover Password",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *     ),
     *   ),
     * )
     */
    public function actionForgotPassword()
    {
        $data = [];
        $this->setStatus(400);
        $model = new User();
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            if (! empty($model->country_code)) {
                $user = User::findOne([
                    'contact_no' => $model->email,
                    'country_code' => $model->country_code
                ]);
            } else {
                $user = User::findOne([
                    'email' => $model->email
                ]);
            }

            if ($user) {
                $user->generatePasswordResetToken();

                if (! $user->save()) {
                    throw new HttpException('400', "Cannot generate authentication key");
                }
                $this->setStatus(200);
                $email = $user->email;
                $sub = "Password Reset";
                EmailQueue::add([
                    'from' => \Yii::$app->params['adminEmail'],
                    'to' => $email,
                    'subject' => $sub,
                    'type_id' => EmailQueue::STATE_SENT,
                    'html' => \yii::$app->view->renderFile('@app/mail/passwordResetToken.php', [
                        'user' => $user
                    ])
                ], false);
                $data['message'] = User::getMessage("Please check your email to reset your password.");
            } else {
                if (! empty($model->country_code)) {
                    $data['message'] = User::getMessage("Contact number is not registered.");
                } else {
                    $data['message'] = User::getMessage("Email is not registered.");
                }
            }
        } else {
            $data['message'] = User::getMessage("Data not posted.");
        }

        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/get-page",
     *   summary="",
     *   tags={"User"},
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
     *   @OA\Parameter(
     *     name="type",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created page info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionGetPage($type)
    {
        $data = [];
        $model = Page::findActive()->andWhere([
            'type_id' => $type
        ])->one();
        if ($model) {
            $this->setStatus(200);
            $data['message'] = User::getMessage('Data found successfully');
            $data['detail'] = $model;
        } else {
            $this->setStatus(400);
            $data['message'] = User::getMessage('Page not found');
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/category-list",
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
     *   @OA\Response(
     *     response=200,
     *     description="Returns completed news list",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     *
     *
     */
    public function actionCategoryList($search = null)
    {
        $article = Category::findActive();
        if (! empty($search)) {
            $article->andFilterWhere([
                'like',
                'title',
                $search
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $article,

            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    /**
     *
     * @OA\Get(path="/user/book-list",
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
     *   @OA\Parameter(
     *     name="search",
     *     in="query",
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
     *     description="Returns completed news list",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     *
     *
     */
    public function actionBookList($page = null, $search = null)
    {
        $article = Detail::findActive();
        if (! empty($search)) {
            $article->andFilterWhere([
                'like',
                'title',
                $search
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $article,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
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
     * @OA\Post(path="/user/book-filters",
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
     *   @OA\Parameter(
     *     name="age",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="catg_id",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *       type="int"
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
     *     description="Returns completed news list",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     *
     *
     */
    public function actionBookFilters($page = null, $age = null, $catg_id = null)
    {
        $article = Detail::findActive();
        if ($age != null) {

            $article->andFilterWhere([
                'age' => $age
            ]);
        }
        if (! empty($catg_id)) {

            $category = explode(',', $catg_id);
            $article->andWhere([
                'in',
                'category_id',
                $category
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $article,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    /**
     *
     * @OA\Get(path="/user/page-list",
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
     *   @OA\Parameter(
     *     name="book_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created page info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionPageList($book_id, $page = null)
    {
        $data = [];
        $article = BookPage::find()->alias('c')
            ->joinWith([
            'book as b'
        ])
            ->where([
            'b.id' => $book_id,
            'c.state_id' => BookPage::STATE_ACTIVE,
            'b.state_id' => BookPage::STATE_ACTIVE
        ]);

        $data = new ActiveDataProvider([
            'query' => $article,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/audio-list",
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
     *   @OA\Parameter(
     *     name="book_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *      @OA\Parameter(
     *     name="page_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created page info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionAudioList($book_id, $page_id)
    {
        $data = [];
        $model = Audio::find()->alias('a')
            ->joinWith([
            'book as b',
            'page as p'
        ])
            ->where([
            'b.id' => $book_id,
            'p.id' => $page_id,
            'a.state_id' => Audio::STATE_ACTIVE,
            'b.state_id' => Audio::STATE_ACTIVE,
            'p.state_id' => Audio::STATE_ACTIVE
        ])
            ->one();
        if ($model) {
            $this->setStatus(200);
            $data['detail'] = $model;
        } else {
            $this->setStatus(400);
            $data['message'] = User::getMessage('Page not found');
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/contact-us",
     *   summary="Contact Us",
     *   tags={"User"},
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
     *   *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *
     *           @OA\Schema(
     *              required={
     *              "Information[email]",
     *              "Information[description]",
     *              "Information[full_name]",
     *              },
     *              @OA\Property(
     *              property="Information[email]",
     *              type="email", format="text",
     *              example="Sam@gmail.in",
     *              description="Enter Email"
     *              ),
     *              @OA\Property(
     *              property="Information[description]",
     *              type="string", format="text",
     *              example="I want to contact",
     *              description="Enter Description"
     *              ),
     *
     *              @OA\Property(
     *              property="Information[full_name]",
     *              type="string", format="text",
     *              example="Sam",
     *              description="Enter Full Name"
     *              ),
     *
     *
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Contact Successfull Message",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionContactUs()
    {
        $data = [];
        $this->setStatus(400);
        $model = new Information();
        if ($model->load(Yii::$app->request->post())) {
            $user = \Yii::$app->user->identity;
            $model->mobile = '+919876543210';
            $from = $model->email;
            $message = \yii::$app->view->renderFile('@app/mail/contact.php', [
                'user' => $model
            ]);
            $sub = 'New Contact Mail: ';
            EmailQueue::sendEmailToAdmins([
                'from' => $from,
                'subject' => $sub,
                'html' => $message,
                'type_id' => EmailQueue::TYPE_KEEP_AFTER_SEND
            ], false);

            if ($model->save()) {
                $data['message'] = User::getMessage("Warm Greetings!! Thank you for contacting us. We have received your request. Our representative will contact you soon.");
                $this->setStatus(200);
            } else {
                $data['error'] = $model->getErrors();
            }
        } else {
            $data['error'] = User::getMessage("No Data Posted");
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/customer-signup",
     *   summary="",
     *   tags={"User Authentication"},
     *   @OA\Parameter(
     *     name="lang",
     *     in="header",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(property="User[email]", type="email", example="test",description="Email"),
     *              @OA\Property(property="User[password]", type="password", example="12345678",description="Password"),
     *              @OA\Property(property="AccessToken[device_token]", type="string", example="263623dsafsdf",description="device token"),
     *              @OA\Property(property="AccessToken[device_name]", type="string", example="android",description="android / ios"),
     *              @OA\Property(property="AccessToken[device_type]", type="string",example="1",description="device type")
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Create new user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionCustomerSignup()
    {
        $this->setStatus(400);
        $data = [];
        $model = new User();
        $model->loadDefaultValues();
        $model->state_id = User::STATE_ACTIVE;
        $model->role_id = User::ROLE_USER;
        $access_token = new AccessToken();
        if ($model->load(Yii::$app->request->post()) && $access_token->load(Yii::$app->request->post())) {
            $email_identify = User::findOne([
                'email' => $model->email
            ]);
            if (! empty($email_identify)) {
                $model = $email_identify;
                $this->setStatus(400);
                $data['message'] = User::getMessage("Email Already Exist");
            } else {
                $data['message'] = User::getMessage("New Register");

                $model->push_enabled = User::STATE_ACTIVE;
                $model->email_verified = User::STATE_ACTIVE;
                $model->otp = rand(1000, 9999);
                // $model->otp_verified = User::STATE_INACTIVE;
                $model->setPassword($model->password);
                $model->generateAuthKey();
                $model->email_verified = User::EMAIL_VERIFIED;
                $model->country_code = $model->country;
                if ($model->save()) {
                    // \Yii::$app->user->login($model);

                    AccessToken::add($access_token, $model->getAuthKey(), $model->id);
                    $this->setStatus(200);
                    $data['access-token'] = $model->activation_key;

                    $data['detail'] = $model->asJson();
                } else {
                    $data['message'] = $model->getErrors();
                }
            }
        } else {
            $data['message'] = User::getMessage("Data not posted.");
        }

        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/verify-otp",
     *   summary="",
     *   tags={"User Authentication"},
     *   @OA\Parameter(
     *     name="lang",
     *     in="header",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"User[email]","User[otp]"},
     *              @OA\Property(property="User[email]", type="string", example="test",description="Email"),
     *              @OA\Property(property="User[otp]", type="string", example="1234",description="Enter received OTP")
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Verify otp",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionVerifyOtp()
    {
        $data = [];
        $model = new User([
            'scenario' => 'api-verify-otp'
        ]);
        $this->setStatus(400);
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $user = User::find()->where([
                'email' => $model->email,
                'otp' => $model->otp
            ])->one();

            if (! empty($user)) {
                $model->generateAuthKey();
                $user->otp_verified = User::OTP_VERIFIED;

                if ($user->save()) {
                    if (\Yii::$app->user->login($user)) {
                        $this->setStatus(200);
                        $data['message'] = User::getMessage('Account verified successfully');
                        $data['access-token'] = $user->activation_key;
                        $data['detail'] = $user->asJson();
                    }
                } else {
                    $data['message'] = $user->getErrors();
                }
            } else {
                $data['message'] = User::getMessage('Incorrect OTP');
            }
        } else {
            $data['message'] = User::getMessage('Data Not Posted');
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/resend-otp",
     *   summary="",
     *   tags={"User Authentication"},
     *   @OA\Parameter(
     *     name="lang",
     *     in="header",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"User[email]"},
     *
     *
     *
     *              @OA\Property(property="User[email]", type="string", example="12345678",description="mail of user")
     *
     *
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Create new user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionResendOtp()
    {
        $data = [];
        $model = new User();
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $user = User::find()->where([
                'email' => $model->email
            ])->one();

            if (! empty($user)) {
                $user->otp = rand(1000, 9999);
                $user->otp_verified = User::STATE_INACTIVE;
                $user->sendVerificationMailtoUser();

                $user->last_action_time = date('Y-m-d H:i:s');
                $user->updateAttributes([
                    'otp',
                    'last_action_time'
                ]);
                $this->setStatus(200);
                $data['detail'] = $user->asJson();
                $data['message'] = User::getMessage("Otp Sent Successfully");
            } else {
                $this->setStatus(400);
                $data['message'] = User::getMessage('No user found');
            }
        } else {
            $data['message'] = User::getMessage('No data posted');
        }

        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/generate-pin",
     *   summary="User",
     *   tags={"User"},
     *  security={
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
     * @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={},
     *              @OA\Property(property="User[pin]", type="string",format="password", example="1234",description="Pin"),
     *              @OA\Property(property="User[confirm_pin]", type="string",format="password", example="1234",description="Confrm Pin"),
     *
     *           ),
     *       ),
     *   ),
     * @OA\Response(
     *     response=200,
     *     description="Pin Generated Successfully",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *     ),
     *   ),
     * )
     */
    public function actionGeneratePin()
    {
        $data = [];
        $this->setStatus(400);
        $model = \Yii::$app->user->identity;
        $newModel = new User([
            'scenario' => 'generatepin'
        ]);
        if ($newModel->load(Yii::$app->request->post())) {

            if ($newModel->pin == $newModel->confirm_pin) {
                $model->updateAttributes([
                    'pin' => $newModel->pin
                ]);
                $this->setStatus(200);
                $data['message'] = User::getMessage(\Yii::t('app', 'Pin Generated Successfully'));
            } else {
                $data['message'] = User::getMessage(\Yii::t('Pin Not matched'));
            }
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/delete-user-account",
     *   summary="",
     *   tags={"User"},
     *  security={
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
     *     description="Returns page info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionDeleteUserAccount()
    {
        $this->setStatus(400);
        $user = \Yii::$app->user->identity;
        if (! empty($user)) {
            $user->state_id = User::STATE_DELETED;
            if ($user->save()) {
                $this->setStatus(200);
                $data['message'] = User::getMessage(\yii::t('app', "Account Deleted Successfully"));
            } else {
                $data['message'] = $user->getErrors();
            }
        } else {
            $data['message'] = User::getMessage(\yii::t('app', "Account Not Found"));
        }

        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/banner-list",
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
     *     description="Returns completed news list",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     *
     *
     */
    public function actionBannerList($page = null, $search = null)
    {
        $article = Banner::findActive();
        if (! empty($search)) {
            $article->andFilterWhere([
                'like',
                'title',
                $search
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $article,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    /**
     *
     * @OA\Get(path="/user/book-detail",
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
     *   @OA\Parameter(
     *     name="book_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *    @OA\Parameter(
     *     name="pin",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created page info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionBookDetail($book_id, $pin = null)
    {
        $data = [];
        $pin_verified = \Yii::$app->user->identity->pin_verified;
        if ($pin_verified == User::STATE_ACTIVE) {
            if (\Yii::$app->user->identity->pin != $pin) {
                $data['message'] = User::getMessage('Pin is not correct');
                return $data;
            }
        }
        $article = Detail::findOne($book_id);
        if (! empty($article)) {
            $data['detail'] = $article->asJson();
        }

        $this->setStatus(200);
        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/promocode-list",
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
     *    @OA\Parameter(
     *     name="page",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description=" Show all the Productcode",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *     ),
     *   ),
     * )
     */
    public function actionPromocodeList($page = null)
    {
        $query = Promocode::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
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
     * @OA\Post(path="/user/verify-pin",
     *   summary="",
     *   tags={"User Authentication"},
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
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"User[email]","User[pin]"},
     *              @OA\Property(property="User[email]", type="string", example="test",description="Email"),
     *              @OA\Property(property="User[pin]", type="integer", example="1234",description="Enter Your Pin")
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Verify pin",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionVerifyPin()
    {
        $this->setStatus(400);

        $data = [];
        $model = new User();

        $model->scenario = 'api-verify-pin';
        $post = \Yii::$app->request->post();

        if ($model->load($post)) {

            $user = User::find()->where([
                'email' => $model->email,
                'pin' => $model->pin
            ])->one();

            if (! empty($user)) {
                if ($user->pin_verified == User::STATE_ACTIVE) {

                    $user->pin_verified = User::STATE_INACTIVE;
                    $data['message'] = User::getMessage('Parental Control Deactivate Successfully');
                } else {
                    $user->pin_verified = User::STATE_ACTIVE;
                    $data['message'] = User::getMessage('Parental Control Activate Successfully');
                }

                $user->updateAttributes([
                    'pin_verified'
                ]);
                $this->setStatus(200);

                $data['detail'] = $user->asJson();
            } else {
                $this->setStatus(400);
                $data['message'] = User::getMessage('Incorrect Pin');
            }
        } else {

            $data['message'] = User::getMessage('Data Not Posted');
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/reset-pin",
     *   summary="User",
     *   tags={"User Authentication"},
     *  security={
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
     * @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"User[pin]","User[confirm_pin]","User[old_pin]"},
     *              @OA\Property(property="User[old_pin]", type="string",format="password", example="1234",description="Old Pin"),
     *              @OA\Property(property="User[pin]", type="string",format="password", example="1234",description="Pin"),
     *              @OA\Property(property="User[confirm_pin]", type="string",format="password", example="1234",description="Confrm Pin"),
     *
     *           ),
     *       ),
     *   ),
     * @OA\Response(
     *     response=200,
     *     description="Pin Generated Successfully",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *     ),
     *   ),
     * )
     */
    public function actionResetPin()
    {
        $data = [];
        $this->setStatus(400);
        $model = \Yii::$app->user->identity;
        $newModel = new User([
            'scenario' => 'reset-pin'
        ]);
        $user = User::find()->where([
            'email' => $model->email
        ])->one();

        if (! empty($user)) {
            if ($newModel->load(Yii::$app->request->post())) {

                if ($user->pin == $newModel->old_pin) {

                    if ($newModel->pin == $newModel->confirm_pin) {
                        $model->updateAttributes([
                            'pin' => $newModel->pin
                        ]);
                        $this->setStatus(200);
                        $data['message'] = User::getMessage('Pin Reset Successfully');
                    } else {
                        $data['message'] = User::getMessage('Pin Not matched');
                    }
                } else {
                    $data['message'] = User::getMessage('Old Pin Not matched');
                }
            } else {
                $data['message'] = User::getMessage('Data Not Posted');
            }
        } else {
            $data['message'] = User::getMessage('User Not Find');
        }

        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/help-support",
     *   summary="",
     *   tags={"Help And Support"},
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
     *   tags={"Help And Support"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *            required={"HelpSupport[title]","HelpSupport[message]","HelpSupport[email]"},
     *              @OA\Property(property="HelpSupport[title]", type="string", example="",description=""),
     *              @OA\Property(property="HelpSupport[message]", type="string",description=""),
     *              @OA\Property(property="HelpSupport[email]", type="string",description=""),
     *
     *
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionHelpSupport()
    {
        $data = [];
        $this->setStatus(400);
        $model = new HelpSupport();
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            if ($model->save()) {
                User::SendContactUsEmail($model);
                $data['message'] = User::getMessage("Warm Greetings!! Thank you for contacting us. We have received your request. Our representative will contact you soon.");
                $this->setStatus(200);
            } else {
                $data['message'] = $model->getErrors();
            }
        } else {
            $data['message'] = \Yii::t('app', 'Data Not Posted');
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/help-support-list",
     *   summary="",
     *   tags={"Help And Support"},
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
     *    @OA\Parameter(
     *     name="page",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description=" Show all the Help and Support List",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *     ),
     *   ),
     * )
     */
    public function actionHelpSupportList($page = null)
    {
        $query = HelpSupport::find()->my();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
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
     * @OA\Get(path="/user/check-notification",
     *   summary="",
     *   tags={"Test"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *    @OA\Parameter(
     *     name="id",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Get user search list",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionCheckNotification($id)
    {
        $model = User::findOne($id);
        Notification::create([
            'to_user_id' => $model->id,
            'created_by_id' => $model->id,
            'title' => \Yii::t('app', 'Your Book ' . $model->id . ' Has Been Added'),
            'model' => $model
        ], true);
    }

    /**
     * Api for store select language by user
     *
     * @OA\Post(path="/user/select-language",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="lang",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="get the jobs for the login user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionSelectLanguage($lang)
    {
        $data = [];
        $user = \Yii::$app->user->identity;
        $this->setStatus(400);
        $user->current_language = $lang;
        $user->updateAttributes([
            'current_language'
        ]);
        $this->setStatus(200);
        $data['message'] = User::getMessage('Updated Successfully');
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/book-lock",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="book_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="lock_state",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="get the jobs for the login user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionBookLock($book_id, $lock_state)
    {
        $data = [];
        $this->setStatus(400);
        $parentalcodeModel = ParentalControl::find()->where([
            'book_id' => $book_id
        ])->my()->one();

        if (empty($parentalcodeModel)) {
            $parentalcodeModel = new ParentalControl();
        }

        $parentalcodeModel->book_id = $book_id;

        $parentalcodeModel->lock = $lock_state;

        if (! $parentalcodeModel->save()) {

            $data['message'] = \Yii::t('app', $parentalcodeModel->getErrorsString());
            return $data;
        }


        $this->setStatus(200);

        $data['message'] = \Yii::t('app', 'Parental lock is ' . $parentalcodeModel->getLock() . ' successfully');

        return $data;
    }
}

