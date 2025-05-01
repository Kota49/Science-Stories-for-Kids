<?php
use app\assets\AppAsset;
use app\components\gdpr\Gdpr;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
	<meta charset="<?= Yii::$app->charset ?>" />
	<?= Html::csrfMetaTags() ?>
	<title>
		<?= Html::encode($this->title) ?>
	</title>
	<?php $this->head() ?>
	<?php
	$this->registerLinkTag([
		'rel' => 'icon',
		'type' => 'image/png',
		'href' => $this->theme->getUrl('img/web_logo.svg')
	]);
	/* -- Plugins CSS -- */
	$this->registerCssFile($this->theme->getUrl('css/font-awesome.css'));
	/* --Theme CSS --- */
	$this->registerCssFile($this->theme->getUrl('css/themes.css'));

	$this->registerCssFile($this->theme->getUrl('css/auth.css'));


	?>
</head>

<body class="home-page <?= Yii::$app->controller->action->id == 'index' ? 'new_class' : '' ?>">

	<?php $this->beginBody() ?>
	<!-- ******HEADER****** -->

	<header class="py-2">
		<div class="container-fluid">
			<nav class="navbar navbar-expand-lg align-items-center p-0 w-100">
				<a class="navbar-brand" href="<?= Url::home(); ?>">
					<img src="<?= $this->theme->getUrl('img/web_logo.svg') ?>" alt="Img" class="logo">
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
					<span class="navbar-toggler-icon w-auto h-auto"><i class="fa fa-bars"></i></span>
				</button>
				<div class="collapse navbar-collapse" id="collapsibleNavbar">
					<ul class="navbar-nav ms-auto align-items-center d-lg-flex d-block">

						<?php if (User::isAdmin()) { ?>
							<li
								class="<?php echo (\Yii::$app->controller->id == 'site' && \Yii::$app->controller->action->id == 'info') ? 'active' : null; ?> nav-item">
								<a href="<?= Url::to(['site/info']); ?>" class="nav-link">Info</a>
							</li>
						<?php } ?>

						<?php if (User::isGuest()) { ?>

							<?php
						} else {
							?>
							<li class="nav-item nav-item-cta last ml-0 ml-lg-3"><a
									href="<?php echo Url::to(['dashboard/index']); ?>" class="nav-link"><button
										type="button" class="btn btn-primary nav-link">Dashboard</button></a></li>
						<?php } ?>
					</ul>
				</div>
			</nav>
		</div>
	</header>
	<!--//header-->
	<?= Gdpr::widget(); ?>
	<!-- body content start-->
	<div class="main_wrapper">
		<?= $content ?>
	</div>
	<!--body wrapper end-->
	<div class="footer-bottom">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12 text-center">
					<p class="mb-0">&copy;
						<?php echo date('Y') ?>
						<a href="<?= Url::home(); ?>">
							<?= Yii::$app->name ?>
						</a>
						| All Rights Reserved. Developed By <a target="_blank"
							href="<?= Yii::$app->params['companyUrl']; ?>">
							<?= Yii::$app->params['company'] ?>
						</a>
					</p>

				</div>
			</div>
		</div>
	</div>
	<!-- Javascript -->
	<?php $this->endBody() ?>

</body>
<?php $this->endPage() ?>

</html>