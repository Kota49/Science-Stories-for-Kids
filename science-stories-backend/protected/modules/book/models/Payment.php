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
namespace app\modules\book\models;

use Yii;
use app\models\Feed;
use app\modules\book\models\Book as Book;
use app\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_book_payment".
 *
 * @property integer $id
 * @property string $title
 * @property string $email
 * @property string $description
 * @property integer $book_id
 * @property string $amount
 * @property string $currency
 * @property string $transaction_id
 * @property string $payer_id
 * @property string $value
 * @property integer $gateway_type
 * @property integer $payment_status
 * @property integer $type_id
 * @property integer $state_id
 * @property integer $created_by_id
 * @property string $created_on
 * @property Book $book
 * @property User $createdBy
 */
class Payment extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->title;
    }

    public static function getBookOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
        // return self::listData ( Book::findActive ()->all () );
    }

    public static function getTransactionOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getTransaction()
    {
        $list = self::getTransactionOptions();
        return isset($list[$this->transaction_id]) ? $list[$this->transaction_id] : 'Not Defined';
    }

    public static function getPayerOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getPayer()
    {
        $list = self::getPayerOptions();
        return isset($list[$this->payer_id]) ? $list[$this->payer_id] : 'Not Defined';
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

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "New",
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

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (empty($this->created_by_id)) {
                $this->created_by_id = self::getCurrentUser();
            }
            if (empty($this->created_on)) {
                $this->created_on = \date('Y-m-d H:i:s');
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
        return '{{%book_payment}}';
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
                    'description',
                    'value'
                ],
                'string'
            ],
            [
                [
                    'book_id',
                    'currency',
                    'created_by_id',
                    'amount'
                ],
                'required'
            ],
            [
                [
                    'book_id',
                    'gateway_type',
                    'payment_status',
                    'type_id',
                    'state_id',
                    'created_by_id',
                    'transaction_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'amount',
                    'email'
                ],
                'safe'
            ],
            [
                [
                    'title',
                    'email',
                    'amount',
                    'payer_id'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'currency'
                ],
                'string',
                'max' => 125
            ],
            [
                [
                    'book_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => Book::class,
                'targetAttribute' => [
                    'book_id' => 'id'
                ]
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
                    'email',
                    'amount',
                    'transaction_id',
                    'payer_id',
                    'currency'
                ],
                'trim'
            ],
            [
                [
                    'email'
                ],
                'email'
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
            'email' => Yii::t('app', 'Email'),
            'description' => Yii::t('app', 'Description'),
            'book_id' => Yii::t('app', 'Book'),
            'amount' => Yii::t('app', 'Amount'),
            'currency' => Yii::t('app', 'Currency'),
            'transaction_id' => Yii::t('app', 'Transaction'),
            'payer_id' => Yii::t('app', 'Payer'),
            'value' => Yii::t('app', 'Value'),
            'gateway_type' => Yii::t('app', 'Gateway Type'),
            'payment_status' => Yii::t('app', 'Payment Status'),
            'type_id' => Yii::t('app', 'Type'),
            'state_id' => Yii::t('app', 'State'),
            'created_by_id' => Yii::t('app', 'Created By'),
            'created_on' => Yii::t('app', 'Created On')
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Detail::className(), [
            'id' => 'book_id'
        ])->cache();
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
        $relations['book_id'] = [
            'book',
            'Book',
            'id'
        ];
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
        $json['email'] = $this->email;
        $json['description'] = $this->description;
        $json['book_id'] = $this->book_id;
        $json['amount'] = $this->amount;
        $json['currency'] = $this->currency;
        $json['transaction_id'] = $this->transaction_id;
        $json['payer_id'] = ! empty($this->payer_id) ? $this->payer_id : "";
        $json['value'] = ! empty($this->value) ? $this->value : "";
        $json['gateway_type'] = ! empty($this->gateway_type) ? $this->gateway_type : 0;
        $json['payment_status'] = ! empty($this->payment_status) ? $this->payment_status : 0;
        $json['type_id'] = ! empty($this->type_id) ? $this->type_id : 0;
        $json['state_id'] = $this->state_id;
        $json['created_by_id'] = $this->created_by_id;
        $json['created_on'] = $this->created_on;
        if ($with_relations) {
            // book
            $list = $this->book;

            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['book'] = $relationData;
            } else {
                $json['book'] = $list;
            }
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
        return '/book/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->title = $faker->text(10);
            $model->email = $faker->email;
            $model->description = $faker->text;
            $model->book_id = 1;
            $model->amount = $faker->text(10);
            $model->currency = $faker->text(10);
            $model->transaction_id = 1;
            $model->payer_id = 1;
            $model->value = $faker->text;
            $model->gateway_type = $faker->text(10);
            $model->payment_status = $faker->text(10);
            $model->type_id = 0;
            $model->state_id = $states[rand(0, count($states))];
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

            $model->email = isset($item['email']) ? $item['email'] : $faker->email;

            $model->description = isset($item['description']) ? $item['description'] : $faker->text;

            $model->book_id = isset($item['book_id']) ? $item['book_id'] : 1;

            $model->amount = isset($item['amount']) ? $item['amount'] : $faker->text(10);

            $model->currency = isset($item['currency']) ? $item['currency'] : $faker->text(10);

            $model->transaction_id = isset($item['transaction_id']) ? $item['transaction_id'] : 1;

            $model->payer_id = isset($item['payer_id']) ? $item['payer_id'] : 1;

            $model->value = isset($item['value']) ? $item['value'] : $faker->text;

            $model->gateway_type = isset($item['gateway_type']) ? $item['gateway_type'] : $faker->text(10);

            $model->payment_status = isset($item['payment_status']) ? $item['payment_status'] : $faker->text(10);

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
            $model->state_id = self::STATE_ACTIVE;
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

    public static function EarningReport($role, $to_date, $from_date, $created_by_id = null, $dateAttribute = 'created_on')
    {
        $date = new \DateTime(date("Y-m-d", strtotime($to_date)));
        $datediff = strtotime($to_date) - strtotime($from_date);
        $modify = round($datediff / (60 * 60 * 24));
        $date->modify($modify . ' days');
        $count = [];
        for ($i = 0; $i <= - $modify; $i ++) {
            $date->modify('+1 days');
            $day = $date->format('Y-m-d');
            $day = date('Y-m-d', (strtotime('-1 day', strtotime($day))));
            $query = Payment::find()->where([
                'between',
                $dateAttribute,
                $to_date,
                $day
            ]);
            $query->where([
                'like',
                $dateAttribute,
                $day
            ]);
            if ($role !== null) {
                if ($role == User::ROLE_ADMIN) {
                    $earning = ! empty($query->sum('amount')) ? round($query->sum('amount'), 2) : 0;
                } elseif ($role == User::ROLE_HOST) {
                    $earning = ! empty($query->sum('amount')) ? round($query->sum('amount'), 2) : 0;
                } else {
                    $earning = ! empty($query->sum('amount')) ? round($query->sum('amount'), 2) : 0;
                }
            } else {
                $earning = ! empty($query->sum('amount')) ? round($query->sum('amount'), 2) : 0;
            }
            $count[$day] = $earning;
        }

        return $count;
    }

    public static function monthly($role = null, $created_by_id = null, $dateAttribute = 'created_on')
    {
        $date = new \DateTime(date('Y-m'));

        $date->modify('-1 year');

        $count = [];
        $query = self::find()->cache();
        for ($i = 1; $i <= 12; $i ++) {
            $date->modify('+1 months');
            $year = $date->format('Y');
            $month = $date->format('m');
            $mon = $date->format('M-Y');

            $query->where([
                "MONTH($dateAttribute)" => $month,
                "YEAR($dateAttribute)" => $year
            ]);
            if ($role !== null) {
                if ($role == User::ROLE_ADMIN) {
                    $earning = ! empty($query->sum('amount')) ? round($query->sum('amount'), 2) : 0;
                } elseif ($role == User::ROLE_HOST) {
                    $earning = ! empty($query->sum('amount')) ? round($query->sum('amount'), 2) : 0;
                } else {
                    $earning = ! empty($query->sum('amount')) ? round($query->sum('amount'), 2) : 0;
                }
            } else {
                $earning = ! empty($query->sum('amount')) ? round($query->sum('amount'), 2) : 0;
            }

            $count[$mon] = $earning;
        }
        return $count;
    }
}
