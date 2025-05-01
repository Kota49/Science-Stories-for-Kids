<?php
/**
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author	 : Shiv Charan Panjeta < shiv@ozvid.com >
 */
namespace app\modules\notification\models\search;

use app\modules\notification\models\Notification as NotificationModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * Notification represents the model behind the search form about `app\modules\notification\models\Notification`.
 */
class Notification extends NotificationModel
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
                    'model_id',
                    'state_id',
                    'type_id'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'description',
                    'model_type',
                    'is_read',
                    'created_on',
                    'to_user_id',
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
        $query = NotificationModel::find()->alias('n')
            ->joinWith('createdBy as c')
            ->joinWith('toUser as t');

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
            'n.id' => $this->id,
            'n.model_id' => $this->model_id,
            'n.state_id' => $this->state_id,
            'n.type_id' => $this->type_id
        ]);

        $query->andFilterWhere([
            'like',
            'n.title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            'n.description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'n.model_type',
            $this->model_type
        ])
            ->andFilterWhere([
            'like',
            'n.is_read',
            $this->is_read
        ])
            ->andFilterWhere([
            'like',
            't.full_name',
            $this->to_user_id
        ])
            ->andFilterWhere([
            'like',
            'c.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
            'like',
            'n.created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
