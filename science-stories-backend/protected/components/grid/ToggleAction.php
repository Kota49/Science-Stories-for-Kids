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
namespace app\components\grid;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

/**
 * Toggle action
 *
 */
class ToggleAction extends Action
{

    /**
     *
     * @var string name of the model
     */
    public $modelClass;

    /**
     *
     * @var string model attribute
     */
    public $attribute = 'state_id';

    /**
     *
     * @var string scenario model
     */
    public $scenario = null;

    /**
     *
     * @var string|array additional condition for loading the model
     */
    public $andWhere = null;

    /**
     *
     * @var string|int|boolean what to set active models to
     */
    public $onValue = 1;

    /**
     *
     * @var string|int|boolean what to set inactive models to
     */
    public $offValue = 0;

    /**
     *
     * @var bool whether to set flash messages or not
     */
    public $setFlash = false;

    /**
     *
     * @var string flash message on success
     */
    public $flashSuccess = "Model saved";

    /**
     *
     * @var string flash message on error
     */
    public $flashError = "Error saving Model";

    /**
     *
     * @var string|array URL to redirect to
     */
    public $redirect;

    /**
     *
     * @var string pk field name
     */
    public $primaryKey = 'id';

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
    public function run($id, $attribute)
    {
        if (! Yii::$app->request->getIsPost()) {
            throw new MethodNotAllowedHttpException();
        }
        $id = (int) $id;

        if (empty($this->modelClass) || ! class_exists($this->modelClass)) {
            throw new InvalidConfigException("Model class doesn't exist");
        }
        /* @var $modelClass \yii\db\ActiveRecord */
        $modelClass = $this->modelClass;

        $attribute = isset($attribute) ? $attribute : $this->attribute;
        $model = $modelClass::find()->where([
            $this->primaryKey => $id
        ]);

        if (! empty($this->andWhere)) {
            $model->andWhere($this->andWhere);
        }

        $model = $model->one();
        if (is_null($model)) {
            throw new NotFoundHttpException("Model  doesn't exist");
        }
        if (! is_null($this->scenario))
            $model->scenario = $this->scenario;

        if (! $model->hasAttribute($this->attribute)) {
            throw new InvalidConfigException("Attribute doesn't exist");
        }

        if ($model->$attribute == $this->onValue) {
            $model->$attribute = $this->offValue;
        } else {
            $model->$attribute = $this->onValue;
        }

        if ($model->save()) {
            if ($this->setFlash) {
                Yii::$app->session->setFlash('success', $this->flashSuccess);
            }
        } else {
            var_dump($model->errors);
            if ($this->setFlash) {
                Yii::$app->session->setFlash('error', $this->flashError);
            }
        }
        
        if (Yii::$app->request->getIsAjax()) {
            Yii::$app->end();
        }
        Yii::$app->end();
        /* @var $controller \yii\web\Controller */
        $controller = $this->controller;
        if (! empty($this->redirect)) {
            return $controller->redirect($this->redirect);
        }
        return $controller->redirect(Yii::$app->request->getReferrer());
    }
}
