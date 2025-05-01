<?php
/**
 *
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author     : Shiv Charan Panjeta < shiv@ozvid.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of OZVID Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\modules\notification\models;

use app\components\helpers\TArrayHelper;
use app\modules\settings\models\SettingsFormCommon;

/**
 * ReplyForm is the model behind the reply form.
 */
class SettingsForm extends SettingsFormCommon
{

    public $clearSentAfterHours = 3;

    public $alertCheckDelay = 5;

    /**
     *
     * @return array the validation rules.
     */
    public function rules()
    {
        return TArrayHelper::merge(parent::rules(), [
            // name, email, subject and body are required
            [
                [
                    'clearSentAfterHours',
                    'alertCheckDelay'
                ],
                'required'
            ],
            [
                [
                    'enable',
                    'alertCheckDelay',
                    'clearSentAfterHours'
                ],
                'integer'
            ]
        ]);
    }

    /**
     *
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return TArrayHelper::merge(parent::attributeLabels(), [
            'enable' => \yii::t('app', 'Enable Emails'),
            'clearSentAfterHours' => \yii::t('app', 'Clear Sent After Hours'),
            'alertCheckDelay' => \yii::t('app', 'Alert Check Delay')
        ]);
    }
}
