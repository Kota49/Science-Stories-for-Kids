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
namespace app\components\editors\suneditor;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

abstract class SunEditor extends InputWidget
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

        // $clientOptions = Json::encode($this->clientOptions);

        $var = str_replace('-', '', $this->options['id']);
        $modelType = str_replace('\\', '\\\\', get_class($this->model));
        $modelId = $this->model->id ?? NULL;
        $baseUrl = Url::home();
        $csrf = \Yii::$app->request->getCsrfToken();
        $jsEditor = new JsExpression(" var $var = SUNEDITOR.create('" . $this->options['id'] . "' , suneditor_config);
                                    
                editorConfig($var, {model_type:\"$modelType\", model_id: \"$modelId\", base_url: \"$baseUrl\",csrf: \"$csrf\"});
                ");

        $this->view->registerJs($jsEditor);

        $css = '.sun-editor-editable table,
.sun-editor-editable table tr,
.sun-editor-editable table th,
.sun-editor-editable table td {
	border: 1px solid #e1e1e1 !important;
}';

        $this->view->registerCss($css);
    }

    /**
     *
     * @param \yii\web\View $view
     */
    protected function registerAssets($view)
    {
        SunEditorAssets::register($view);
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
