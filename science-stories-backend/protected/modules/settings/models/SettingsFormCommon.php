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
namespace app\modules\settings\models;

use app\components\helpers\TLogHelper;
use Yii;
use yii\base\Model;
use yii\helpers\StringHelper;

/**
 * ReplyForm is the model behind the reply form.
 */
class SettingsFormCommon extends Model
{
    use TLogHelper;

    public $enable = 1;

    /**
     *
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [
                [
                    'enable'
                ],
                'required'
            ]
        ];
    }

    /**
     *
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'enable' => \yii::t('app', 'Enable')
        ];
    }

    public function init()
    {
        parent::init();
        foreach ($this->attributes as $key => $value) {
            $this->$key = \Yii::$app->settings->getValue($key, $this->$key, $this->getModuleName());
        }
    }

    public function save()
    {
        foreach ($this->attributes as $key => $value) {
            \Yii::$app->settings->setValue($key, $value, $this->getModuleName());
        }
    }

    public function getFormAttributeId($attribute)
    {
        return $this->getModuleName() . $attribute;
    }

    public function getModuleName()
    {
        $moduleID = Yii::$app->controller->module->id;
        $modelClass = get_called_class();
        self::log('modelClass: ' . $modelClass);
        if (strstr($modelClass, 'modules')) {
            $moduleClassPath = StringHelper::dirname($modelClass);
            $modulePath = StringHelper::dirname($moduleClassPath);
            $moduleID = StringHelper::basename($modulePath);
        }
        self::log('moduleID: ' . $moduleID);
        return $moduleID;
    }
}
