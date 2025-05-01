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
namespace app\modules\storage\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\storage\models\Provider as ProviderModel;

/**
 * Provider represents the model behind the search form about `app\modules\storage\models\Provider`.
 */
class Provider extends ProviderModel
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
                    'state_id',
                    'type_id'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'key',
                    'secret',
                    'endpoint',
                    'read_write',
                    'location',
                    'created_by_id',
                    'created_on',
                    'updated_on'
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
        $query = ProviderModel::find()->alias('p')->joinWith('createdBy as c');

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
            'p.state_id' => $this->state_id,
            'p.type_id' => $this->type_id
        ]);

        $query->andFilterWhere([
            'like',
            'p.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'p.title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            'p.key',
            $this->key
        ])
            ->andFilterWhere([
            'like',
            'p.secret',
            $this->secret
        ])
            ->andFilterWhere([
            'like',
            'p.endpoint',
            $this->endpoint
        ])
            ->andFilterWhere([
            'like',
            'p.read_write',
            $this->read_write
        ])
            ->andFilterWhere([
            'like',
            'p.location',
            $this->location
        ])
            ->andFilterWhere([
            'like',
            'p.created_on',
            $this->created_on
        ])
            ->andFilterWhere([
            'like',
            'c.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
            'like',
            'p.updated_on',
            $this->updated_on
        ]);

        return $dataProvider;
    }
}
