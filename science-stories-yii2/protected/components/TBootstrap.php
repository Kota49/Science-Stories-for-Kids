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

use Yii;
use yii\base\BootstrapInterface;
use yii\helpers\VarDumper;

/**
 * Class ModuleBootstrap
 *
 * @package app\extensions
 */
class TBootstrap implements BootstrapInterface
{

    public function bootstrap($oApplication)
    {
        $aModuleList = $oApplication->getModules();
        
        foreach ($aModuleList as $sKey => $aModule) {
            if (is_array($aModule) && strpos($aModule['class'], 'app\modules') === 0 && is_subclass_of($aModule['class'], TModule::class)) {
                $className = $aModule['class'];
                
                $sFilePathConfig = $className::getRules();
                if (! empty($sFilePathConfig)) {
                    Yii::trace($sKey . " :adding rules : " . VarDumper::dumpAsString($sFilePathConfig));
                    $oApplication->getUrlManager()->addRules($sFilePathConfig,false);
                }
            }
        }
    }
}
