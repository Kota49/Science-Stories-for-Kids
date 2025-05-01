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
namespace app\components;

/**
 * 
 * Dashboard Widget
 *
 */
class TDashboard extends TBaseWidget
{
    public $columns = 1;
    public $items ;
    
    public function init()
    {
        parent::init();

        if (! isset($this->id)) {
            $this->options['id'] = $this->getId();
        }
    }

    public function renderHtml()
    {
        foreach ( $this->items as $item)
        {
            echo TAjaxDashBox::widget($item);
        }

        
    }
}

