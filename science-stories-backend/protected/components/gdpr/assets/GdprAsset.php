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
namespace app\components\gdpr\assets;

use yii\web\AssetBundle;

/**
 * Asset for social icons font.
 *
 */
class GdprAsset extends AssetBundle
{

    /**
     * {@inheritdoc}
     */
    public $sourcePath = '@app/components/gdpr/assets/src';

    /**
     * {@inheritdoc}
     */
    public $css = [
        'css/style-gdpr.css'
    ];
}
