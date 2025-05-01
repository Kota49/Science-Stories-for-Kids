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
namespace app\models\search;

use app\models\Banner as BannerModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Banner represents the model behind the search form about `app\models\Banner`.
 */
class Banner extends BannerModel
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
                    'image_file',
                    'created_on',
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
        $query = BannerModel::find()->alias('b')->joinWith('createdBy as c');

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
            'b.type_id' => $this->type_id,
            'b.state_id' => $this->state_id
            // 'b.created_by_id' => $this->created_by_id
        ]);

        $query->andFilterWhere([
            'like',
            'b.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'b.title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            'b.description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'b.image_file',
            $this->image_file
        ])
            ->andFilterWhere([
            'like',
            'c.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
            'like',
            'b.created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
