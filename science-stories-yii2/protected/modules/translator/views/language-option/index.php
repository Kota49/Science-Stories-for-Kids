<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\components\TActiveForm;
use app\modules\translator\models\LanguageOption;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\LanguageOption */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* $this->title = Yii::t('app', 'Index');*/
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Language Options'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');;
?>
<div class="wrapper">
	<div class="user-index">
		<div class=" panel ">
			
				<div
					class="language-option-index">

<?=  \app\components\PageHeader::widget(); ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
  </div>
			
		</div>
		<div class="panel panel-margin">
			<div class="panel-body">
				<div class="content-section clearfix card d-block mt-4">
				
				
					<header class="panel-heading head-border card-header">   <?php echo strtoupper(Yii::$app->controller->action->id); ?> </header>
					<div class="card-body">
					
					<div class="panel-body">

    <?php 
$form = TActiveForm::begin([
	'layout' => 'horizontal',
	'id'	=> 'language-option-form',
    'action'=>Url::toRoute(['add'])
]);
						
						
echo $form->errorSummary($model);	
?>


         
		  <?php echo $form->field($model, 'language_code')->dropDownList($model->getLanguageCode(),['maxlength' => 255]) ?>
	 		


		 <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
	 		



	   <div class="form-group">
		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'language-option-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
					
					
					
					
		<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
</div>

</div>
			</div>
		</div>
	</div>

</div>

