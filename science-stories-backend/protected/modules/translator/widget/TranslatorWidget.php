<?php
namespace app\modules\translator\widget;

use app\components\TBaseWidget;
use app\modules\translator\models\Translator;
use app\modules\translator\models\LanguageOption;
use app\components\TRichTextEditor;

class TranslatorWidget extends TBaseWidget
{

    const TYPE_FORM = "form";

    const TYPE_SAVE = "save";

    const TYPE_DISPLAY = "display";

    const TYPE_INPUT = "input";

    const TYPE_TEXTAREA = "textArea";

    const TYPE_EDITOR = "editor";

    public $name = "text";

    public $inputType = [];

    public $attribute;

    public $model;

    public $type;

    public $language = 'en';

    public $form;

    public $dataAttribute;

    public function run()
    {
        $this->checkType();
    }

    public function checkType()
    {
        switch ($this->type) {
            case self::TYPE_FORM:
                $this->form();
                break;
            case self::TYPE_SAVE:
                $this->save();
                break;
            case self::TYPE_DISPLAY:
                $this->display();
                break;
        }
    }

    public function form()
    {
        $data = [];
        
        $attr = [];
        $value = '';
        
        $model = new Translator();
        $languageModel = LanguageOption::findActive()->select('language_code')->all();
        if (! empty($languageModel)) {
            foreach ($languageModel as $language) {
                if (isset($this->model->id)) {
                    $data = Translator::find()->select([
                        'text',
                        'model_id'
                    ])
                        ->where([
                        'model_id' => $this->model->id,
                        'model_type' => $this->model::className(),
                        'language' => $language->language_code,
                        'attribute_type'=>$this->attribute,
                    ])
                        ->one();
                }
                if (! empty($data)) {
                    $value = $data->text;
                }
                $attr = "$this->name[$this->attribute][$language->language_code]";
                $this->checkInputType($model, $attr, $value, $language->language_code);
            }
        }
    }

    public function checkInputType($model, $attr, $value, $code)
    {
        if (isset($this->inputType['inputField'])) {
            
            switch ($this->inputType['inputField']) {
                case self::TYPE_TEXTAREA:
                    $this->textArea($model, $attr, $value, $code);
                    break;
                case self::TYPE_EDITOR:
                    $this->editor($model, $attr, $value, $code);
                    break;
                default:
                    $this->inputType($model, $attr, $value, $code);
            }
        } else {
            $this->inputType($model, $attr, $value, $code);
        }
    }

    public function inputType($model, $attr, $value, $code)
    {
        echo $this->form->field($model, $attr)
            ->textInput([
            'value' => $value
        ])
            ->label(ucfirst($this->attribute) . " in $code");
    }

    public function textArea($model, $attr, $value, $code)
    {
        echo $this->form->field($model, $attr)
            ->textArea([
            'value' => $value,
            'rows' => isset($this->inputType['rows']) ? $this->inputType['rows'] : 6,
            'cols' => isset($this->inputType['cols']) ? $this->inputType['cols'] : 10
        ])
            ->label(ucfirst($this->attribute) . " in $code");
    }

    public function editor($model, $attr, $value, $code)
    {
        echo $this->form->field($model, $attr)
            ->widget(TRichTextEditor::className(), [
            
            'options' => [
                'value'=>$value,   
                'rows' => 6
            ],
            'preset' => 'basic'
        ])
            ->label(ucfirst($this->attribute) . " in $code");
    }

    public function save()
    {
        if (isset($this->model->id)) {
            if (isset($_POST['Translator'][$this->name]) && (! empty($_POST['Translator'][$this->name]))) {
                $post = $_POST['Translator'][$this->name];
                if (isset($this->dataAttribute) && (! empty($this->dataAttribute))) {
                    foreach ($this->dataAttribute as $attr) {
                        if (isset($post[$attr]) && (! empty($post[$attr]))) {
                            foreach ($post[$attr] as $key => $value) {
                                    $model = Translator::find()->where([
                                        'model_id' => $this->model->id,
                                        'model_type' => $this->model::className(),
                                        'attribute_type' => $attr,
                                        'language' => $key
                                    ])->one();
                                    if (empty($model)) {
                                        $model = new Translator();
                                    }
                                    $model->attribute_type = $attr;
                                    $model->text = $value;
                                    $model->language = $key;
                                    $model->model_id = $this->model->id;
                                    $model->model_type = $this->model::className();
                                    if (! $model->save()) {
                                        return false;
                                    }
                                
                            }
                        }
                    }
                    return true;
                }
            }
        }
    }

    public function display()
    {
        if (isset($this->model->id)) {
            $language = \yii::$app->language;
            $model = Translator::find()->where([
                'model_id' => $this->model->id,
                'model_type' => $this->model::className(),
                'attribute_type' => $this->attribute,
                'language' => $language
            ])->one();
            
            if (! empty($model)) {
                echo $model[$this->name];
                return true;
            }
            echo $this->model[$this->attribute];
        }
    }
}