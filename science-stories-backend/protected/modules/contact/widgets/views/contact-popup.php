<?php
use app\components\TActiveForm;
use app\modules\contact\models\Address;
use app\modules\contact\models\Information;
use app\modules\contact\models\SocialLink;
use borales\extensions\phoneInput\PhoneInput;
use yii\helpers\Html;
use app\modules\contact\assets\ContactAsset;
use yii\helpers\Url;
$bundle = ContactAsset::register(Yii::$app->view);
?>
<div id="getintouchmodal" class="modal fade">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body p-0">
				<button type="button" class="close" data-dismiss="modal">
					<i class="fal fa-times"></i>
				</button>

				<div class="row m-0">

				<div class="col-lg-6 contact-info p-0">
					
					<a href="<?=Url::toRoute('/event')?>"><img src="<?=$this->theme->getUrl('/img/pop-up/event/event-popup.gif')?>" class="img-fluid anniversary-img" alt="popup"></a>
				
				</div>
					<!-- Contact Deatils
					 <div class="col-lg-6 contact-info">
						<h4 class="text-light contact-heading">Let's get in Touch</h4>
						<p class="text-light para">Interested in our services? Just pick
							up the phone to talk to our sales team.</p>
						<ul class="footer-address-list ftr-details link-hover">
							 <?php
        $addressQuery = Address::findActive();
        $add = $addressQuery->one();
        if ($add) {
            ?>
         <?php }?>
								<li><span><i class="fas fa-envelope"></i></span>
								<p>
									<span> <a href="mailto:<?=$add->email?>"><?=$add->email?></a></span>
								</p></li>
							<li><span><i class="fas fa-phone-alt"></i></span>
								<p>
										<?php
        $sales = $add->activeContacts;
        foreach ($sales as $sale) {
            ?>
                                  <span><?= $sale->getContactLink()?></span> 
                                  <?php }?>
									</p></li>
							<li><span><i class="fas fa-map-marker-alt"></i></span>
								<p>
									<span><?=$add->address?></span>
								</p></li>
						</ul>
						<div class="footer-social-media-icons">
								<?php if($linkModel = SocialLink::getLinkModel('facebook')){?>
								<a href="<?= $linkModel->ext_url?>" target="blank"><i
								class="fab fa-facebook"></i></a> 
							<?php } ?>
							<?php if($linkModel = SocialLink::getLinkModel('twitter')){?>
								<a href="<?=$linkModel->ext_url?>" target="blank"><i
								class="fab fa-twitter"></i></a> 
							<?php }?>
							<?php if($linkModel = SocialLink::getLinkModel('linkedin')){?> 
								<a href="<?=$linkModel->ext_url?>" target="blank"><i
								class="fab fa-linkedin"></i></a> 
									<?php }?>
									<?php if($linkModel = SocialLink::getLinkModel('instagram')){?> 
								<a href="<?=$linkModel->ext_url?>" target="blank"><i
								class="fab fa-instagram"></i></a> 
									<?php }?>

									<?php if($linkModel = SocialLink::getLinkModel('youtube')){?>  
								<a href="<?=$linkModel->ext_url?>" target="blank"><i
								class="fab fa-youtube"></i></a>
									<?php }?>
							</div>
					</div> -->
					<div class="col-lg-6 contact-box shadow-none border-left">
						<h4 class="contact-heading d-block d-lg-none">Let's get in Touch</h4>

							<?php
    $form = TActiveForm::begin([
        'id' => 'contact-popup',
        'enableAntispamTags' => true,
        'action' => 'contact/information/info-address'
    ]);

    ?>
	            	<?php echo $form->field($model, 'type_id')->hiddenInput(['value' => Information::TYPE_QUICK])->label(false) ?>
					<?php echo $form->field($model, 'full_name')->textInput(['maxlength' => 255])->label('Name')?>
					<?php echo $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
					<?php

    echo $form->field($model, 'mobile')
        ->input('tel', [
        'id' => "phone_number",
        'maxlength' => 10
    ])
        ->widget(PhoneInput::className(), [
        'options' => [
            'id' => 'phone_number'
        ],
        'jsOptions' => [
            'separateDialCode' => true,
            'autoPlaceholder' => 'off',
            'initialCountry' => 'IN'
        ]
    ]);
    ?>

					<?php echo $form->field($model, 'description')->textarea(['rows' => 4]);  ?>
					<div class="col-lg-12 col-md-12 text-center">
							<div class="text-center">
							<?php
    echo Html::submitButton('Send Message', [
        'class' => 'mt-20 butn btn-gredient',
        'id' => 'contact-form-submit',
        'name' => 'submit-button'
    ])?>
						</div>
						</div>
					<?php TActiveForm::end(); ?>
						</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="toxsl-years-popup">
	<a class="triigerModal"  href="<?=Url::toRoute('/event')?>"
	> <img
		src="<?=$this->theme->getUrl('/img/pop-up/events/event.gif')?>"
		class="img-fluid" alt="popup">
	</a>
</div>