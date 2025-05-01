<?php
use app\components\useraction\UserAction;
use app\modules\page\models\Page;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model Page */

/* $this->title = $model->label() .' : ' . $model->title; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Pages'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="page-view">
        <?php
        echo \app\components\PageHeader::widget([
            'model' => $model
        ]);
        ?>
    </div>

	<div class="card ">
		<div class="card-body">
            <?php

            echo \app\components\TDetailView::widget([
                'id' => 'page-detail-view',
                'model' => $model,
                'options' => [
                    'class' => 'table table-bordered'
                ],
                'attributes' => [
                    'id',
                    'title',
                    [
                        'label' => Yii::t('app', 'Title') . ' in hebrew',
                        'format' => 'raw',
                        'value' => ! empty($model->getTranslation('he', 'title', $model)) ? $model->getTranslation('he', 'title', $model) : Yii::t('app', 'N/A')
                    ],
                    
                    /*'description:html',*/
                    [
                        'attribute' => 'type_id',
                        'value' => $model->getType()
                    ],
                    'created_on:datetime',
                    'updated_on:datetime',
                    [
                        'attribute' => 'created_by_id',
                        'format' => 'raw',
                        'value' => $model->getRelatedDataLink('created_by_id')
                    ]
                ]
            ])?>
                    <h4>Description(en)</h4>
            
            <?= HtmlPurifier::process($model->description); ?>
         
         <br> <br>

			<h4>Description(he)</h4>
         
         
         <?php

        $he_desc = ! empty($model->getTranslation('he', 'description', $model)) ? $model->getTranslation('he', 'description', $model) : '';

        echo $he_desc;

        ?>
            <div></div>

		</div>
	</div>
    <?php

    echo UserAction::widget([
        'model' => $model,
        'attribute' => 'state_id',
        'states' => $model->getStateOptions()
    ]);
    ?>
</div>