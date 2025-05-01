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
namespace app\components;

use Yii;
use app\components\helpers\TLogHelper;

/**
 *
 * {@inheritdoc}
 */
class TBaseWidget extends \yii\base\Widget
{
    use TLogHelper;

    public $options = [];

    public $route;

    public $params;

    public $visible;

    public function init()
    {
        parent::init();
        if (! isset($this->visible)) {
            $this->visible = true;
        }
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
    }

    public function run()
    {

        if ($this->visible) {
            return $this->renderHtml();
        }
    }

    public function renderHtml()
    {}
}
