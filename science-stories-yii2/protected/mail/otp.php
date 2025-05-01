<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user @app\models\User */

$Link = $user->getVerified();

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
               <p> Thanks for signing up. To continue, please enter the following code to verify
			your email.
               </p>
              <h3>OTP: <span style="font-size:16px; font-weight:400;"><?= $user->otp ?></span> </h3>
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