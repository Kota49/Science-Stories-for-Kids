<?php
/**
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author    : Shiv Charan Panjeta < shiv@ozvid.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\book\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\book\models\Detail as DetailModel;

/**
 * Detail represents the model behind the search form about `app\modules\book\models\Detail`.
 */
class Detail extends DetailModel
{

    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',

                    'image_file',
                    'type_id',
                    'state_id',
                    'price_id'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'category_id',
                    'description',
                    'age',
                    'price',
                    'created_on',
                    'author_name',
                    'created_by_id'
                ],
                'safe'
            ]
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function beforeValidate()
    {
        return true;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DetailModel::find()->alias('c')
            ->joinWith('category as cd')
            ->joinWith('createdBy as b');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        if (! ($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'c.image_file' => $this->image_file,
            'c.type_id' => $this->type_id,
            'c.state_id' => $this->state_id,
            'c.price_id' => $this->price_id,
            'c.age' => $this->age
        ]);

        $query->andFilterWhere([
            'like',
            'c.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'c.title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            'c.description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'c.price',
            $this->price
        ])
            ->andFilterWhere([
            'like',
            'cd.title',
            $this->category_id
        ])
            ->andFilterWhere([
            'like',
            'b.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
            'like',
            'c.author_name',
            $this->author_name
        ])
            ->andFilterWhere([
            'like',
            'c.created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
