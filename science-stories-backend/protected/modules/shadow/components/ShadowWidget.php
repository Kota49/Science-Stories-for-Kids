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
namespace app\modules\shadow\components;

use app\modules\shadow\models\Shadow;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\TBaseWidget;

/**
 * widget allow to logged in as current user
 */
class ShadowWidget extends TBaseWidget
{

    public function run()
    {
        if (\Yii::$app->user->isGuest)
            return true;

        $currentuser = Yii::$app->user->identity;
        $id = Yii::$app->session->get("shadow");
        if ($id != null) {
            $shadow = Shadow::find()->where([
                'id' => $id
                // 'to_id' => Yii::$app->user->id,
                // 'state_id' => Shadow::STATE_ACTIVE
            ])->one();

            if ($shadow != null) {
                $this->renderContent($shadow, $currentuser);
            }
        }
    }

    protected function renderContent($shadow, $currentuser)
    {
        echo Html::beginTag('div', [
            'class' => 'alert-wrapper'
        ]);
        echo Html::beginTag('div', [
            'class' => 'alert alert-danger'
        ]);
        echo 'You are logged in as ' . $currentuser->full_name . '[' . $currentuser->role . ']. Click here to return back  ' . Html::a('here', $shadow->getUrl('logout')) . ' <i class="fa fa-hand-o-left"></i>';
        echo Html::endTag('div');
        echo Html::endTag('div');
        echo '<div class="clearfix"></div><br/>';
    }
}
