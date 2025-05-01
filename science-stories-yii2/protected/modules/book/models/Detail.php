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
use app\modules\book\models\Audio as Audio;
use app\modules\book\models\Category as Category;
use app\models\User;
use app\modules\page\models\Page as Page;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\modules\rating\models\Rating;

/**
 * This is the model class for table "tbl_book".
 *
 * @property integer $id
 * @property string $title
 * @property integer $category_id
 * @property string $description
 * @property integer $image_file
 * @property string $age
 * @property string $price
 * @property integer $type_id
 * @property integer $state_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property Audio[] $audios
 * @property Category $category
 * @property User $createdBy
 * @property Page[] $pages
 */
class Detail extends \app\components\TActiveRecord
{

    const PAID = 1;

    const FREE = 2;

    public function __toString()
    {
        return (string) $this->title;
    }

    public static function getCategoryOptions()
    {
        return self::listData(Category::findActive()->all());
    }

    public static function getTypeOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public static function getAgeOptions()
    {
        return [
            "0-3",
            "4-8",
            "9+"
        ];
    }

    public static function getPriceOptions()
    {
        return [
            self::PAID => "Paid",
            self::FREE => "Free"
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public function getAge()
    {
        $list = self::getAgeOptions();
        return isset($list[$this->age]) ? $list[$this->age] : 'Not Defined';
    }

    public function getPrice()
    {
        $list = self::getPriceOptions();
        return isset($list[$this->price_id]) ? $list[$this->price_id] : 'Not Defined';
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

    public static function findIdentity($id)
    {
        return static::findOne([
            'id' => $id,
            'state_id' => self::STATE_ACTIVE
        ]);
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
            if (empty($this->created_on)) {
                $this->created_on = \date('Y-m-d H:i:s');
            }
            if (empty($this->created_by_id)) {
                $this->created_by_id = self::getCurrentUser();
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
        return '{{%book}}';
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
                    'category_id',
                    'created_on',
                    'created_by_id',
                    'price_id',
                    'age',
                    'title',
                    'author_name'
                ],
                'required'
            ],
            [
                [
                    'price'
                ],
                'required',
                'when' => function ($model) {
                    return ($model->price_id == Detail::STATE_INACTIVE);
                }
            ],
            [
                [
                    'category_id',
                    'type_id',
                    'state_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'price'
                ],
                'number',
                'min' => 1,
                'when' => function ($model) {
                    return $model->price_id == Detail::PAID;
                }
            ],
            [
                [
                    'description',
                    'author_name'
                ],
                'string'
            ],
            [
                [
                    'created_on',
                    'image_file'
                ],
                'safe'
            ],
            [
                [
                    'title'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'age'
                ],
                'string',
                'max' => 16
            ],

            [
                [
                    'category_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::class,
                'targetAttribute' => [
                    'category_id' => 'id'
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
                    'age'
                ],
                'trim'
            ],
            [
                [
                    'image_file'
                ],
                'file',
                'skipOnEmpty' => true,
                'extensions' => 'png, jpg,jpeg'
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
                    'age'
                ],
                'in',
                'range' => array_keys(self::getAgeOptions())
            ],
            [
                [
                    'price_id'
                ],
                'in',
                'range' => array_keys(self::getPriceOptions())
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
            'category_id' => Yii::t('app', 'Category'),
            'description' => Yii::t('app', 'Description'),
            'author_name' => Yii::t('app', 'Author'),
            'image_file' => Yii::t('app', 'Image File'),
            'age' => Yii::t('app', 'Age'),
            'price_id' => Yii::t('app', 'Price Type'),
            'price' => Yii::t('app', 'Book Price (in $)'),
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
    public function getAudios()
    {
        return $this->hasMany(Audio::className(), [
            'book_id' => 'id'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), [
            'id' => 'category_id'
        ])->cache(5);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), [
            'id' => 'created_by_id'
        ])->cache(5);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(BookPage::className(), [
            'book_id' => 'id'
        ]);
    }

    public static function getHasManyRelations()
    {
        $relations = [];

        $relations['Audios'] = [
            'audios',
            'Audio',
            'id',
            'book_id'
        ];
        $relations['Pages'] = [
            'pages',
            'Page',
            'id',
            'book_id'
        ];
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
        $relations['category_id'] = [
            'category',
            'Category',
            'id'
        ];
        $relations['created_by_id'] = [
            'createdBy',
            'User',
            'id'
        ];
        return $relations;
    }

    public function getPagePdf()
    {
        return $this->hasOne(BookPage::class, [
            'book_id' => 'id'
        ]);
    }

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }
        // TODO : start here
        Audio::deleteRelatedAll([
            'book_id' => $this->id
        ]);
        BookPage::deleteRelatedAll([
            'book_id' => $this->id
        ]);

        Payment::deleteRelatedAll([
            'book_id' => $this->id
        ]);

        // Delete actual file
        $filePath = UPLOAD_PATH . $this->image_file;

        if (is_file($filePath)) {
            unlink($filePath);
        }

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

    public function getImageUrl($thumbnail = false)
    {
        $params = [
            '/' . $this->getControllerID() . '/image'
        ];
        $params['id'] = $this->id;

        if (isset($this->image_file) && ! empty($this->image_file)) {
            $params['file'] = $this->image_file;
        }

        if ($thumbnail)
            $params['thumbnail'] = is_numeric($thumbnail) ? $thumbnail : 150;
        return Url::toRoute($params, true);
    }

    public function getRating()
    {
        return $this->hasMany(Rating::class, [
            'model_id' => 'id'
        ]);
    }

    public function getLikeCount()
    {
        $query = $this->hasMany(Like::class, [
            'model_id' => 'id'
        ])->count();

        return $query;
    }

    public function getPurchase()
    {
        $purc = Payment::find()->Where([
            'book_id' => $this->id,
            'created_by_id' => Yii::$app->user->id
        ])->one();
        return $purc;
    }

    public function getFavourite()
    {
        $fav = Favourite::find()->Where([
            'model_id' => $this->id,
            'created_by_id' => Yii::$app->user->id
        ])->one();
        return $fav;
    }

    public function getLike()
    {
        $like = Like::find()->Where([
            'model_id' => $this->id,
            'created_by_id' => Yii::$app->user->id
        ])->one();
        return $like;
    }

    public function PaymentDetail()
    {
        $model = Payment::find()->where([
            'book_id' => $this->id
        ])
            ->my()
            ->one();
        return $model;
    }

    public function asJson($with_relations = true)
    {
        if (\Yii::$app->controller->action->id == 'book-detail' || \Yii::$app->controller->action->id == 'purchase-list' || \Yii::$app->controller->action->id == 'favourite-list' || \Yii::$app->controller->action->id == 'like-list') {
            return $this->asDetailJson();
        }
        $json = [];
        $json['id'] = $this->id;
        if (User::getHeaderValue() == 'en') {
            $json['title'] = $this->title;
        } else {
            $json['title'] = $this->getHebrewTitle();
        }
        $json['category_id'] = $this->category_id;

        if (User::getHeaderValue() == 'en') {
            $json['description'] = ! empty($this->description) ? strip_tags($this->description) : "";
        } else {
            $json['description'] = $this->getHebrewDescription();
        }

        if (User::getHeaderValue() == 'en') {
            $json['author_name'] = ! empty($this->author_name) ? $this->author_name : "";
        } else {
            $json['author_name'] = $this->getHebrewAuthorName();
        }

        if (isset($this->image_file)) {
            $json['image_file'] = ! empty($this->image_file) ? $this->getImageUrl() : '';
        }
        $json['age'] = $this->age;
        $json['rating_count'] = $this->getRating()->count();
        $json['user_count'] = $this->getRating()->count();
        $json['rating_average'] = ! empty($this->getRatingAverage($this->id)) ? $this->getRatingAverage($this->id) : 0;
        $json['price_id'] = $this->price_id;
        $json['price'] = $this->price;
        $json['type_id'] = $this->type_id;
        $json['state_id'] = $this->state_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        $json['parental_control'] = $this->getParentalControl($this->id);

        if ($with_relations) {
            // audios
            $list = ! empty($this->audios) ? $this->audios : "";
            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['audios'] = $relationData;
            } else {
                $json['audios'] = $list;
            }
            // category
            $list = ! empty($this->category) ? $this->category : "";

            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['category'] = $relationData;
            } else {
                $json['category'] = $list;
            }
            // rating
            $list = ! empty($this->rating) ? $this->rating : "";
            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['rating'] = $relationData;
            } else {
                $json['rating'] = (! empty($list)) ? $list->asJson() : null;
            }
            // createdBy
            $list = ! empty($this->createdBy) ? $this->createdBy : "";

            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['createdBy'] = $relationData;
            } else {
                $json['createdBy'] = $list;
            }
            // pages
            $list = ! empty($this->pages) ? $this->pages : "";
            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['pages'] = $relationData;
            } else {
                $json['pages'] = $list;
            }
        }
        return $json;
    }

    public function asDetailJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        if (User::getHeaderValue() == 'en') {
            $json['title'] = $this->title;
        } else {
            $json['title'] = $this->getHebrewTitle();
        }
        $json['category_id'] = $this->category_id;
        if (User::getHeaderValue() == 'en') {
            $json['description'] = ! empty($this->description) ? strip_tags($this->description) : "";
        } else {
            $json['description'] = $this->getHebrewDescription();
        }
        if (User::getHeaderValue() == 'en') {
            $json['author_name'] = ! empty($this->author_name) ? $this->author_name : "";
        } else {
            $json['author_name'] = $this->getHebrewAuthorName();
        }

        if (isset($this->image_file)) {
            $json['image_file'] = ! empty($this->image_file) ? $this->getImageUrl() : '';
        }
        $json['age'] = $this->age;
        $json['is_rated'] = $this->isRatedForBook();
        $json['rating_count'] = $this->getRating()->count();
        $json['user_count'] = $this->getRating()->count();
        $json['like_count'] = $this->getLikeCount();
        $json['price_id'] = $this->price_id;
        $json['price'] = $this->price;
        $json['type_id'] = $this->type_id;
        $json['state_id'] = $this->state_id;
        // $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        $json['pdf_file'] = ! empty($this->pagePdf) ? $this->pagePdf->getImageUrl() : "";
        $json['purchase_date'] = ! empty($this->PaymentDetail()) ? $this->PaymentDetail()->created_on : '';
        $json['rating_average'] = ! empty($this->getRatingAverage($this->id)) ? $this->getRatingAverage($this->id) : 0;
        $json['parental_control'] = $this->getParentalControl($this->id);

        // rating
        $list = ! empty($this->rating) ? $this->rating : "";
        if (is_array($list)) {
            $relationData = array_map(function ($item) {
                return $item->asJson();
            }, $list);

            $json['rating'] = $relationData;
        } else {
            $json['rating'] = (! empty($list)) ? $list->asJson() : null;
        }

        // rating
        $list = ! empty($this->rating) ? $this->rating : "";
        if (is_array($list)) {
            $relationData = array_map(function ($item) {
                return $item->asJson();
            }, $list);

            $json['rating'] = $relationData;
        } else {
            $json['rating'] = (! empty($list)) ? $list->asJson() : null;
        }

        if (! empty($this->purchase)) {

            $json['is_purchased'] = '1';
        } else {
            $json['is_purchased'] = '0';
        }
        if (! empty($this->favourite)) {

            $json['is_favourite'] = '1';
        } else {
            $json['is_favourite'] = '0';
        }
        if (! empty($this->like)) {

            $json['is_like'] = '1';
        } else {
            $json['is_like'] = '0';
        }

        if ($with_relations) {}
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
            $model->category_id = 1;
            $model->description = $faker->text;
            $model->image_file = isset($item['image_file']) ? $item['image_file'] : $faker->text(10);
            $model->age = $faker->text(10);
            $model->price_id = 0;
            $model->price = $faker->text(10);
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
            $model->category_id = isset($item['category_id']) ? $item['category_id'] : 1;
            $model->description = isset($item['description']) ? $item['description'] : $faker->text;
            $model->image_file = isset($item['image_file']) ? $item['image_file'] : $faker->text(10);
            $model->age = isset($item['age']) ? $item['age'] : $faker->text(10);
            $model->price_id = isset($item['price_id']) ? $item['price_id'] : 0;
            $model->price = isset($item['price']) ? $item['price'] : $faker->text(10);
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

    public function isRatedForBook()
    {
        $model = Rating::find()->andWhere([
            'model_type' => Detail::className(),
            'model_id' => $this->id
        ])
            ->my()
            ->one();
        if (! empty($model)) {
            return self::STATE_ACTIVE;
        }

        return self::STATE_INACTIVE;
    }

    public function getHebrewTitle()
    {
        $data = ! empty($this->getTranslation('he', 'title', $this)) ? $this->getTranslation('he', 'title', $this) : '';

        return $data;
    }

    public function getHebrewDescription()
    {
        $data = ! empty($this->getTranslation('he', 'description', $this)) ? $this->getTranslation('he', 'description', $this) : '';

        return $data;
    }

    public function getHebrewAuthorName()
    {
        $data = ! empty($this->getTranslation('he', 'author_name', $this)) ? $this->getTranslation('he', 'author_name', $this) : '';

        return $data;
    }

    public static function getRatingAverage($id)
    {
        $rating = Rating::find()->where([
            'model_id' => $id,
            'model_type' => Detail::class
        ]);

        $sum = $rating->sum('rating');
        $count = $rating->count();

        if ($sum == 0 || $count == 0) {
            $avg = 0;
        } else {
            $avg = round(($sum / $count), 2);
        }

        return $avg;
    }

    /**
     *
     * @param integer $id
     * @return boolean
     */
     public static function getParentalControl($id = null)
    {
        $current_user_lock = \Yii::$app->user->identity->pin_verified;

        $current_user_lock_status = false;

        if ($current_user_lock) {
            $current_user_lock_status = true;
        }

        $parentalQuery = ParentalControl::find()->where([
            'book_id' => $id
        ])
            ->my()
            ->one();

        if (empty($parentalQuery)) {
            return $current_user_lock_status;
        } else {
            if (! $current_user_lock_status) {
                return false;
            } else {
                if ($parentalQuery->lock == ParentalControl::LOCK_IS_ON) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
}
