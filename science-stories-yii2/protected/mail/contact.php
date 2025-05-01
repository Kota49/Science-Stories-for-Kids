<?php

use yii\helpers\Html;

include('header.php') ?>




<!--- body start-->
<tr>
         <td style="background-color: #FA8C0B; " align="center">
            <table style="width: 100%; margin: 0 auto; ">

               <tr>
                  <td style="width: 100%; text-align: center; padding-top: 15px; padding-bottom:15px;" colspan="2">
                     <h1
                        style="letter-spacing: 0.5px;color: #ffffff;line-height: 1.3; text-transform: uppercase; margin: 0;">
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
                     <h2 style="margin: 0;"> Hi
			<?php echo Html::encode($user->title) ?>,</h2>
                  </td>
               </tr>
               <tr>
                  <td style="padding-top: 15px; color: #868686;">
                     <span style="margin: 0; font-weight: 400; font-size: 21px;">
                        Welcome to <b style="color: #fa8c0b;"><?= Yii::$app->name; ?></b>
                     </span>

                  </td>
               </tr>
               <tr>
                  <td style="padding-top: 0; color: #202020;">
                     <span style="margin: 0; font-weight: 400; font-size: 15px;">
                     Thank you for reaching us , we have received your request and our
			representative will get back to you on following details .
                     </span>

                  </td>
               </tr>
            </table>
         </td>
      </tr>

      <tr>
            <td style="padding: 0px 28px; ">
               <table style="width: 100%;">
                  <tr>
                     <td style="border-bottom: 1px solid #828282; padding: 10px;" width="50%">
                        <b>User Name</b>
                     </td>
                     <td style="border-bottom: 1px solid #828282; padding: 10px;" width="50%">
                     <?php echo Html::encode($user->title) ?>
                     </td>
                  </tr>
                  <tr>
                     <td style="border-bottom: 1px solid #828282; padding: 10px;" width="50%">
                        <b> Email</b>
                     </td>
                     <td style="border-bottom: 1px solid #828282; padding: 10px;" width="50%">
                     <?php echo Html::encode($user->message) ?>
                     </td>
                  </tr>
                 
                  <tr>
                     <td style=" padding: 10px;" colspan="2">
                        <b>Message</b>
                        <p style="margin-top: 8px ; " >
                           <?php echo Html::encode($user->message) ?>
                        </p>
                     </td>
                  </tr>
            

               </table>    
            </td>
      </tr>  
    
      <tr>
         <td style="padding: 12px 20px; ">
            <table style="background-color: #fa8c0b; width: 100%;">

               <tr>
                  <td style="padding: 15px 15px; color: #fff;" align="center">
                     <span style="margin: 0; font-weight: 400; font-size: 20px;">
                        Thank you for joining the <b><?= Yii::$app->name; ?></b> family!
                     </span>

                  </td>
               </tr>

            </table>
         </td>
      </tr>
<!--- body end-->






<?php include('footer.php') ?>