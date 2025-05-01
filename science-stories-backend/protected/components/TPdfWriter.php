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

use Mpdf\Mpdf;
use yii\helpers\ArrayHelper;
use app\components\helpers\TStringHelper;

/**
 * Pdf helper
 */
class TPdfWriter extends Mpdf
{

    public $data = [];

    public function __construct($data = null)
    {
        $this->data['tempDir'] = \Yii::getAlias('@runtime');

        if (! empty($data) && is_array($data)) {

            $this->data = ArrayHelper::merge($this->data, $data);
        }

        parent::__construct($this->data);
        if (! isset($this->data['no-protection'])) {
            $this->SetProtection([
                'print'
            ]);
        }

        if (! \Yii::$app instanceof \yii\console\Application) {
            $this->SetAuthor(\Yii::$app->user->userName);
            $this->SetCreator(\Yii::$app->user->userName);
        }
    }

    public function Output($name = '', $dest = '')
    {
        if (empty($this->title)) {
            $title = TStringHelper::basename($name);
            $this->SetSubject($title);
            $this->SetTitle($title);
        }
        return parent::Output($name, $dest);
    }

    public function enableWaterMark($text = null)
    {
        // call watermark content aand image
        $this->SetWatermarkText($text != null ? $text : \Yii::$app->name);
        $this->showWatermarkText = true;
        $this->watermarkTextAlpha = 0.1;
    }

    public function enableImageWaterMark($image)
    {
        // call watermark content aand image
        $this->SetWatermarkImage($image);
        $this->showWatermarkImage = true;
        $this->watermarkImageAlpha = 0.1;
    }
}

?>
