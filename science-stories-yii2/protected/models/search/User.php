<?php
/**
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author	 : Shiv Charan Panjeta < shiv@ozvid.com >
 */
namespace app\models\search;

use app\models\User as UserModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * User represents the model behind the search form about `app\models\User`.
 */
class User extends UserModel
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
                    'gender',
                    'tos',
                    'role_id',
                    'state_id',
                    'type_id',
                    'login_error_count',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'full_name',
                    'email',
                    'password',
                    'date_of_birth',
                    'about_me',
                    'contact_no',
                    'address',
                    'latitude',
                    'longitude',
                    'city',
                    'country',
                    'zipcode',
                    'language',
                    'profile_file',
                    'last_visit_time',
                    'last_action_time',
                    'last_password_change',
                    'activation_key',
                    'timezone',
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
        $query = UserModel::find()->alias('u');
        /*
         * $query->andWhere([
         * '!=',
         * 'u.role_id',
         * UserModel::ROLE_ADMIN
         * ]);
         */

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
            'u.id' => $this->id,
            'u.date_of_birth' => $this->date_of_birth,
            'u.gender' => $this->gender,
            'u.tos' => $this->tos,
            'u.role_id' => $this->role_id,
            'u.state_id' => $this->state_id,
            'u.type_id' => $this->type_id,
            'u.last_visit_time' => $this->last_visit_time,
            'u.last_action_time' => $this->last_action_time,
            'u.last_password_change' => $this->last_password_change,
            'u.login_error_count' => $this->login_error_count,
            'u.created_by_id' => $this->created_by_id
        ]);

        $query->andFilterWhere([
            'like',
            'u.full_name',
            $this->full_name
        ])
            ->andFilterWhere([
            'like',
            'u.email',
            $this->email
        ])
            ->andFilterWhere([
            'like',
            'u.about_me',
            $this->about_me
        ])
            ->andFilterWhere([
            'like',
            'u.contact_no',
            $this->contact_no
        ])
            ->andFilterWhere([
            'like',
            'u.address',
            $this->address
        ])
            ->andFilterWhere([
            'like',
            'u.latitude',
            $this->latitude
        ])
            ->andFilterWhere([
            'like',
            'u.longitude',
            $this->longitude
        ])
            ->andFilterWhere([
            'like',
            'u.city',
            $this->city
        ])
            ->andFilterWhere([
            'like',
            'u.country',
            $this->country
        ])
            ->andFilterWhere([
            'like',
            'u.zipcode',
            $this->zipcode
        ])
            ->andFilterWhere([
            'like',
            'u.language',
            $this->language
        ])
            ->andFilterWhere([
            'like',
            'u.created_on',
            $this->created_on
        ])
            ->andFilterWhere([
            'like',
            'u.timezone',
            $this->timezone
        ]);
        return $dataProvider;
    }
}
