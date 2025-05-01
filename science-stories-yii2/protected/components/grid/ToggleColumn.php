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

use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\web\View;
use Yii;
use yii\helpers\Url;

/**
 * Toggle column
 */
class ToggleColumn extends DataColumn
{

    /**
     * Toggle action that will be used as the toggle action in your controller
     *
     * @var string
     */
    public $action = 'toggle';

    public $onTitle = 'Click to Activate';

    public $offTitle = 'Click to In-Activate';

    /**
     *
     * @var string pk field name
     */
    public $primaryKey = 'id';

    /**
     * Whether to use ajax or not
     *
     * @var bool
     */
    public $enableAjax = true;

    public $gridViewId = null;

    public function init()
    {
        // if ($this->enableAjax) {
        $this->registerJs();
        // }
    }

    /**
     *
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $url = [
            $this->action,
            'id' => $model->{$this->primaryKey},
            'attribute' => $this->attribute,
            'page' => Yii::$app->request->get('page'),
            'data-pjax' => '0',
            'data-method' => 'post'
        ];

        $attribute = $this->attribute;
        $value = $model->$attribute;

        if ($value === null || $value == true) {
            $icon = 'ok';
            $class = 'btn btn-success';
            $title = Yii::t('yii', $this->offTitle);
        } else {
            $icon = 'remove';
            $class = 'btn btn-danger';
            $title = Yii::t('yii', $this->onTitle);
        }
        return Html::a('<span class="glyphicon glyphicon-' . $icon . '"></span>', $url, [
            'title' => $title,
            'class' => 'toggle-column ' . $class
        ]);
    }

    /**
     * Registers the ajax JS
     */
    public function registerJs()
    {
        $url = Url::current();
        $js = '
        $("a.toggle-column").on("click", function(e) {
            e.preventDefault();
            $.post($(this).attr("href"), function(data) {
                $target = $(e.target).closest(".grid-view").parent();
                var pjaxId =  $target.attr("id");
                if ( pjaxId == null )
                {
                   pjaxId =  $target.parent().attr("id");
                   if ( pjaxId == null )
                   {
                       pjaxId =  $target.parent().parent().attr("id");
                   }
                } 
                console.log("pjax reload " + pjaxId);
                if ( pjaxId != null  &&  pjaxId.includes("pjax"))
                {
                    $.pjax.reload({container:"#" + pjaxId, url:"' . $url . '",replace: false});
                }
            });
            return false;
        });';
        $this->grid->view->registerJs($js, View::POS_READY, 'toggle-column');
    }
}
