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
namespace app\modules\feature\controllers;

use app\components\TController;
use app\modules\feature\models\Update;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ContentController implements the CRUD actions for Content model.
 */
class ApiController extends TController
{

    public $enableCsrfValidation = false;

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
                            'get-update'
                        ],
                        'allow' => true,
                        'roles' => [
                            '@',
                            '?',
                            '*'
                        ]
                    ]
                ]
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'delete' => [
                        'post'
                    ]
                ]
            ]
        ];
    }

    /**
     * Displays a single Content model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionGetUpdate()
    {
        Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel();
        return $model;
    }

    protected function findModel()
    {
        $model = Update::findActive()->one();
        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model;
    }
}
