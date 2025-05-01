<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$Link = $user->getLoginUrl();
?>

<!--- body start-->
<tr>
   <td style="background-color: #FE9723; " align="center">
      <table style="width: 100%; margin: 0 auto; ">

         <tr>
            <td style="width: 100%; text-align: center; padding-top: 15px; padding-bottom:15px;" colspan="2">
               <h1 style="letter-spacing: 0.5px;color: #ffffff;line-height: 1.3; text-transform: uppercase; margin: 0;">
                  Warm Greetings ! !
               </h1>
            </td>

         </tr>

      </table>

   </td>
</tr>
<tr style="width: 100%;  ">
   <td style="padding: 0;">
      <table style="width: 100%; border-spacing: 0; padding: 20px 28px 0px;">
         <tr>
            <td colspan="2" style="padding: 0;">
               <img src="./images/testimnl.png" alt="img" height="100px" width="100px" style="object-fit: cover; border-radius: 50%;">
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
               <?php echo Html::encode($user->full_name) ?>,
            </h2>
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
               <p> Thank you for registering with
               <?php echo Yii::$app->name ?>
               </p>
               <p style="margin: 20px 0 30px;">
                  <a href="<?= Html::encode($Link) ?>" style="background-color: #6eb356; border-radius: 3px; color: #fff;
                  display: inline-block;font-size:16px; font-weight: normal;
                  line-height: 40px; text-align: center; text-decoration: none; width: 100px; -webkit-text-size-adjust: none;
                  border: 1px solid #6eb356;
                  " target="_blank">LOG IN</a>
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
