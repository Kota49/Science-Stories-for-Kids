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
namespace app\components\helpers;

trait LoadApiData
{

    /**
     * 
     * @param array $data
     * @param string $formName
     * @return boolean
     */
    public function load($data, $formName = null)
    {
        if (parent::load($data, $formName)) {
            return true;
        }
        if (parent::load($data, '')) {
            return true;
        }

        return false;
    }
}
