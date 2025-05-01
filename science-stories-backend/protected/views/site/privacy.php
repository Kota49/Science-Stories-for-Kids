<?php
use app\components\PageWidget;

/* @var $this yii\web\View */
/*
 * $this->title = 'About';
 * $this->params ['breadcrumbs'] [] = $this->title;
 */
?>
<section class="pagetitle-section">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 text-center">
				<h1 class="mb-0 mt-0">Privacy Policy</h1>
			</div>
		</div>
	</div>
</section>
<div class="site-about">
	<div class="container-fluid">
		<div class="row other-wrapper ">
	<?php
if ($privacy) {
    echo $privacy->description;
} else {
    echo "Info will be available soon.";
}
?>
</div>
	</div>
</div>