<?php
use app\modules\feature\models\Feature;
use app\modules\feature\models\Type;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = $this->title;
?>

<section class="content py-5">
	<div class="container-fluid">
		<?php

		$index = Feature::findActive()->andWhere([
			'order_id' => 0
		])->one();
		if (!empty($index)) {
			?>
			<div class="row">
				<div class="col-xs-12 col-sm-12">
					<div class="features-block_One">
						<div class="text-center">
							<h1 class="section-title">Features</h1>
							<hr />
						</div>
					</div>

					<div class="single-video">
						<div class="video-wrapper">
							<?php echo isset($index->description) ? $index->description : "Not Found"; ?>

						</div>
					</div>

				</div>
			</div>
		<?php } ?>
		<?php

		$types = Type::findActive()->andWhere([
			'state_id' => Type::STATE_ACTIVE
		]);

		?>
	</div>

</section>
<section class="features-section">
	<div class="container-fluid">
		<?php foreach ($types->each() as $type) { ?>
			<div class="row">
				<div class="col-md-12">
					<div class="features-block_One">
						<div class="text-center">
							<h2 class="section-title">
								<?php echo isset($type->title) ? $type->title : "Not Found"; ?>
							</h2>

						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?php

					$features = Feature::findActive()->andWhere([
						'type_id' => $type->id
					])->orderBy([
								'order_id' => SORT_DESC
							]);
					?>
					<div class="jischool-erp">
						<div class="row">
							<?php foreach ($features->each() as $feature) { ?>
								<div class="col-md-6 col-sm-12 col-lg-4">

									<div class="features-inner min-height">
										<div class="img-wrap">
											<?php
											if (!empty($feature->icon)) {
												echo Html::img($this->theme->getUrl('/assets/img/features-icon/' . $feature->icon . '.png'), [
													'alt' => $feature->title,
													'itemprop' => 'image'
												]);
											}
											?>
										</div>
										<div class="f-desc">
											<h3>
												<a href="<?= $feature->getUrl() ?>">
													<?php echo isset($feature->title) ? $feature->title : "Not Found"; ?>
												</a>
											</h3>
											<ul>
												<?= $feature->summary ?>
											</ul>
										</div>
									</div>

								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</section>