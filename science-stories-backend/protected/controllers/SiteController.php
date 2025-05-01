<?php

/**
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author	 : Shiv Charan Panjeta < shiv@ozvid.com >
 */
namespace app\controllers;

use app\components\TController;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\User;
use Yii;
use app\modules\page\models\Page;

class SiteController extends TController
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
                            'about',
                            'error',
                            'demo',
                            'pricing',
                            'privacy',
                            'terms',
                            'captcha'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                        return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'contact'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                        return User::isUser() || User::isGuest();
                        }
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
            ]
        ];
    }

    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        return $this->render('error', [
            'message' => $exception->getMessage(),
            'name' => 'Error'
        ]);
    }

    public function actionIndex()
    {
        $this->updateMenuItems();
        if (! \Yii::$app->user->isGuest) {
            return $this->redirect('dashboard/index');
        } else {
            return $this->render('index');
        }
    }

    public function actionContact()
    {
        return $this->render('contact');
    }

    public function actionAbout()
    {
        $about = Page::getPageDetails(Page::TYPE_ABOUT_US);

        return $this->render('about', [
            'about' => $about
        ]);
    }

    public function actionFeatures()
    {
        return $this->render('features');
    }

    public function actionPricing()
    {
        return $this->render('pricing');
    }

    public function actionPrivacy()
    {
        $privacy = Page::getPageDetails(Page::TYPE_PRIVACY);
        
        return $this->render('privacy', [
            'privacy' => $privacy
        ]);
    }

    public function actionTerms()
    {
        $terms = Page::getPageDetails(Page::TYPE_TERM_CONDITION);
        
        return $this->render('terms', [
            'terms' => $terms
        ]);
    }

    protected function updateMenuItems($model = null)
    {
        // create static model if model is null
        switch ($this->action->id) {
            case 'add':
                {
                    $this->menu[] = array(
                        'label' => Yii::t('app', 'Manage'),
                        'url' => array(
                            'index'
                        ),
                        'visible' => User::isAdmin()
                    );
                }
                break;
            default:
            case 'view':
                {
                    $this->menu[] = array(
                        'label' => '<span class="glyphicon glyphicon-list"></span> Manage',
                        'title' => 'Manage',
                        'url' => array(
                            'index'
                        ),
                        'visible' => User::isAdmin()
                    );

                    if ($model != null)
                        $this->menu[] = array(
                            'label' => Yii::t('app', 'Update'),
                            'url' => array(
                                'update',
                                'id' => $model->id
                            ),
                            'visible' => ! User::isAdmin()
                        );
                }
                break;
        }
    }
}
