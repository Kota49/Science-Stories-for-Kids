<?php
use app\assets\AppAsset;
use app\components\FlashMessage;
use app\components\TActiveForm;
use app\modules\shadow\components\ShadowWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\Menu;
use app\components\EmailVerification;
use app\base\TBaseFlashMessage;

/* @var $this \yii\web\View */
/* @var $content string */
// $this->title = yii::$app->name;

AppAsset::register($this);
$user = Yii::$app->user->identity;
?>
<?php
$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
	<meta charset="<?= Yii::$app->charset ?>" />
	<?= Html::csrfMetaTags() ?>
	<title>
		<?= Html::encode($this->title) ?>
	</title>
	<?php
	$this->head() ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<link rel="shortcut icon" href="<?= $this->theme->getUrl('img/web_logo.svg') ?>" type="image/png">
	<link href="<?php

	echo $this->theme->getUrl('css/style-admin.css') ?>" rel="stylesheet">
	<link href="<?php

	echo $this->theme->getUrl('css/responsive.css') ?>" rel="stylesheet">
	<link href="<?php

	echo $this->theme->getUrl('css/font-awesome.css') ?>" rel="stylesheet">
	<link href="<?php

	echo $this->theme->getUrl('css/glyphicon.css') ?>" rel="stylesheet">

</head>

<body class="sticky-header <?php

