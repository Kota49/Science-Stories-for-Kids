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
use app\modules\storage\models\Type as TypeModel;

/**
 * Type represents the model behind the search form about `app\modules\storage\models\Type`.
 */
class Type extends TypeModel
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
                    'description',
                    'created_by_id',
                    'created_on'
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
        $query = TypeModel::find()->alias('t')->joinWith('createdBy as c');

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
            't.state_id' => $this->state_id,
            't.type_id' => $this->type_id
        ]);

        $query->andFilterWhere([
            'like',
            't.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            't.title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            't.description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'c.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
            'like',
            't.created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
