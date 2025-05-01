<?php
   /* @var $this yii\web\View */
   use app\modules\contact\widgets\ContactWidget;
   
   // $this->title = "Contact Us ";
   
   ?>
<section class="pagetitle-section">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-12 text-center">
            <h1 class="mb-0 mt-0">Contact Us</h1>
         </div>
      </div>
   </div>
</section>
<section class="py-5 clr-green contact-section">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-12 text-center">
            <h2>Get in touch</h2>
            <p class="mb-5">We have a great customer support team who would love
               to hear from you.
            </p>
         </div>
      </div>
      <div class="card-group contact-group d-lg-flex d-block">
         <div class="card">
            <ul class="d-flex">
               <li class="mr-4">
                  <div class="icon">
                   <span class="fa fa-envelope"></span>
                  </div>
               </li>
               <li>
                  <div class="details">
                  <h4 class="heading-title">Email:</h4>
                  <p>info@jitalent.com</p>
               </div>
               </li>
            </ul>
         </div>
         <div class="card mx-lg-4 mx-0">
             <ul class="d-flex">
               <li class="mr-4">
                   <div class="icon">
                  <span class="fa fa-map-marker"></span>
               </div>
               </li>
               <li>
                  <div class="details">
                  <h4 class="heading-title">Find Us at:</h4>
                  <p class="address">jiWeb Technologies LLP. C-127, Phase 8,
                     Industrial Area, Sector 72, Mohali, Punjab
                  </p>
               </div>
               </li>
            </ul>
          
         </div>
         <div class="card">
             <ul class="d-flex">
               <li class="mr-4">
                 <div class="icon">
                  <span class="fa fa-phone"></span>
               </div>
               </li>
               <li>
                   <div class="details">
                  <h4 class="heading-title">Contact number:</h4>
                  <p>
                     Phone :+91 956 922 7788<br /> Phone :+1 844 912 7788
                  </p>
               </div>
               </li>
            </ul>
         </div>
      </div>
      <div class=" mt-4 p-5 bg-white">
         <h3 class="title text-center">Contact Form</h3>
         <div class="contact-form">
            <?php echo ContactWidget::widget();?>
         </div>
      </div>
   </div>
</section>