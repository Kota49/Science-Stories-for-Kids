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
namespace app\modules\notification\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\notification\models\PushNotification as PushNotificationModel;

/**
 * PushNotification represents the model behind the search form about `app\modules\notification\models\PushNotification`.
 */
class PushNotification extends PushNotificationModel
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
                    'role_type',
                    'state_id',
                    'type_id'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'description',
                    'value',
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
        $query = PushNotificationModel::find()->alias('p')->joinWith('createdBy as c');

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
            'p.role_type' => $this->role_type,
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
            'p.description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'p.value',
            $this->value
        ])
        ->andFilterWhere([
            'like',
            'c.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
            'like',
            'p.created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
