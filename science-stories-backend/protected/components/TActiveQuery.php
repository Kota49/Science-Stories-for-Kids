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
namespace app\components;

use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveQuery;

/**
 *
 * {@inheritdoc}
 *
 *
 */
class TActiveQuery extends ActiveQuery
{
    public function cacheTagged($tag)
    {
        return $this->cache(60, new TagDependency([
            'tags' => $tag
        ]));
    }
    public function my($attribbute = 'created_by_id')
    {
        list ($table, $alias) = $this->getTableNameAndAlias();

        return $this->andWhere([
            $alias . '.' . $attribbute => Yii::$app->user->id
        ]);
    }

    public function active($state_id = 1, $stateAttribbute = 'state_id')
    {
        list ($table, $alias) = $this->getTableNameAndAlias();

        return $this->andWhere([
            $alias . '.' . $stateAttribbute => $state_id
        ]);
    }

    public function andState($state_id, $stateAttribbute = 'state_id')
    {
        list ($table, $alias) = $this->getTableNameAndAlias();
        $state_id = is_array($state_id) ? $state_id : [
            $state_id
        ];

        return $this->andWhere([
            'in',
            $alias . '.' . $stateAttribbute,
            $state_id
        ]);
    }

    public function orState($state_id, $stateAttribbute = 'state_id')
    {
        list ($table, $alias) = $this->getTableNameAndAlias();
        $state_id = is_array($state_id) ? $state_id : [
            $state_id
        ];
        return $this->orWhere([
            'in',
            $alias . '.' . $stateAttribbute,
            $state_id
        ]);
    }
}
