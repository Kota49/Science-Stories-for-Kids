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
namespace app\components\editors\sceditor;

use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;
use yii\helpers\Html;

abstract class ScEditor extends InputWidget
{

    /**
     *
     * @var string
     */
    public $editorType = 'Classic';

    /**
     *
     * @var array
     */
    public $clientOptions = [];

    /**
     *
     * @var array Toolbar options array
     */
    public $toolbar = [];

    /**
     *
     * @var string Url to image upload
     */
    public $uploadUrl = '';

    /**
     *
     * @var array
     */
    public $options = [];

    /**
     *
     * @inheritdoc
     */
    public function run()
    {
        $this->registerAssets($this->getView());
        $this->registerEditorJS();
        $this->printEditorTag();
    }

    /**
     * Registration JS
     */
    protected function registerEditorJS()
    {
        if (! empty($this->toolbar)) {
            $this->clientOptions['toolbar'] = $this->toolbar;
        }

        $clientOptions = Json::encode($this->clientOptions);

        $var = str_replace('-', '', $this->options['id']);

        $jsEditor = new JsExpression("
  var textarea = document.getElementById('" . $this->options['id'] . "');
      sceditor.create(textarea, {
				format: 'xhtml',
				icons: 'monocons',
			});
   
   ");

        $this->view->registerJs($jsEditor);
    }

    /**
     *
     * @param \yii\web\View $view
     */
    protected function registerAssets($view)
    {
        ScEditorAssets::register($view);
    }

    /**
     * View tag for editor
     */
    /**
     *
     * @inheritdoc
     */
    protected function printEditorTag()
    {
        if ($this->hasModel()) {
            print Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            print Html::textarea($this->name, $this->value, $this->options);
        }
    }
}
