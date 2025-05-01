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
 * AjaxAction column
 */
class TAjaxActionColumn extends DataColumn
{

    public $title = 'Click to Activate';

    public function init()
    {
        $this->registerJs();
    }

    /**
     *
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if (empty($this->value) || ! $this->value instanceof \Closure) {
            $url = [
                'delete',
                'id' => $model->id,
                'page' => Yii::$app->request->get('page'),
                'data-pjax' => '0',
                'data-method' => 'post'
            ];

            $icon = 'remove';
            $class = 'btn btn-danger';
            $title = Yii::t('yii', $this->title);

            return Html::a('<span class="glyphicon glyphicon-' . $icon . '"></span>', $url, [
                'title' => $title,
                'class' => 'ajax-action-column ' . $class
            ]);
        }
        return call_user_func($this->value, $model, $key, $index, $this);
    }

    /**
     * Registers the ajax JS
     */
    public function registerJs()
    {
        $url = Url::current();
        $js = '
        $("a.ajax-action-column").on("click", function(e) {
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
        $this->grid->view->registerJs($js, View::POS_READY, 'ajax-action-column');
    }
}
