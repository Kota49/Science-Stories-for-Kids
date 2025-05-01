<?php 
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
?>
<div class="card notice-view">
   <div class="card-header">
      Notices
   </div>
   <?php //Pjax::begin(['id'=>'notices']); ?>
   <div id='notices' class="card-body ">
      <div class="content-list content-image menu-action-right">
         <ul class="list-wrapper">
            <?php
               echo \yii\widgets\ListView::widget([
                   'dataProvider' => $notices,
                   
                   // 'summary' => false,
                   
                   'itemOptions' => [
                       'class' => 'item'
                   ],
                   'itemView' => '_view',
                   'options' => [
                       'class' => 'list-view notice-list'
                   ]
               ]);
               ?>
         </ul>
      </div>
   </div>
   <?php //Pjax::end(); ?>
</div>
