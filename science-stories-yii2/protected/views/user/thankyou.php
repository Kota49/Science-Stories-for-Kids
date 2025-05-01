<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PaymentGateway */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<div class="container mt-5">
	<div class="row justify-content-center">
		<div class="col-lg-8">
			<div class="bg-white p-5 box-shadow thanks-div text-center">

				<h3 class="thanks-text">
			 <?php echo \Yii::t('app', "Thank You !!"); ?> 
    		</h3>
				<p> 
					<?= \Yii::t('app', 'If your email is valid, You would have received reset password instructions.') ?> </p>

				<a href="<?= Url::home() ?>" class="btn btn-success">Go home</a> 
				<a href="<?= Url::toRoute(['user/recover']) ?>" class="btn btn-primary">Resend</a>

			</div>
		</div>

	</div>
</div>