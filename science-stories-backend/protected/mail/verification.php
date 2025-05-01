<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user @app\models\User */

$link = $user->getVerified();

?>

<!--- body start-->

<tr style="width: 100%;  ">
	<td style="padding: 0;">
	   <table style="width: 100%; border-spacing: 0; padding: 20px 28px 0px;">
		  <tr>
			 <td colspan="2" style="padding: 0;">
				<img src="./images/testimnl.png" alt="img" height="100px" width="100px" style="object-fit: cover; border-radius: 50%; ">
			 </td>
		  </tr>
	   </table>
	</td>
 </tr>
 <tr>
	<td style="padding: 12px 28px; ">
	   <table style="width: 100%;">
		  <tr>
			 <td>
				<h2 style="margin: 0;">Hi
				   <?php echo Html::encode($user->full_name) ?>,</h2>
			 </td>
		  </tr>
		  <tr>
			 <td style="padding-top: 15px; color: #868686;">
				<span style="margin: 0; font-weight: 400; font-size: 21px;">
				   Welcome to <b style="color: #FE9723;"><?= Yii::$app->name; ?> </b>
				</span>
 
			 </td>
		  </tr>
 
		  <tr>
			 <td>
				<p> Thanks for signing
			 up. To continue, please confirm your email address by clicking the
			 button below.
				</p>
				<p style="padding: 10px 0px">
			 <a style="display: inline-block; text-decoration: none; background-color: #fca600; padding:10px 20px;border: 1px solid #fca600; border-radius: 3px; color: #000; font-weight:500;"
				 href="<?= $link ?>" target="_blank">Verify Email</a>
		 </p>
	   <p style="margin-bottom: 20px;">If
			 above link isn't working, please copy and paste it directly in you
			 browser's URL field to get started.</p>
		 <p style="margin-bottom: 20px;">
			 <a href="<?php echo $link; ?>" style="color: #3d9c68; font-size: 14px;">
				 <?php echo $link; ?>
			 </a>
		 </p>
			 
			 </td>
		  </tr>
	   </table>
	</td>
 </tr>
 
 
 
 <tr>
	<td style="padding: 12px 20px; ">
	   <table style="background-color: #FE9723; width: 100%;">
 
		  <tr>
			 <td style="padding: 15px 15px; color: #fff;" align="center">
				<span style="margin: 0; font-weight: 400; font-size: 20px;">
				   Thank you for joining the <b><?= Yii::$app->name; ?> </b> family!
				</span>
 
			 </td>
		  </tr>
 
	   </table>
	</td>
 </tr>

<!--body end-->
