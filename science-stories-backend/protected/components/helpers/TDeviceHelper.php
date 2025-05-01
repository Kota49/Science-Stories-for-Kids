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

use yii\base\Component;

/**
 * Device helper
 * 
 */
class TDeviceHelper 
{

    /**
     * Return true if Mobile User Agent is detected
     * @param $user_agent
     * @return number
     */
    public static function isMobile($user_agent = null)
    {
        $aMobileUA = array(
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );
        if ($user_agent == null) {
            $user_agent = \Yii::$app->request->getUserAgent();
        }

        // Return true if Mobile User Agent is detected
        foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
            if (preg_match($sMobileKey, $user_agent)) {
                return 1;
            }
        }
        // Otherwise return false..
        return 0;
    }
    
    /**
     * Return true if Android User Agent is detected
     * @param $user_agent
     * @return number
     */
    public static function isAndroid($user_agent = null)
    {
        $aMobileUA = array(

            '/android/i' => 'Android',
            '/webos/i' => 'Mobile'
        );

        if ($user_agent == null) {
            $user_agent = \Yii::$app->request->getUserAgent();
        }
        // Return true if Mobile User Agent is detected
        foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
            if (preg_match($sMobileKey, $user_agent)) {
                return 1;
            }
        }
        // Otherwise return false..
        return 0;
    }
}
