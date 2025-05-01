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
   
   use yii\helpers\Inflector;
   use yii\helpers\StringHelper;
   
   /* @var $this yii\web\View */
   /* @var $generator yii\gii\generators\crud\Generator */
   
   $urlParams = $generator->generateUrlParams ();
   
   echo "<?php\n";
      ?>
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
use app\components\TActiveForm;
/* @var $this yii\web\View */
/* @var $model <?=ltrim ( $generator->modelClass, '\\' )?> */
<?php if(!empty($generator->moduleID)){?>
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->moduleID)))) ?>, 'url' => ['/<?php echo $generator->moduleID;?>']];
<?php }?>
$this->params['breadcrumbs'][] = ['label' => <?=$generator->generateString ( Inflector::pluralize ( Inflector::camel2words ( StringHelper::basename ( $generator->modelClass ) ) ) )?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = (string)$model;
?>
<div class="wrapper">
   <div class="card">
      <div class="<?=Inflector::camel2id ( StringHelper::basename ( $generator->modelClass ) )?>-view card-body">
         <h4 class="text-danger">Are you sure you want to delete this item? All related data is deleted</h4>
         <?="<?php echo "?> \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
   </div>
   <div class="card">
      <div class="card-body">
         <?="<?php echo "?>\app\components\TDetailView::widget([
         'id'	=> '<?=Inflector::camel2id ( StringHelper::basename ( $generator->modelClass ) )?>-detail-view',
         'model' => $model,
         'options'=>['class'=>'table table-bordered'],
         'attributes' => [
         <?php
            $classname = $generator->modelClass;
            $hasOneRelations = $classname::getHasOneRelations ();
            
            if (($tableSchema = $generator->getTableSchema ()) === false) {
            	foreach ( $generator->getColumnNames () as $name ) {
            
            		if (isset ( $hasOneRelations [$name] ))
            			$name = $hasOneRelations [$name] [0];
            		if (preg_match ( '/^(description|content|password|activation_key)/i', $name ))
            
            			echo "           /* '" . $name . "',*/\n";
            		else
            			echo "            '" . $name . "',\n";
            	}
            } else {
            
            	foreach ( $tableSchema->columns as $column ) {
            		if (isset ( $hasOneRelations [$column->name] )) {
            			$column_out = "[" . "
            			'attribute' => '$column->name',
            			'format'=>'raw',
            			'value' => \$model->getRelatedDataLink('$column->name'),
            			" . "]";
            		} else {
            			$column_out = $generator->generateDetailViewColumn ( $column );
            		}
            		if (preg_match ( '/^(title|state_id|description|content|password|activation_key)/i', $column->name ))
            			echo "            /*" . $column_out . ",*/\n";
            		else
            			echo "            " . $column_out . ",\n";
            	}
            }
            ?>
         ],
         ]) ?>
         <?php
            echo "<?php  ";
               foreach ( $tableSchema->columns as $column ) {
               	$column_out = $generator->generateDetailViewColumn ( $column );
               	if (preg_match ( '/^(description|content)/i', $column->name ))
               		echo 'echo $model->' . $column->name . ';';
               }
               ?>?>
         <?="<?php "?>
         $form = TActiveForm::begin([
         'id'	=> '<?=$generator->getControllerID ()?>-form',
         'options'=>[
         'class'=>'row'
         ]
         ]);
         echo $form->errorSummary($model);	
         <?php
            echo '?>';
         ?>
         <div class="col-md-12 text-right">
            <?="<?= "?>Html::submitButton('Confirm', ['id'=> '<?=$generator->getControllerID ()?>-form-submit','class' =>'btn btn-success']) ?>
         </div>
         <?="<?php "?>TActiveForm::end(); ?>
      </div>
   </div>
   <?php
      $classname = $generator->modelClass;
      
      if (count ( $classname::getHasManyRelations () ) != 0) {
      	?>
   <div class="card">
      <div class="card-body">
         <div
            class="<?=Inflector::camel2id ( StringHelper::basename ( $generator->modelClass ) )?>-panel">
            <?php
               echo "<?php\n";
                  ?>
            $this->context->startPanel();
            <?php
               foreach ( $classname::getHasManyRelations () as $field => $relationClass ) {
               	?>
            $this->context->addPanel('<?=ucfirst ( $relationClass [0] )?>', '<?=$relationClass [0]?>', '<?=$relationClass [1]?>',$model /*,null,true*/);
            <?php
               }
               ?>
            $this->context->endPanel();
            ?>
         </div>
      </div>
   </div>
   <?php
      }
      ?>
   <?="<?php echo "?>CommentsWidget::widget(['model'=>$model]); ?>
</div>