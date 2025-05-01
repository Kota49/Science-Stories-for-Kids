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

use app\components\TController;
use Yii;
use yii\web\Response;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\User;

/**
 * Default controller for the `Api` module
 */
class DefaultController extends TController
{

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::className()
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'json'
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

    public function actions()
    {
        return [
            'index' => [
                'class' => 'light\swagger\SwaggerAction',
                'restUrl' => \yii\helpers\Url::to([
                    '/api/default/json'
                ], true)
            ],
            'json' => [
                'class' => 'light\swagger\SwaggerApiAction',
                'scanDir' => [
                   Yii::getAlias('@app/modules/api/controllers'),
                    Yii::getAlias('@app/modules/api/models'),
                    Yii::getAlias('@app/models'),
                    Yii::getAlias('@app/modules/logger/controllers'),
                    Yii::getAlias('@app/modules/book/api/controllers')
                    
                ]
            ]
        ];
    }

    public function beforeAction($action)
    {
        if (! parent::beforeAction($action)) {
            return false;
        }

        $this->layout = 'guest-main';

        \Yii::$app->response->format = Response::FORMAT_HTML;

        return true;
    }
}
