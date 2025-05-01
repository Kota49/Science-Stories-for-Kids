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
namespace app\components;

use Yii;
use app\models\User;
use yii\helpers\VarDumper;
use yii\web\HttpException;
use app\components\helpers\TStringHelper;
use yii\rest\ActiveController;

/**
 *
 * @see \yii\web\User
 *
 */
class WebUser extends \yii\web\User
{

    private $_company_id = null;

    private $_modeAdmin = false;

    public $authKeyParam = '__key';

    public $enableAutoLogin = true;

    public $identityClass = 'app\models\User';

    public $loginUrl = [
        '/user/login'
    ];

    public $authTimeout = 86400;

    /**
     *
     * @see \yii\web\User::init()
     *
     */
    public function init()
    {
        if (preg_match('/\/api\/(?!(default|access-token))/i', Yii::$app->request->url)) {
            // self::log('controller =>' . VarDumper::dumpAsString(Yii::$app->controller->id));
            // if (Yii::$app->controller && Yii::$app->controller instanceof ActiveController) {

            // TODO: find better way to ensure its apis
            $this->enableSession = false;
            $this->loginUrl = null;
            $this->enableAutoLogin = false;
            \Yii::$app->request->enableCsrfValidation = false;
        }
        parent::init();
        $cookiePath = '/';
        $path = \Yii::$app->request->baseUrl;
        if (! empty($path)) {
            $cookiePath = $path;
        }
        $this->identityCookie['name'] = '_user_' . \Yii::$app->id;
        $this->identityCookie['path'] = $cookiePath;
    }

    public function afterLogin($identity, $cookieBased, $duration)
    {
        
         $identity->last_visit_time = date('Y-m-d H:i:s');
          $identity->updateAttributes([
          'last_visit_time'
          ]);
        
        $this->updateCompany($identity);
        $this->setIsAdminMode();
        return parent::afterLogin($identity, $cookieBased, $duration);
    }

    /**
     *
     * @param
     *            identity
     */
    private function updateCompany($identity)
    {
        if ($identity->hasAttribute('company_id')) {
            $this->setCompany($identity->company_id);
        }
    }

    public function afterLogout($identity)
    {
        // $this->cleanupCookies();
    }

    public function getIsAdminMode()
    {
        if ($this->enableSession) {
            $this->_modeAdmin = \Yii::$app->session->get('ADMIN_MODE', false);
        }
        return $this->_modeAdmin;
    }

    public function setIsAdminMode($mode = false)
    {
        if ($this->enableSession) {
            $this->_modeAdmin = $mode;
            \Yii::$app->session->set('ADMIN_MODE', $mode);
        }
    }

    public function cleanupCookies()
    {
        $past = time() - 3600;
        foreach ($_COOKIE as $key => $value) {
            setcookie($key, false, $past, '/');
        }
    }

    public function can($permissionName, $params = [], $allowCaching = true)
    {
        return parent::can($permissionName, $params, $allowCaching);
    }

    public function canRoute($module, $route = null, $allowCaching = true, $defaultValue = false)
    {
        if (($accessChecker = $this->getAuthAccessChecker()) === false) {
            return $defaultValue;
        }
        if ($this->isGuest) {
            \Yii::info("Guest " . $defaultValue);
            return $defaultValue;
        }
        return $accessChecker->canRoute($module, $route, $allowCaching, $defaultValue);
    }

    public function getAuthAccessChecker()
    {
        if (($accessChecker = $this->getAccessChecker()) === null) {
            return false;
        }

        return $accessChecker;
    }

    public function switchIdentity($identity, $duration = 0)
    {
        parent::switchIdentity($identity, $duration);

        if ($this->enableSession && $identity) {
            $session = Yii::$app->getSession();
            $session->set($this->authKeyParam, $identity->getAuthKey());

            $this->updateCompany($identity);
        }
    }

    protected function renewAuthStatus()
    {
        $session = Yii::$app->getSession();
        $id = $session->getHasSessionId() || $session->getIsActive() ? $session->get($this->idParam) : null;

        if ($id === null) {
            $identity = null;
        } else {
            /* @var $class User */
            $class = $this->identityClass;
            $identity = $class::findIdentity($id);
        }

        $this->setIdentity($identity);

        if ($identity !== null && ($this->authTimeout !== null || $this->absoluteAuthTimeout !== null)) {
            $expire = $this->authTimeout !== null ? $session->get($this->authTimeoutParam) : null;
            $expireAbsolute = $this->absoluteAuthTimeout !== null ? $session->get($this->absoluteAuthTimeoutParam) : null;
            if ($expire !== null && $expire < time() || $expireAbsolute !== null && $expireAbsolute < time()) {
                $this->logout(false);
            } elseif ($this->authTimeout !== null) {
                $session->set($this->authTimeoutParam, time() + $this->authTimeout);
            }
        }

        if ($this->enableAutoLogin) {
            if ($this->getIsGuest()) {
                $this->loginByCookie();
            } elseif ($this->autoRenewCookie) {

                $id = Yii::$app->session->get("shadow");

                if ($id != null) {
                    $this->renewIdentityCookie();
                    Yii::info("User $id succeeded shadow in progress");
                    return;
                }

                $this->renewIdentityCookie();
                Yii::info("User $id succeeded authKey validation");
            }
        }

        if ($identity !== null) {
            $authKey = $session->get($this->authKeyParam);

            if ($authKey !== null && ! $identity->validateAuthKey($authKey)) {
                $this->logout();
                Yii::info("User $id failed authKey validation");
            }
        }
    }

    public function getUserName()
    {
        if ($this->isGuest) {
            return 'Guest';
        }
        return $this->identity;
    }

    public function getCompany()
    {
        $this->_company_id = \Yii::$app->session->get('COMPANY_ID', null);
        Yii::info(__FUNCTION__ . ' company_id=' . $this->_company_id);
        return $this->_company_id;
    }

    public function setCompany($id = null)
    {
        Yii::info(__FUNCTION__ . ' company_id=' . $id);
        $this->_company_id = $id;
        \Yii::$app->session->set('COMPANY_ID', $id);
    }
}
