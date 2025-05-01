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
namespace app\modules\faq\models\search;

use app\modules\faq\models\Faq as FaqModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Faq represents the model behind the search form about `app\modules\faq\models\Faq`.
 */
class Faq extends FaqModel
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
                    'question',
                    'answer',
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
        $query = FaqModel::find()->alias('f')->joinWith('createdBy as cb');

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
            'f.state_id' => $this->state_id,
            'f.type_id' => $this->type_id
        ]);

        $query->andFilterWhere([
            'like',
            'f.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'f.question',
            $this->question
        ])
            ->andFilterWhere([
            'like',
            'f.answer',
            $this->answer
        ])
            ->andFilterWhere([
            'like',
            'f.created_on',
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
