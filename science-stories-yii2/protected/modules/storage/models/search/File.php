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

use app\modules\storage\models\File as FileModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * File represents the model behind the search form about `app\modules\storage\models\File`.
 */
class File extends FileModel
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
                    'size',
                    'type_id'
                ],
                'integer'
            ],
            [
                [
                    'name',
                    'key',
                    'model_id',
                    'model_type',
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
        $query = FileModel::find()->alias('f')->joinWith('createdBy as u');

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
            'size' => $this->size,
            'type_id' => $this->type_id
        ]);

        $query->andFilterWhere([
            'like',
            'f.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'f.name',
            $this->name
        ])
            ->andFilterWhere([
            'like',
            'f.key',
            $this->key
        ])
            ->andFilterWhere([
            'like',
            'u.full_name',
            $this->model_id
        ])
            ->andFilterWhere([
            'like',
            'f.model_type',
            $this->model_type
        ])
            ->andFilterWhere([
            'like',
            'f.created_on',
            $this->created_on
        ])
            ->andFilterWhere([
            'like',
            'u.full_name',
            $this->created_by_id
        ]);

        return $dataProvider;
    }
}
