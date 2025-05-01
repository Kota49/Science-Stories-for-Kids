<?php
use yii\helpers\Html;
use devgroup\jsoneditor\Jsoneditor;

/**
 *
 * @var $alias string
 * @var $file string
 * @var $messages string
 * @var $this \yii\web\View
 */

$this->title = Yii::t('app', 'Update messages "{alias}"', [
    'alias' => $alias
]);
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('app', 'I18n'),
        'url' => 'index'
    ],
    $this->title
];

?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <?=Html::beginForm()?>
       
            <?php

            echo Jsoneditor::widget([
                'editorOptions' => [
                    'modes' => [
                        'tree'
                    ]
                ],
                'name' => 'messages',
                'options' => [
                    'style' => 'height: 600px'
                ],
                'value' => $messages
            ])?>
            <div>
              
            </div>
                <?=Html::submitButton(Yii::t('app', 'Update'), ['id' => 'translator-form-submit','class' => 'btn btn-primary'])?>
        
    <?=Html::endForm()?>
</div>
