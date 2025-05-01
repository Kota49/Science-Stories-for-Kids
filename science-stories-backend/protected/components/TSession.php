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

use yii\web\Session;
use yii\web\Cookie;

/**
 * 
 * @see yii\web\Session
 *
 */
class TSession extends Session
{

    /**
     * @see Session::init()
     */
    public function init()
    {
        $cookiePath = '/';
        $path = \Yii::$app->request->baseUrl;
        if (! empty($path)) {
            $cookiePath = $path;
        }

        $this->setCookieParams([
            'httponly' => true,
            'path' => $cookiePath,
            'sameSite' => Cookie::SAME_SITE_LAX
        ]);
        $this->name = '_session_' . \Yii::$app->id;
        $savePath = \Yii::$app->runtimePath . DIRECTORY_SEPARATOR . 'sessions';
        if (! is_dir($savePath)) {
            @mkdir($savePath, FILE_MODE, true);
        }
        $this->savePath = $savePath;
        parent::init();
    }
}
