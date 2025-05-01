<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\modules\feature\widgets;

use app\components\TBaseWidget;
use app\modules\feature\models\Feature;
use yii\data\ArrayDataProvider;

/**
 * This is just an example.
 */
class FeatureWidget extends TBaseWidget
{

    const LATEST_FEATURES = 1;

    const UPCOMING_FEATURES = 2;

    public $model;

    public $type_id = self::LATEST_FEATURES;

    public $disabled = false;

    public static function getTypeOptions()
    {
        return [
            self::LATEST_FEATURES => "Latest Features",
            self::UPCOMING_FEATURES => "Upcoming Features"
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    protected function getLatest()
    {
        $provider = new ArrayDataProvider([
            'allModels' => Feature::find()->limit(5)
                ->orderBy('id DESC')
                ->all()
        ]);
        return $provider;
    }

    protected function getUpcoming()
    {
        $provider = new ArrayDataProvider([
            'allModels' => Feature::find()->where([
                'type_id' => self::UPCOMING_FEATURES
            ])
                ->limit(5)
                ->all()
        ]);
        return $provider;
    }

    protected function getPosts()
    {
        switch ($this->type_id) {

            case self::LATEST_FEATURES:
                return $this->getLatest();
                break;
            case self::UPCOMING_FEATURES:
                return $this->getUpcoming();
                break;
        }
    }

    public function run()
    {
        if ($this->disabled)
            return; // Do nothing
        $posts = $this->getPosts();
        if ($posts == null)
            return;
        return $this->render('posts', [
            'posts' => $posts,
            'type' => $this->getType()
        ]);
    }
}
