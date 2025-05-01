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
 
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin ( [ 
		'id' => 'form-crud',
		// 'action' => Yii::$app->urlManager->createUrl ( '/crud/default/process' ),
		
		'attributes' => [ 
				'db_connection' => 'DB Connection' 
		] 
] );
?>

<div class="tugii-title">Yii2 Auto CRUD</div>


<div>
	<div class="tugii-info">
		Use whichever database connection to be queried. Default is "db". <br>
		This refers to "Yii::$app->db"
	</div>
    <?= $form->field($model, 'db_connection')?>
</div>
<div>
	<div class="tugii-info">Namespace path to the models directory. Default
		is automatically added.</div>
    <?= $form->field($model, 'models_path')?>
</div>
<div>
	<div class="tugii-info">Namespace path to the model search directory.
		Default is automatically added. This can be the same as the models
		path.</div>
    <?= $form->field($model, 'models_search_path')?>
</div>
<div>
	<div class="tugii-info">Namespace path to the controllers directory.
		Default is automatically added. Note, views will be added based on the
		controller path.</div>
    <?= $form->field($model, 'controllers_path')?>
</div>


<div>
	<div class="tugii-info">Overwrite existing controllers.</div>
    <?= $form->field($model, 'override_controllers')->checkbox()?>
</div>
<div>
	<div class="tugii-info">Comma delimited list of controllers to skip.
		Note, do NOT add .php</div>
    <?= $form->field($model, 'exclude_controllers')?>
</div>
<div class="form-group">
	<div class="">
        <?= Html::submitButton('Run', [ 'class' => 'btn btn-primary', 'name' => 'button-submit' ])?>
    </div>
</div>

<?php ActiveForm::end()?>