echo Yii::$app->session->get('is_collapsed') ?>">
	<?php
	$this->beginBody() ?>
	<section class="position-relative">
		<!-- sidebar left start-->
		<div class="sidebar-left  style-scroll">
			<!--responsive view logo start-->
			<div class="logo theme-logo-bg  d-block d-xl-none">
				<a href="<?= Url::home(); ?>" class="logo-hidden"> <img
						src="<?= $this->theme->getUrl('img/web_logo.svg') ?>" alt="Img" class="logo">
				</a> <a href="<?= Url::home(); ?>" class="logo-show text-white"> <img
						src="<?= $this->theme->getUrl('img/web_logo.svg') ?>" alt="Img" class="logo">
				</a>

			</div>
			<!--responsive view logo end-->
			<div class="sidebar-left-info">
				<!-- visible small devices start-->
				<div class=" search-field"></div>
				<!-- visible small devices start-->
				<!--sidebar nav start-->
				<?php
				if (method_exists($this->context, 'getItems')) {
					echo Menu::widget([
						'encodeLabels' => false,
						'activateParents' => true,
						'items' => $this->context->getItems(),
						'options' => [
							'class' => 'nav  nav-stacked side-navigation'
						],
						'submenuTemplate' => "\n<ul class='child-list'>\n{items}\n</ul>\n"
					]);
				}
				?>

				<!--sidebar nav end-->
			</div>
		</div>
		<!-- sidebar left end-->
		<!-- body content start-->
		<div class="body-content">
			<!-- header section start-->
			<div class="header-section topbar">
				<!--logo and logo icon start-->
				<div class="logo theme-logo-bg d-xl-block d-none">
					<a href="<?= Url::home(); ?>" class="logo-hidden"> <img
							src="<?= $this->theme->getUrl('img/web_logo.svg') ?>" alt="Img" class="logo">
					</a> <a href="<?= Url::home(); ?>" class="logo-show text-white"> <img
							src="<?= $this->theme->getUrl('img/web_logo.svg') ?>" alt="Img" class="logo">
					</a>
				</div>
				<!--logo and logo icon end-->
				<!--toggle button start-->
				<a class="toggle-btn"><i class="fa fa-outdent"></i></a>
				<!--toggle button end-->
				<!--mega menu start-->
				<div class='pull-left'>
					<div class="search-form">
						<?php
						$form = TActiveForm::begin([
							'layout' => 'inline',
							'id' => 'search-form',

							'action' => Url::toRoute('/search'),
							'method' => 'get'
						]);
						?>
						<?php

						echo Html::input('text', 'q', Yii::$app->request->getQueryParam('q', ''), [
							'placeholder' => Yii::t('app', 'Search')
						]) ?>
						<?php

						TActiveForm::end();
						?>
					</div>
				</div>
				<!--mega menu end-->
				<div class="notification-wrap">
					<!--right notification start-->
					<div class="right-notification">
						<ul class="notification-menu">
							<li><a href="javascript:;" class="dropdown-toggle" data-bs-toggle="dropdown">
							<span
									class="img-icn">
								<?php

												if (empty(Yii::$app->user->identity->profile_file)) {
													?>
													<img class="img-responsive"
														src="<?= $this->theme->getUrl('img/default.jpg') ?>" alt="">
													<?php

												} else {
													?>
													<?= Html::img($user->getImageUrl(50), ['class' => 'img-fluid', 'alt' => '']); ?>

													<?php
												}
												?>
								</span>

									<?php
									echo Yii::$app->user->identity->full_name;
									?>
									<span class=" fa fa-angle-down"></span>
								</a>
								<ul class="dropdown-menu dropdown-usermenu dropdown-user purple float-right">
									<li class="p-0 border">
										<div class="dw-user-box bg-info">
											<div class="u-img">
												<?php

												if (empty(Yii::$app->user->identity->profile_file)) {
													?>
													<img class="img-responsive"
														src="<?= $this->theme->getUrl('img/default.jpg') ?>" alt="">
													<?php

												} else {
													?>
													<?= Html::img($user->getImageUrl(), ['class' => 'img-fluid', 'alt' => $user]); ?>

													<?php
												}
												?>
											</div>
											<div class="u-text">
												<h4>Admin</h4>
												<p class="text-white mb-0">
													<?= Yii::$app->user->identity->email; ?>
												</p>
												<a href="<?= Url::toRoute(['user/view', 'id' => Yii::$app->user->identity->id]) ?>"
													class="btn btn-rounded btn-danger btn-sm d-none">View
													Profile</a>
											</div>
										</div>
									</li>
									<li><a href="<?php
									echo Yii::$app->user->identity->getUrl();
									?>"> <span class="fa fa-user float-right"></span> Profile
										</a></li>
									<li><a href="<?php
									echo Yii::$app->user->identity->getUrl('changepassword');
									?>"> <span class="fa fa-key float-right"></span> <span>Change
												Password</span>
										</a></li>
									<li><a href="<?php
									echo Yii::$app->user->identity->getUrl('update');
									?>"> <span class="fa fa-pencil float-right"></span> Update
										</a></li>
									<li><a href="<?php
									echo Url::toRoute([
										'/user/logout'
									]);
									?>"> <span class="fa fa-sign-out float-right"></span> Log Out
										</a></li>
									<?php

									if (isset(Yii::$app->params['bug-report-link'])) {
										?>
										<li><a href="<?= Yii::$app->params['bug-report-link']; ?>"> <i
													class="fa fa-sign-out float-right"></i> Report a Problem
											</a></li>
										<?php

									}
									?>
								</ul>
							</li>
						</ul>
					</div>
					<!--right notification end-->
				</div>
			</div>
			<!-- header section end-->
			<!-- page head start-->
			<?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
			<!--body wrapper start-->
			<section class="main_wrapper">
				<?= TBaseFlashMessage::widget(['type' => 'default']) ?>
				<?= ShadowWidget::widget() ?>
				<?= EmailVerification::widget() ?>
				<?= $content; ?>
			</section>
			<footer>
				<div class="row">
					<div class="col-md-12 text-center">
						<p class="mb-0">&copy;
							<?php

							echo date('Y') ?> <a href="<?= Url::home(); ?>">
								<?= Yii::$app->name ?>
							</a> | All Rights Reserved. Developed By <a target="_blank"
								href="<?= Yii::$app->params['companyUrl']; ?>">
								<?= Yii::$app->params['company'] ?>
							</a>
						</p>
					</div>
				</div>
			</footer>
			<!--body wrapper end-->
		</div>
		<!-- body content end-->
	</section>
	<!--common scripts for all pages-->
	<script src="<?php

	echo $this->theme->getUrl('js/scripts.js') ?>"></script>
	<script src="<?php

	echo $this->theme->getUrl('js/custom-modal.js') ?>"></script>
	<script src="<?php

	echo $this->theme->getUrl('js/sidebar.js') ?>"></script>
	<script type="text/javascript"
		src="https://cdnjs.cloudflare.com/ajax/libs/jstimezonedetect/1.0.7/jstz.min.js"></script>

	<script type="text/javascript">
		/* Get Current time on the basis of current country */
		$(document).ready(function () {
			var now = new Date();
			var time = now.getTime();
			var expireTime = time + 1000 * 36000;
			now.setTime(expireTime);

			timezone = jstz.determine()
			var tz = timezone.name();
			document.cookie = "timezone=" + tz + ";expires=" + now.toUTCString() + ";path=/";
			var cookie = getCookie('timezone');

			function getCookie(c_name) {
				if (document.cookie.length > 0) {
					c_start = document.cookie.indexOf(c_name + "=");
					if (c_start != -1) {
						c_start = c_start + c_name.length + 1;
						c_end = document.cookie.indexOf(";", c_start);
						if (c_end == -1) {
							c_end = document.cookie.length;
						}
						return unescape(document.cookie.substring(c_start, c_end));
					}
				}
				return "";
			}
		});
	</script>

	<?php
	$this->endBody() ?>
</body>
<?php
$this->endPage() ?>

</html>