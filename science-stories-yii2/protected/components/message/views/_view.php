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
<li class="clearfix">
	<img src="https://bootdey.com/img/Content/user_1.jpg" class="avatar" alt="">
	<div class="post-comments">
	<?php if(isset($model->created_by_id)) {?>
		<p class="meta"> 
		<?= \yii::$app->formatter->asDatetime($model->created_on)?> 
		<?= $model->createdBy->linkify() ?> says : 
		</p>
	<?php }else{?>
		<p class="meta"> 
		<?= \yii::$app->formatter->asDatetime($model->created_on)?>
		<?= $model->getModel()->linkify() ?> says : 
		
		</p>
	<?php }?>
		 <?= $model->message?>
	</div>
</li>
