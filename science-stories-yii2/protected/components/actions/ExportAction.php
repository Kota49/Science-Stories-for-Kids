<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\components\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use app\components\helpers\TFileHelper;
use app\components\helpers\TStringHelper;

/**
 * Export action
 *
 * public function actions()
 * {
 * return [
 *
 * 'export' => [
 * 'class' => 'app\components\actions\ExportAction',
 * 'modelClass' => Gateway::class,
 * ]
 * ];
 * }
 */
class ExportAction extends Action
{

    /**
     *
     * @var string name of the model
     */
    public $modelClass;

    /**
     *
     * @var string pk field name
     */
    public $primaryKey = 'id';

    public $withRelations = true;

    /**
     * Run the action
     *
     * @param $id integer
     *            id of model to be loaded
     *            
     * @throws \yii\web\MethodNotAllowedHttpException
     * @throws \yii\base\InvalidConfigException
     * @return mixed
     */
    public function run($id)
    {
        if (empty($this->modelClass) || ! class_exists($this->modelClass)) {
            throw new InvalidConfigException("Model class doesn't exist");
        }
        /* @var $modelClass \yii\db\ActiveRecord */
        $modelClass = $this->modelClass;

        $model = $modelClass::find()->where([
            $this->primaryKey => $id
        ])->one();

        if (is_null($model)) {
            throw new NotFoundHttpException("Model  doesn't exist");
        }
        if (! $model->isAllowed()) {
            throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));
        }
        $file = TStringHelper::basename($modelClass) . '-' . $model->id . '-' . $model . '.json';
        $file = TFileHelper::cleanFilePath($file);
        $content = json_encode($model->asJson($this->withRelations));
        return Yii::$app->response->sendContentAsFile($content, $file);
    }
}
?>
