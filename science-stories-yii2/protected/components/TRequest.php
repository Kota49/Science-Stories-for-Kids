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

use yii\web\Request;
use app\components\helpers\TArrayHelper;
use app\components\helpers\TLogHelper;
use yii\helpers\VarDumper;
use app\components\helpers\TipHelper;

/**
 *
 * @see yii\web\Request
 *
 */
class TRequest extends Request
{
    use TLogHelper;

    public $parsers = [
        'application/json' => 'yii\web\JsonParser'
    ];

    /**
     *
     * @see Request::init()
     *
     */
    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = defined('YII_TEST') ? false : true;
        $this->cookieValidationKey .= md5(\Yii::$app->id . $_SERVER['SERVER_ADDR']);
        $this->csrfParam = '_csrf_' . \Yii::$app->id;
        $path = $this->baseUrl;
        if (! empty($path)) {
            $this->csrfCookie['path'] = $path;
        }
        if (getenv('USING_DOCKER')) {

            $docker_netwpork = TipHelper::getNetworkFromIP();
            $this->trustedHosts = TArrayHelper::merge($this->trustedHosts, $docker_netwpork);
            self::log('trustedHosts : ' . VarDumper::dumpAsString($this->trustedHosts));
            $this->trustedHosts = array_unique($this->trustedHosts);
        }
    }
}
