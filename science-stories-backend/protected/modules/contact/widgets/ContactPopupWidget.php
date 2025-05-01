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
namespace app\modules\contact\widgets;

use app\components\TBaseWidget;
use app\modules\contact\models\Information;

class ContactPopupWidget extends TBaseWidget
{

    public function run()
    {
        $model = Information::createNewRecord();
        return $this->render('contact-popup', [
            'model' => $model
        ]);
    }
}
