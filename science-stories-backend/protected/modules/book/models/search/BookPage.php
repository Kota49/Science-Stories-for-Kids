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
namespace app\modules\book\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\book\models\BookPage as BookPageModel;

/**
 * BookPage represents the model behind the search form about `app\modules\book\models\BookPage`.
 */
class BookPage extends BookPageModel
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
                    'type_id',
                    'state_id'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'description',
                    'created_on',
                    'category_id',
                    'book_id',
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
        $query = BookPageModel::find()->alias('c')->joinWith([
            'category as cd',
            'createdBy as cb',
            'book as b'
        ]);
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
            'c.type_id' => $this->type_id,
            'c.state_id' => $this->state_id
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
            'cd.title',
            $this->category_id
        ])
            ->andFilterWhere([
            'like',
            'b.title',
            $this->book_id
        ])
            ->andFilterWhere([
            'like',
            'c.created_on',
            $this->created_on
        ])
            ->andFilterWhere([
            'like',
            'cb.full_name',
            $this->created_by_id
        ]);

        return $dataProvider;
    }
}
