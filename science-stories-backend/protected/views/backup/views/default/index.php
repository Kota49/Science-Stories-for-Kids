<div class="wrapper main-content-spacing">

	<div class="backup-default-index">

		<?php
		$this->params['breadcrumbs'][] = [
			'label' => 'Manage',
			'url' => array(
				'index'
			)
		];
		?>

		<?php if (Yii::$app->session->hasFlash('success')): ?>
			<div class="alert alert-success">
				<?php echo Yii::$app->session->getFlash('success'); ?>
			</div>
		<?php endif; ?>
		<div class="card">
			<header class="card-header form-spacing d-flex align-items-center justify-content-between flex-wrap">
				<h4 class="clearfix">
					Manage database backup files
				</h4>
				<span class="pull-right"> <a href="<?= \yii\helpers\Url::toRoute(['create']) ?>"
						class="btn btn-success"> <i class="fa fa-plus"></i> Create
						Backup
					</a> <a href="<?= \yii\helpers\Url::toRoute(['create']) ?>?data=0" class="btn btn-success"> <i
							class="fa fa-plus"></i> Create
						Structure
					</a>
				</span>
			</header>
			<div class="card-body">

				<?php

				echo $this->render(
					'_list',
					array(
						'dataProvider' => $dataProvider
					)
				);
				?>


			</div>

		</div>


	</div>
</div>