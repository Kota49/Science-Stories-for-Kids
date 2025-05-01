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

use yii\helpers\IpHelper;
use yii\helpers\VarDumper;

class TipHelper extends IpHelper
{
    use TLogHelper;

    public static function checkIfClientIpIsTrusted($ip = null)
    {
        if ($ip == null) {
            $ip = \Yii::$app->getRequest()->getUserIP();
        }

        if (self::inRange($ip, '127.0.0.0/8')) {
            return true;
        }

        foreach (\Yii::$app->getRequest()->trustedHosts as $iprange) {
            if (self::inRange($ip, $iprange)) {
                return true;
            }
        }
        return false;
    }

    public static function getNetworkFromIP($markedBits = 8, $ips = null)
    {
        if ($ips == null) {
            $ips = gethostbynamel(gethostname());
        }

        $ips = is_array($ips) ? $ips : [
            $ips
        ];
        $network = [];
        self::log('IPs= ' . VarDumper::dumpAsString($ips));
        foreach ($ips as $ip) {

            if (self::getIpVersion($ip) == self::IPV4) {

                $network[] = preg_replace('/(\.\d+)/', '.0', $ip) . '/' . $markedBits;
            }
            self::log('$network = ' . VarDumper::dumpAsString($network));
        }

        return $network;
    }

    public static function formatAsMacAddress(string $input)
    {
        // Ensure the input length is appropriate for a typical MAC address without colons.
        if (strlen($input) != 12 && strlen($input) % 3 != 0) {
            return false; // Return false or an error message if the string length isn't valid
        }

        $result = '';
        $len = strlen($input);
        for ($i = 0; $i < $len; $i += 2) {
            $chunk = substr($input, $i, 2);
            $result .= $chunk . (($i + 2) < $len ? ':' : '');
        }

        return strtoupper($result);
    }

    public static function formatMacAddressAsString(string $mac)
    {
        return str_replace([
            ':',
            '-'
        ], '', $mac);
    }
}