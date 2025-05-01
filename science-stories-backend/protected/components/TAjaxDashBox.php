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

use yii\helpers\Html;

/**
 *
 * {@inheritdoc}
 */
class TAjaxDashBox extends TBaseWidget
{

    public $label;

    public $url;

    public function init()
    {
        parent::init();

        $this->options['id'] = $this->getId();
    }

    public function renderHtml()
    {
        $this->view->registerJs("$('#" . $this->id . "').load('" . $this->url . "');");

        ?>

            	
      <?php echo Html::tag('div', 'Loading', $this->options);?>
            	

<?php
    }
}

