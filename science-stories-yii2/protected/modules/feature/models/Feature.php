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
namespace app\modules\feature\models;

use app\models\User;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_feature".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $type_id
 * @property integer $state_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 * @property Vote[] $votes
 */
class Feature extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->title;
    }

    public static function getTypeOptions()
    {
        return self::listData(Type::findActive()->all());
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    const STATE_DELETED = 0;

    const STATE_ACTIVE = 1;

    const STATE_INACTIVE = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "New",
            self::STATE_ACTIVE => "Active",
            self::STATE_DELETED => "Archived"
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
            self::STATE_INACTIVE => "primary",
            self::STATE_ACTIVE => "success",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (! isset($this->created_on))
                $this->created_on = date('Y-m-d H:i:s');

            if (! isset($this->created_by_id))
                $this->created_by_id = self::getCurrentUser();
        }
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%feature}}';
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
                    'created_on',
                    'created_by_id',
                    'icon',
                    'type_id',
                    'state_id',
                    'summary'
                ],
                'required'
            ],
            [
                [
                    'description'
                ],
                'string'
            ],
            [
                [
                    'type_id',
                    'state_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',

                    'order_id'
                ],
                'safe'
            ],
            [
                [
                    'title',
                    'icon',
                    'summary'
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
                'targetClass' => User::class,
                'targetAttribute' => [
                    'created_by_id' => 'id'
                ]
            ],
            [
                [
                    'title',
                    'summary'
                ],
                'trim'
            ],
            [
                [
                    'type_id'
                ],
                'in',
                'range' => array_keys(self::getTypeOptions())
            ],
            [
                [
                    'state_id'
                ],
                'in',
                'range' => array_keys(self::getStateOptions())
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
            'summary' => Yii::t('app', 'Summary'),
            'description' => Yii::t('app', 'Description'),
            'type_id' => Yii::t('app', 'Type'),
            'state_id' => Yii::t('app', 'State'),
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
        return $this->hasOne(User::class, [
            'id' => 'created_by_id'
        ])->cache();
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(Vote::class, [
            'feature_id' => 'id'
        ]);
    }

    public static function getHasManyRelations()
    {
        $relations = [];
        $relations['Votes'] = [
            'votes',
            'Vote',
            'id',
            'feature_id'
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
        Vote::deleteRelatedAll([
            'feature_id' => $this->id
        ]);
        return true;
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['title'] = $this->title;
        $json['description'] = $this->description;
        $json['type_id'] = $this->type_id;
        $json['state_id'] = $this->state_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {
            // createdBy
            $list = $this->createdBy;

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['createdBy'] = $relationData;
            } else {
                $json['CreatedBy'] = $list;
            }
            // votes
            $list = $this->votes;

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['votes'] = $relationData;
            } else {
                $json['Votes'] = $list;
            }
        }
        return $json;
    }

    public function getImageUrl($thumbnail = false)
    {
        $params = [
            'feature/' . $this->getControllerID() . '/icon'
        ];
        $params['id'] = $this->id;

        return Yii::$app->getUrlManager()->createAbsoluteUrl($params);
    }

    public function getControllerID()
    {
        return '/feature/' . parent::getControllerID();
    }

    public function getVoteButton()
    {
        $count = 0;
        $button = 'primary';
        $vote = 'Vote Now';
        $query = Vote::find()->where([
            'feature_id' => $this->id
        ]);
        $count = $query->count();
        $query->andWhere([
            'created_by_id' => Yii::$app->user->id
        ]);
        if (! empty($query->one())) {
            $button = 'success';
            $vote = 'Voted';
        }

        return Html::a($vote . " [ $count ] ", $this->getUrl('voted-unvoted'), [
            'class' => "btn btn-$button"
        ]);
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $types = array_keys(self::getTypeOptions());

        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->title = $faker->text(14);
            $model->type_id = $types[rand(0, count($types) - 1)];
            $model->state_id = 1;
            $model->description = $faker->text;
            $model->summary = $faker->text;
            $model->created_by_id = User::getCurrentUser();
            $model->save();
        }
    }

    public static function addData($data)
    {
        $faker = \Faker\Factory::create();
        if (self::find()->count() != 0)
            return;
        $type = Type::findActive()->one();
        foreach ($data as $item) {
            $model = new self();
            $model->title = isset($item['title']) ? $item['title'] : $faker->text(14);
            $model->type_id = isset($type->id) ? $type->id : 1;
            $model->icon = isset($item['icon']) ? $item['icon'] : $faker->text(10);
            $model->state_id = self::STATE_ACTIVE;
            $model->description = isset($item['description']) ? $item['description'] : $faker->text(1000);
            $model->summary = isset($item['summary']) ? $item['summary'] : $faker->text(150);
            $model->created_by_id = User::getCurrentUser();
            $model->save();
        }
    }
}
