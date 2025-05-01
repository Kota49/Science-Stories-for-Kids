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
namespace app\components\gdpr;

use app\components\TBaseWidget;
use app\components\gdpr\assets\GdprAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Cookie;
use yii\helpers\VarDumper;
use app\models\User;

/**
 * Generate the privacy policies links and html
 */
class Gdpr extends TBaseWidget
{

    /**
     *
     * @param string $name
     */
    private $name = null;

    /**
     *
     * @param string $privacylink
     * @var string link for privacy
     */
    public $privacylink = '/site/privacy';

    /**
     *
     * @param string $description
     */
    public $description = "We use cookies, check our {privacy}.";

    /**
     *
     * {@inheritdoc}
     * @see \app\components\TBaseWidget::init()
     */
    public function init()
    {
        parent::init();
        $this->name = "gdpr_" . \Yii::$app->id;
        $cookies = \Yii::$app->request->cookies;

        self::log(' All cookies' . VarDumper::dumpAsString($cookies->toArray()));

        $isSet = $cookies->getValue($this->name);

        \Yii::info($this->name . ' : Coockie: ' . $isSet);
        if (YII_ENV == 'dev' || ! User::isGuest() || $isSet) {
            $this->visible = false;
        } else {
            GdprAsset::register(\Yii::$app->getView());

            $this->description = str_replace('{privacy}', Html::a('Privacy Policies', Url::toRoute([
                $this->privacylink
            ])), $this->description);
        }
        \Yii::info($this->name . ' : $this->visible: ' . $this->visible);
        $post = \Yii::$app->request->post('accept');

        if ($this->visible && $post) {
            $cookie = new Cookie([
                'name' => $this->name,
                'value' => $post,
                'expire' => time() + 86400 * 365,
                // 'domain' => isset(\Yii::$app->params['domain']) ? \Yii::$app->params['domain'] : \Yii::$app->request->hostName,
                'path' => \Yii::$app->request->baseUrl
            ]);
            \Yii::$app->response->cookies->add($cookie);
            \Yii::$app->controller->redirect(\Yii::$app->request->referrer);
        }
    }

    /**
     * return the html of gdpr
     */
    public function renderHtml()
    {
        if ($this->visible) {
            echo $this->render('gdpr', [
                'description' => $this->description
            ]);
        }
    }
}
