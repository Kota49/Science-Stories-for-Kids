<?php

/**
 *
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author     : Shiv Charan Panjeta < shiv@ozvid.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of OZVID Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\base;

use app\models\User;
use Yii;
use yii\web\Controller;
use app\components\EmailVerification;

abstract class TBaseController extends Controller
{
    use SideBarMenu;

    public $allowedIPs = [
        '127.0.0.1',
        '::1',
        '192.168.*.*'
    ];

    public $layout = '//guest-main';

    public $menu = [];

    public $top_menu = [];

    public $side_menu = [];

    public $user_menu = [];

    public $tabs_data = null;

    public $tabs_name = null;

    public $dryRun = false;

    public $assetsDir = '@webroot/assets';

    public $ignoreDirs = [];

    public $nav_left = [];

    protected $_author = '@toxsltech';

    // nav-left-medium';
    protected $_pageCaption;

    protected $_pageDescription="The main objective of this project is to design and develop the multilingual Science Stories for Kids Mobile app for both Android and iOS platforms, providing an immersive educational experience for children through illustrated science-themed stories. The platform will incorporate static JPEG illustrations, narration, audio effects, and interactive elements. Customer can search, like, download it in offline mode, purchase and gift them to others.";

    protected $_pageKeywords;

    public function beforeAction($action)
    {
        if (! parent::beforeAction($action)) {
            return false;
        }
        if (! Yii::$app->user->isGuest && ! User::isAdmin()) {
            EmailVerification::checkIfVerified();
        }
        if (! \Yii::$app->user->isGuest) {
            $this->layout = 'main';
        }
        return true;
    }
    
    public function redirectBack()
    {
        $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }
}

