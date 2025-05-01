<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\feature\widgets;

use app\components\TBaseWidget;
use function GuzzleHttp\json_decode;

/**
 * This is just an example.
 */
class RemoteFeature extends TBaseWidget
{

    public $model;

    public $readOnly = false;

    public $disabled = false;

    public $remoteUrl;

    // public $baseApiUrl = "https://jischoolerp.com/feature/api/";
    public $baseApiUrl = (YII_ENV == 'dev') ? "http://localhost/jischool-frontend-647/feature/api/" : "https://jischoolerp.com/feature/api/";

    public function init()
    {
        parent::init();
        $this->remoteUrl = $this->baseApiUrl . "get-update";
        \app\modules\feature\assets\RemoteFeatureAssets::register(\Yii::$app->getView());
    }

    public function run()
    {
        if ($this->disabled)
            return; // Do nothing
        
        $features = file_get_contents($this->remoteUrl);
        
        if (empty($features))
            return;
        $features = json_decode($features, true);
        return $this->render('feature_content', [
            'model' => $features
        ]);
    }
}
