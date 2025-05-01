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

use Yii;
use yii\helpers\Html;
use yii\web\View;

/**
 *
 * @inheritdoc
 *
 *
 */
class TCopyHelper
{

    public static function render($model, $attribute, $value = null)
    {
        $html = '';
        $value = $value ?? $model->$attribute;

        $html .= '<div class="text-primary" id="' . $attribute . '"> ' . $value . '';

        $html .= Html::button('<i class="fa fa-copy"></i>', [
            'id' => 'buttonCopyText-' . $attribute,
            'class' => 'btn btn-success ms-3',
            'title' => 'Copy'
        ]);
        $html .= '</div>';
        Yii::$app->view->registerJs("
                        $('#buttonCopyText-$attribute' ).click(function () {
                            var copyText = document.getElementById('$attribute');
                            if (copyText )
                            {
                             copyToClipboard(copyText.innerText);
                            }
                        })
                        ", View::POS_END, '".$attribute."');
        Yii::$app->view->registerJs(
                    "async function copyToClipboard(textToCopy) {
                        
                        // Navigator clipboard api needs a secure context (https)
                        if (navigator.clipboard && window.isSecureContext) {
                            await navigator.clipboard.writeText(textToCopy);
                            alert('Copied');
                            console.log('Copied');
                        } else {
                            // Use the 'out of viewport hidden text area' trick
                            const textArea = document.createElement('textarea');
                            textArea.value = textToCopy;
                                
                            // Move textarea out of the viewport so it's not visible
                            textArea.style.position = 'absolute';
                            textArea.style.left = '-999999px';
                                
                            document.body.prepend(textArea);
                            textArea.select();
                    
                            try {
                                document.execCommand('copy');
                                alert('Copied');
                                console.log('Copied');
                            } catch (error) {
                                console.error(error);
                            } finally {
                                textArea.remove();
                            }
                        }
                    }"
            , View::POS_END, 'copyToClipboard');
        return $html;
    }
}
