<?php

/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\components\notice;

use app\components\TBaseWidget;
use app\models\Notice;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is just an example.
 */
class Notices extends TBaseWidget
{

    public $model;

    public $disabled = false;

    protected function getRecentNotices()
    {
        
        $query = Notice::findActive()->orderBy('id DESC');
        
        if ($query->count() == 0)
            return null;
        
        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    public function run()
    {
        if ($this->disabled)
            return; // Do nothing
        
        if (\Yii::$app->user->isGuest)
            return;
        
        if ($this->model == null)
            $this->model = Yii::$app->user->identity;
        
        $notices = $this->getRecentNotices();
        if ($notices == null)
            return;
        
        return $this->render('notices', [
            'notices' => $notices
        ]);
    }
}
