<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\feature\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feature\models\Feature as FeatureModel;
use app\models\User;

/**
 * Feature represents the model behind the search form about `app\modules\feature\models\Feature`.
 */
class Feature extends FeatureModel
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
                    'state_id',
                    'order_id'
                ],
                'integer'
            ],
            [
                [
                    'created_by_id'
                ],
                'string'
            ],
            [
                [
                    'title',
                    'description',
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
        $query = FeatureModel::find()->alias('i')
        ->joinWith('createdBy as u');
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
            'i.state_id' => $this->state_id,
            'i.order_id' => $this->order_id,
        ]);
        
        $query->andFilterWhere([
            'like',
            'i.title',
            $this->title
        ])->andFilterWhere([
            'like',
            'i.description',
            $this->description
        ])->andFilterWhere([
            'like',
            'i.created_on',
            $this->created_on
        ])->andFilterWhere([
            'like',
            'u.full_name',
            $this->created_by_id
        ]);
        
        return $dataProvider;
    }
}
