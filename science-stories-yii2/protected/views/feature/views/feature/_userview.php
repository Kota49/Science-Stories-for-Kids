<?php
use yii\helpers\StringHelper;

?>
<div class="benefits features-new col-md-6 col-sm-6 col-xs-12">
	<a href="<?= $model->getUrl() ?>">
		<div class="features-item item clearfix">
			<div class="icon col-md-3 col-xs-12 text-center">
				<span class="pe-icon pe-7s-network"></span>
			</div>
			<!--//icon-->
			<div class="content col-md-9 col-xs-12">
				<h3 class="title"> <?=$model->title;?> </h3>
				<p class="desc text-justify"><?=strip_tags(StringHelper::truncate($model->description, 200, '...'))?></p>
			</div>
			<!--//content-->
		</div> <!--//item-->
	</a>
</div>
