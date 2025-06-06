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
use yii\widgets\Pjax;

?>
		<div class="col-md-offset-2 col-md-8">
			<div class="blog-comment">
		    <?php Pjax::begin(['id'=>'message-chat']); ?>
				<h3 class="text-success">Messages</h3>
				<hr />
				<ul class="comments">
				<?=\yii\widgets\ListView::widget(['dataProvider' => $messages,'summary' => false,'itemOptions' => ['class' => 'item'],'itemView' => '_view','options' => ['class' => 'comments']]);?>
				</ul>
				<?php if ($model &&  !Yii::$app->user->isGuest) {?>
					<?=$this->render ( '_form', [ 'model' => $model ] )?>
    			<?php }?> 
   			 <?php Pjax::end(); ?>
			</div>
		</div>
