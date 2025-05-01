<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
use app\modules\feature\widgets\FeatureWidget;
use yii\helpers\Html;

?>

<section class="content" itemscope
	itemtype="http://schema.org/BlogPosting">
	<div class="portfolio-heading-section">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="area-heading mt-5">
						<h1 class="area-title" itemprop="name headline"><?=Html::encode($model->title)?></h1>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- cx blog section start -->
<div class="cx-section cx-blog-section feature-page py-5">
	<div class="container-fluid">

		<div class="row">
			<div class="col-lg-8 col-md-8">
				<div class="row post-bar ">
					<div class="col-md-4 text-right blog-comment-section"></div>
				</div>
				<article class="blog-post post-single mt-0">
					<div class="row">
						<div class="col-md-12">
							<p class="mb-1">Share this post on: &nbsp;</p>
                        <?php
                        echo Yii::$app->controller->getSocialTags();
                        ?>
								</div>
					</div>
					<div class="post-thumbnail"></div>
					<div class="post-content">
						<div class="post-content-inner text-justify">  
                                 <?= $model->description?><br>
						</div>
					</div>
				</article>
			</div>

			<div class="col-lg-4 col-md-4">
						<?php echo  FeatureWidget::widget(['model'=>$model,'type_id' => FeatureWidget::LATEST_FEATURES]); ?>
						<?php echo  FeatureWidget::widget(['model'=>$model,'type_id' => FeatureWidget::UPCOMING_FEATURES]); ?>
						</div>
		</div>
	</div>
</div>

