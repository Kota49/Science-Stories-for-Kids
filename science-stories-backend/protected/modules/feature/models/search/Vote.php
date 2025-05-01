<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\feature\models\search;

use app\modules\feature\models\Vote as VoteModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Vote represents the model behind the search form about `app\modules\feature\models\Vote`.
 */
class Vote extends VoteModel
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
                    'feature_id',
                    'created_by_id'
                ],
                'string'
            ],
            [
                [
                    'comment',
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
        $query = VoteModel::find()->alias('i')
            ->joinWith('createdBy as u')
            ->joinWith('feature as f');

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
            'i.id' => $this->id,
            'i.type_id' => $this->type_id,
            'i.state_id' => $this->state_id
        ]);

        $query->andFilterWhere([
            'like',
            'i.comment',
            $this->comment
        ])
            ->andFilterWhere([
            'like',
            'i.created_on',
            $this->created_on
        ])
            ->andFilterWhere([
            'like',
            'f.title',
            $this->feature_id
        ])
            ->andFilterWhere([
            'like',
            'u.full_name',
            $this->created_by_id
        ]);
        return $dataProvider;
    }
}
