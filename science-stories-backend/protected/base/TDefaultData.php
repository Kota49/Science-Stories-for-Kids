<?php

/**
 *
 *@copyright : OZVID Technologies Pvt. Ltd. < www.ozvid.com >
 *@author     : Shiv Charan Panjeta < shiv@ozvid.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of OZVID Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\base;

use app\models\User;
use app\modules\feature\models\Feature;
use app\modules\feature\models\Type as FeatureType;

class TDefaultData
{

    public static function data()
    {
        User::log(__FUNCTION__ . ' =>Default data start');
        User::addData([
            [
                'full_name' => 'Admin',
                'email' => 'admin@ozvid.in',
                'role_id' => User::ROLE_ADMIN,
                'password' => 'Admin@123'
            ]
        ]);
        FeatureType::addData([
            [
                'title' => 'Core',
                'type_id' => 1
            ]
        ]);
        Feature::addData([
            [
                'title' => 'Advanced Applicant Tracking',
                'order_id' => 1,
                'type_id' => 1
            ],
            [
                'title' => 'AI Based Resume Parsing',
                'order_id' => 2,
                'type_id' => 1
            ]
        ]);

        User::log(__FUNCTION__ . " ==> End");
    }
}

