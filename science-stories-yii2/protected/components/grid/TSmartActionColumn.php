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
namespace app\components\grid;

use Yii;
use yii\helpers\Html;
use app\models\User;

/**
 * Smart Action Column
 *
 */
class TSmartActionColumn extends \yii\grid\ActionColumn
{
    /**
     * 
     * @var boolean
     * @desc enable or not delete button
     */
    public $enableDelete = true;

    /**
     * @see \yii\grid\ActionColumn::init()
     */
    function init()
    {
        $this->enableDelete = User::isAdmin();
        parent::init();
        $this->initDefaultButtons();
        if (! $this->enableDelete) {
            unset($this->buttons['delete']);
        }
        $this->urlCreator = function ($action, $model, $key, $index) {
            return $model->getUrl($action);
        };
    }

    /**
     * @see \yii\grid\ActionColumn::initDefaultButtons()
     */
    protected function initDefaultButtons()
    {
        if (! isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-success'
                ], $this->buttonOptions);
                return Html::a('<span class="fa fa-eye"></span>', $url, $options);
            };
        }
        if (! isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-info'
                ], $this->buttonOptions);
                return Html::a('<span class="fa fa-pencil"></span>', $url, $options);
            };
        }
        if (! isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                    'class' => 'btn btn-danger'
                ], $this->buttonOptions);
                return Html::a('<span class="fa fa-trash"></span>', $url, $options);
            };
        }
    }
}
