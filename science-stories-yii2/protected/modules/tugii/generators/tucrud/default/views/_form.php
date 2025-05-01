<?php
   /**
    *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
    *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
    *
    * All Rights Reserved.
    * Proprietary and confidential :  All information contained herein is, and remains
    * the property of ToXSL Technologies Pvt. Ltd. and its partners.
    * Unauthorized copying of this file, via any medium is strictly prohibited.
    *
    */
   
   /* @var $this yii\web\View */
   /* @var $generator yii\gii\generators\crud\Generator */
   
   /* @var $model \yii\db\ActiveRecord */
   $model = new $generator->modelClass();
   $safeAttributes = $model->safeAttributes();
   if (empty($safeAttributes)) {
       $safeAttributes = $model->attributes();
   }
   
   $tableSchema = $generator->getTableSchema();
   
   $count = 0;
   $match = '/^(id|pending_on|create_time|created_on|created_by|create_user_id|created_by_id|update_time|updated_on|actual_start\|actual_end)/i';
   // foreach ($generator->getColumnNames() as $attribute) {
   
   foreach ($tableSchema->columns as $column) {
       if (! preg_match($match, $column->name)) {
           $count ++;
       }
   }
   echo "<?php\n";
      ?>
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model <?=ltrim ( $generator->modelClass, '\\' )?> */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?="<?php echo "?>strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?="<?php "?>
   $form = TActiveForm::begin([
   <?php if ($count > 8){ echo '//'; }?> 
   'id' => '<?=$generator->getControllerID ()?>-form',
   ]);
   echo $form->errorSummary($model);    
   <?php
      echo '?>';
   ?>

   <?php
      // echo $count;
      $index = 0;
      
      $mid = round($count / 2);
      // echo $mid;
      foreach ($tableSchema->columns as $column) :
          $attribute = $column->name;
          // foreach ($generator->getColumnNames() as $attribute) :
          
          if (preg_match($match, $attribute))
              continue;
          
          ?>
   <?php
      if ($count > 8 && $index == 0) {
          ?>
   <div class="col-md-6">
      <?php
         } elseif ($count > 8 && $index == $mid) {
             ?>
   </div>
   <div class="col-md-6">
      <?php
         }
         ?>
      <?php
         if ($column->name == 'state_id') {
             echo '<?php if(User::isAdmin()){?>';
      }
      if ($column->allowNull) {
      ?>
      <?php
         echo "<?php /*echo " . $generator->generateActiveField($attribute) . " */ ?>\n";
      ?>
      <?php
         } else {
             ?>
      <?php
         echo "<?php echo " . $generator->generateActiveField($attribute) . " ?>\n";
      ?>
      <?php
         }
         if ($column->name == 'state_id') {
             echo '<?php }?>';
      }
      ?>
      <?php
         $index ++;
         if ($count > 8 && $index == $count) {
             ?>
   </div>
   <?php
      }
      ?>
   <?php
      endforeach
      ;
      ?>
   <div
      class="col-md-12 text-right">
      <?="<?= "?>Html::submitButton($model->isNewRecord ? <?=$generator->generateString ( 'Save' )?> : <?=$generator->generateString ( 'Update' )?>, ['id'=> '<?=$generator->getControllerID ()?>-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?="<?php "?>TActiveForm::end(); ?>
</div>