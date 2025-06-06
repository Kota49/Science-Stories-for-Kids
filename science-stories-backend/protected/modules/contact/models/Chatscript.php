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
namespace app\modules\contact\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "tbl_contact_chatscript".
 *
 * @property integer $id
 * @property string $title
 * @property string $domain
 * @property string $script_code
 * @property integer $contact_link
 * @property integer $show_bubble
 * @property integer $popup_delay
 * @property string $chat_server
 * @property integer $role_id
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property User $createdBy
 */
class Chatscript extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->title;
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "Deactivate",
            self::STATE_ACTIVE => "Active",
            self::STATE_DELETED => "Deleted"
        ];
    }

    public function getState()
    {
        $list = self::getStateOptions();
        return isset($list[$this->state_id]) ? $list[$this->state_id] : 'Not Defined';
    }

    public function getStateBadge()
    {
        $list = [
            self::STATE_INACTIVE => "secondary",
            self::STATE_ACTIVE => "success",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getActionOptions()
    {
        return [
            self::STATE_INACTIVE => "Deactivate",
            self::STATE_ACTIVE => "Activate",
            self::STATE_DELETED => "Delete"
        ];
    }

    public static function getTypeOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (empty($this->created_on)) {
                $this->created_on = \date('Y-m-d H:i:s');
            }
            if (empty($this->created_by_id)) {
                $this->created_by_id = self::getCurrentUser();
            }
            if (empty($this->popup_delay)) {
                $this->popup_delay = 15;
            }
        } else {}
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contact_chatscript}}';
    }

    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'title',
                    'script_code',
                    'created_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'contact_link',
                    'show_bubble',
                    'popup_delay',
                    'state_id',
                    'type_id',
                    'role_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'title',
                    'domain'
                ],
                'string',
                'max' => 64
            ],
            [
                [
                    'script_code'
                ],
                'string',
                'max' => 1024
            ],
            [
                [
                    'chat_server'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'created_by_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => [
                    'created_by_id' => 'id'
                ]
            ],
            [
                [
                    'title',
                    'domain',
                    'script_code',
                    'chat_server'
                ],
                'trim'
            ],
            [
                [
                    'state_id'
                ],
                'in',
                'range' => array_keys(self::getStateOptions())
            ],
            [
                [
                    'type_id'
                ],
                'in',
                'range' => array_keys(self::getTypeOptions())
            ]
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'domain' => Yii::t('app', 'Domain'),
            'script_code' => Yii::t('app', 'Script Code'),
            'contact_link' => Yii::t('app', 'Contact Link'),
            'show_bubble' => Yii::t('app', 'Show Bubble'),
            'popup_delay' => Yii::t('app', 'Popup Delay'),
            'chat_server' => Yii::t('app', 'Chat Server'),
            'role_id' => Yii::t('app', 'Role'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'Created By')
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), [
            'id' => 'created_by_id'
        ])->cache();
    }
    
    public function getRole()
    {
        $list = User::getRoleOptions();
        return isset($list[$this->role_id]) ? $list[$this->role_id] : 'Not Defined';
    }

    public static function getHasManyRelations()
    {
        $relations = [];

        $relations['feeds'] = [
            'feeds',
            'Feed',
            'model_id'
        ];
        return $relations;
    }

    public static function getHasOneRelations()
    {
        $relations = [];
        $relations['created_by_id'] = [
            'createdBy',
            'User',
            'id'
        ];
        return $relations;
    }

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }
        // TODO : start here

        return true;
    }

    public function beforeSave($insert)
    {
        if (! parent::beforeSave($insert)) {
            return false;
        }
        // TODO : start here

        return true;
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['title'] = $this->title;
        $json['domain'] = $this->domain;
        $json['script_code'] = $this->script_code;
        $json['contact_link'] = $this->contact_link;
        $json['show_bubble'] = $this->show_bubble;
        $json['popup_delay'] = $this->popup_delay;
        $json['chat_server'] = $this->chat_server;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {
            // createdBy
            $list = $this->createdBy;

            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['createdBy'] = $relationData;
            } else {
                $json['createdBy'] = $list;
            }
        }
        return $json;
    }

    public function getControllerID()
    {
        return '/contact/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->title = $faker->text(10);
            $model->domain = $faker->text(10);
            $model->script_code = $faker->text(10);
            $model->contact_link = $faker->text(10);
            $model->show_bubble = $faker->text(10);
            $model->popup_delay = $faker->text(10);
            $model->chat_server = $faker->text(10);
            $model->state_id = $states[rand(0, count($states))];
            $model->type_id = 0;
            $model->save();
        }
    }

    public static function addData($data)
    {
        if (self::find()->count() != 0) {
            return;
        }

        $faker = \Faker\Factory::create();
        foreach ($data as $item) {
            $model = new self();
            $model->loadDefaultValues();

            $model->title = isset($item['title']) ? $item['title'] : $faker->text(10);

            $model->domain = isset($item['domain']) ? $item['domain'] : $faker->text(10);

            $model->script_code = isset($item['script_code']) ? $item['script_code'] : $faker->text(10);

            $model->contact_link = isset($item['contact_link']) ? $item['contact_link'] : $faker->text(10);

            $model->show_bubble = isset($item['show_bubble']) ? $item['show_bubble'] : $faker->text(10);

            $model->popup_delay = isset($item['popup_delay']) ? $item['popup_delay'] : $faker->text(10);

            $model->chat_server = isset($item['chat_server']) ? $item['chat_server'] : $faker->text(10);
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
            $model->save();
        }
    }

    public function isAllowed()
    {
        if (User::isAdmin())
            return true;
        if ($this->hasAttribute('created_by_id') && $this->created_by_id == Yii::$app->user->id) {
            return true;
        }

        return User::isUser();
    }

    public function afterSave($insert, $changedAttributes)
    {
        return parent::afterSave($insert, $changedAttributes);
    }

    public static function loadFromSEO()
    {
        $class = '\app\modules\seo\models\Chatscript';
        if (class_exists($class)) {

            $query = $class::findActive();
            foreach ($query->each() as $q) {
                $model = new self();
                $model->loadDefaultValues();
                $model->title = $q->title;
                $model->domain = $q->domain;
                $model->script_code = $q->script_code;
                $model->save();
            }
        }

        return self::findActive()->orderBy('id DESC')->one();
    }
}
