<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\storage\widgets;


use yii\base\Widget;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\modules\fileshare\models\Share;

// echo Gallery::widget([
// 'images' => [
// [
// 'url' => 'http://demo.michaelsoriano.com/images/photodune-174908-rocking-the-night-away-xs.jpg',
// 'title' => 'Hello'
// ]
// ]
// ])
class Gallery extends Widget
{

    const BOOTSTRAP_PHOTO_GALLER = 'bootstrap-photo-gallery';

    public $layout = null;

    public $delete = true;

    public $model;

    public $dataProvider;

    /*
     * array of images or model with arribute
     *
     * 'images' => [
     * [
     * 'thumb' => 'http://demo.michaelsoriano.com/images/photodune-174908-rocking-the-night-away-xs.jpg',
     * 'url' => 'http://demo.michaelsoriano.com/images/photodune-174908-rocking-the-night-away-xs.jpg',
     * 'title' => 'Hello',
     * 'deleteUrl' => [],
     * 'id' => ''
     * ]
     * ]
     *
     * OR
     *
     * 'images' => [
     * 'model' => 'app\modules\media\models\file',
     * 'attribute' => 'file' (By default)
     * ]
     *
     */
    public $images;

    public $id = "bootstrap-photo-gallery";

    public $class;

    public $options;
    public $unique_code;

    public function init()
    {
        parent::init();
        if (empty($this->layout))
            $this->layout = self::BOOTSTRAP_PHOTO_GALLER;
        
        $this->imageModal();
        $this->getLayout();
    }

    public function imageModal()
    {
        if (empty($this->images) && empty($this->dataProvider)) {
            $this->createDataProvider();
            $this->images = $this->getData();
        } elseif (! empty($this->dataProvider)) {
            $this->images = $this->getData();
        }
        
        return $this->images;
    }

    protected function getData()
    {
        $images = [];
        foreach ($this->dataProvider->models as $image) {
            $images[] = [
                'thumb' => \Yii::$app->urlManager->createAbsoluteUrl([
                    '/fileshare/file/image',
                    'id' => $image->id
                ]),
                'url' => \Yii::$app->urlManager->createAbsoluteUrl([
                    '/fileshare/file/image',
                    'id' => $image->id,
                    'thumb' => false
                ]),
                'deleteUrl' => \Yii::$app->urlManager->createAbsoluteUrl([
                    '/fileshare/file/delete',
                    'id' => $image->id
                ]),
                'title' => isset($image['title']) ? $image['title'] : "Image",
                'id' => $image->id,
                'unique_code'=> isset($image['unique_code']) ? $image['unique_code'] : "Image",
                'id' => $image->unique_code,
            ];
        }
        return $images;
    }

    protected function createDataProvider()
    {
        if (! empty($this->model->id)) {
            $query = Share::find()->where([
                'model_id' => $this->model->id,
                'model_type' => $this->model::className()
            ]);
        } else {
            $query = Share::find();
            if (!( User::isAdmin() &&  \Yii::$app->user->isAdminMode)) {
                $query = $query->andWhere(['created_by_id' => \Yii::$app->user->id]);
            }
        }
        
        return $this->dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
    }

    private function getLayout()
    {
        switch ($this->layout) {
            case self::BOOTSTRAP_PHOTO_GALLER:
                echo $this->render('_thumbnail_gallery', [
                    'delete' => $this->delete,
                    'model' => $this->model,
                    'images' => $this->images,
                    'id' => $this->id,
                    'class' => $this->class,                   
                    'options' => $this->options
                ]);
                break;
        }
    }
}
