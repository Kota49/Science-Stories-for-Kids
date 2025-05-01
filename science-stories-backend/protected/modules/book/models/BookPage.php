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
use yii\helpers\Url;

/**
 * This is the model class for table "tbl_book_page".
 *
 * @property integer $id
 * @property string $title
 * @property integer $category_id
 * @property string $description
 * @property integer $book_id
 * @property integer $type_id
 * @property integer $state_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property Audio[] $audios
 * @property Book $book
 * @property User $createdBy
 */
class BookPage extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->title;
    }

    public static function getCategoryOptions()
    {
        return self::listData(Category::findActive()->all());
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), [
            'id' => 'category_id'
        ])->cache(10);
    }

    public static function getBookNameOptions()
    {
        return self::listData(Book::findActive()->each(), 'id', 'title');
    }

    public static function getBookList()
    {
        return ArrayHelper::map(Detail::find()->each(), 'id', 'title');
    }

    public static function getBookOptions()
    {
        return self::listData(Detail::findActive()->all());
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
        return '{{%book_page}}';
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
                    'category_id',
                    'book_id',
                    'created_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'category_id',
                    'book_id',
                    'type_id',
                    'state_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'description'
                ],
                'string'
            ],
            [
                [
                    'created_on',
                    'page_image'
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
                    'book_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => Detail::class,
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
                    'title'
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
            'category_id' => Yii::t('app', 'Category'),
            'description' => Yii::t('app', 'Description'),
            'book_id' => Yii::t('app', 'Book'),
            'type_id' => Yii::t('app', 'Type'),
            'state_id' => Yii::t('app', 'State'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'Created By'),
            'page_image' => Yii::t('app', 'Page Image File')
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAudios()
    {
        return $this->hasOne(Audio::class, [
            'page_id' => 'id'
        ])
            ->andOnCondition([
            'book_id' => $this->book_id
        ])
            ->cache(5);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAudiosActive()
    {
        return $this->hasOne(Audio::class, [
            'page_id' => 'id'
        ])
            ->andOnCondition([
            'book_id' => $this->book_id,
            'state_id' => Audio::STATE_ACTIVE
        ])
            ->cache(5);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Detail::className(), [
            'id' => 'book_id'
        ])->cache(10);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarked()
    {
        $bookmarked = Favourite::find()->Where([
            'model_id' => $this->id,
            'created_by_id' => Yii::$app->user->id
        ])->one();
        return $bookmarked;
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

        $relations['Audios'] = [
            'audios',
            'Audio',
            'id',
            'page_id'
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
        $relations['category_id'] = [
            'category',
            'Category',
            'id'
        ];
        return $relations;
    }

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }

        Audio::deleteRelatedAll([
            'book_id' => $this->id
        ]);

        Audio::deleteRelatedAll([
            'page_id' => $this->id
        ]);

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

        $json['book_id'] = $this->book_id;

        if (User::getHeaderValue() == 'en') {
            $json['book_title'] = ! empty($this->book_id) ? $this->book->title : '';
        } else {
            $json['book_title'] = ! empty($this->book_id) ? $this->book->getHebrewTitle() : '';
        }

        $json['type_id'] = $this->type_id;
        $json['state_id'] = $this->state_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        $json['image_file'] = $this->getImageUrl();
        $json['audio_file'] = ! empty($this->audiosActive) ? $this->audiosActive->getImageUrl() : "";

        if (User::getHeaderValue() == 'en') {
            $json['audio_title'] = ! empty($this->audios) ? $this->audios->title : "";
        } else {
            $json['audio_title'] = ! empty($this->audios) ? $this->audios->getHebrewTitle() : '';
        }

        $json['page_image'] = $this->getPageImageUrl();
        if (! empty($this->bookmarked)) {
            $json['is_bookmarked'] = '1';
        } else {
            $json['is_bookmarked'] = '0';
        }
        $json['parental_control'] = Detail::getParentalControl($this->book_id);

        if ($with_relations) {
            // audios
            $list = $this->audios;

            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['audios'] = $relationData;
            } else {
                $json['audios'] = $list;
            }
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
            $model->category_id = 1;
            $model->description = $faker->text;
            $model->book_id = 1;
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

            $model->book_id = isset($item['book_id']) ? $item['book_id'] : 1;

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

    public function getPageImageUrl($thumbnail = false)
    {
        $params = [
            '/' . $this->getControllerID() . '/pageimage'
        ];
        $params['id'] = $this->id;

        if (isset($this->page_image) && ! empty($this->page_image)) {
            $params['file'] = $this->page_image;
        }

        if ($thumbnail)
            $params['thumbnail'] = is_numeric($thumbnail) ? $thumbnail : 150;
        return Url::toRoute($params, true);
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
}
