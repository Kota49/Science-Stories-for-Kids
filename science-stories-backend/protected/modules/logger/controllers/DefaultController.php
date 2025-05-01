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
namespace app\modules\logger\controllers;

use app\components\TController;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\User;
use app\modules\logger\models\Log;
use app\modules\logger\models\SettingsForm;
use Yii;
use yii\helpers\FileHelper;

/**
 * Default controller for the `log` module
 */
class DefaultController extends TController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className()
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'settings',
                            'info',
                            'toggle-env',
                            'php-info',
                            'clear'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ]
                ]
            ]
        ];
    }

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSettings()
    {
        $model = new SettingsForm();

        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            if ($model->validate() && $model->save()) {

                return $this->redirect([
                    'settings'
                ]);
            }
        }
        return $this->render('settings', [

            'model' => $model
        ]);
    }

    /**
     * get PhpInfo
     */
    public function actionPhpInfo()
    {
        ob_start();
        phpinfo();
        $pinfo = ob_get_contents();
        ob_end_clean();
        echo $pinfo;
        exit();
    }

    /**
     * Renders the System Info view
     *
     * @return string
     */
    public function actionInfo()
    {
        $info['Generic'] = [
            'App Name' => \Yii::$app->name,
            'App ID' => PROJECT_ID,
            // 'App Version' => VERSION,
            'Environment' => YII_ENV,
            'Company Name' => \Yii::$app->params['company']
            // 'Domain' => Yii::$app->params['domain']
        ];
        return $this->render('info', [
            'model' => $info
        ]);
    }

    /**
     * Toggle Env
     *
     * @return \yii\web\Response
     */
    public function actionToggleEnv()
    {
        Log::toggleEnv();
        \Yii::$app->getSession()->setFlash("success", 'Env Change');
        return $this->redirect([
            'info'
        ]);
    }
    
    public function actionClear()
    {
        $this->cleanRuntimeDir(true);
        $this->cleanAssetsDir();
        exit();
    }
}
