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

use yii\base\InvalidCallException;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/**
 *
 * {@inheritdoc}
 *
 *        
 */
class TActiveForm extends ActiveForm
{

    /**
     * Enable AntiSpam Form tags to avoid form bots to send messages
     *
     * @var boolean
     */
    public $enableAntispamTags = false;

    /**
     *
     * @inheritdoc
     */
    public $enableAjaxValidation = true;

    /**
     *
     * @inheritdoc
     */
    public $enableClientValidation = false;

    public $options = [
        'enctype' => 'multipart/form-data'
    ];

    public $fieldClass = 'app\components\TActiveField';

    /**
     * Runs the widget.
     * This registers the necessary JavaScript code and renders the form open and close tags.
     *
     * @throws InvalidCallException if `beginField()` and `endField()` calls are not matching.
     */
    public function init()
    {
        $this->layout == self::LAYOUT_HORIZONTAL;

        parent::init();
    }

    public function run()
    {
        if (! empty($this->_fields)) {
            throw new InvalidCallException('Each beginField() should have a matching endField() call.');
        }

        $content = ob_get_clean();
        $html = Html::beginForm($this->action, $this->method, $this->options);

        if ($this->enableAntispamTags) {
            $html = Html::script("document.write(atob(\"" . base64_encode($html) . "\"));");
        }

        $html .= $content;

        if ($this->enableClientScript) {
            $this->registerClientScript();
        }

        $html .= Html::endForm();

        return $html;
    }
}
