
   <!--- header Start-->
   <!DOCTYPE html>
<html>
   <head>
      <title>Yii2 base </title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
      <style type="text/css">
      @font-face {
        
         font-family: 'OpenSans-Regular';
          src: url(<?php echo Yii::$app->urlManager->createAbsoluteUrl('/');  ?>/themes/new/fonts/OpenSans-Regular.ttf),
          url(<?php echo Yii::$app->urlManager->createAbsoluteUrl('/');  ?>/themes/new/fonts/opensans-regular.woff),
          url(<?php echo Yii::$app->urlManager->createAbsoluteUrl('/');  ?>/themes/new/fonts/opensans-regular.woff2);
          font-weight: normal;
         font-style: normal;
                  }

         
         @media only screen and (max-device-width: 480px), only screen and (max-width: 480px) {
         td[class='column'],
         td[class='column'] { 
         float: left !important; 
         display: block !important;
         border-right: 0 !important; width: 100%
         }
         td[class='td'] { 
         width: 100% !important; 
         min-width: 100% !important; 
         }
         table  {
         margin: auto;
         width: 100%;
         }
         }
     
      </style>
   </head>
   <body style="background-color:#FFF0DE;color: #202020;font-size: 15px;line-height: 24px; font-family: 'Roboto', sans-serif;">
   <br>
   <table style="width: 600px;margin: 0 auto;height: 100%; background-color: #fff; border-spacing: 0;  font-family: 'Roboto', sans-serif;">
      <tr>
         <td>
            <table style="width:100%;padding: 5px 10px; border-bottom: 1px solid #eee;">
               <tr>
                  <td style="width: 30%; padding-top:10px;"><a href="index.php"> <img src="./images/logo.svg" alt="logo" width="60px"> </a></td>
                  <td style="color: #4a4a4a; width: 70%; font-weight: 400; text-align: right;">
                     <p>Jan 15<sup>th</sup>, 2024 </p>
                  </td>
               </tr>
            </table>
         </td>
      </tr>
