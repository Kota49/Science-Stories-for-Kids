<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>


<div class="my-2 text-center">
	<?php
	echo Html::a('Capture Image', '#', [
		'id' => 'image-capture',
		'class' => 'btn btn-success',
		'data-bs-toggle' => "modal",
		'data-bs-target' => "#modalCaptureImage"
	]);
	?>
	<?= Html::hiddenInput('capture_image', null, ['id' => 'image_file']); ?>
</div>

<!-- Modal -->
<div class="modal" id="modalCaptureImage" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-body">

				<div class="wrapper">
					<div>

						<div class="row pakainfo">
							<div class="col-md-6 pakainfo">
								<div id="live_camera"></div>
								<hr />
							</div>
							<div class="col-md-6">
								<div id="preview"></div>
							</div>
							<div class="col-md-12 text-center pakainfo">
								<br /> <input type=button value="Take Snapshot" class="btn btn-primary"
									onClick="capture_web_snapshot()"> <input type="hidden" name="image"
									class="image-tag">
								<button type="button" id="submit-form" class="btn btn-success pakainfo">Submit</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Settings a few settings and (php capture image from camera) web attach camera -->
				<script language="JavaScript">
					Webcam.set({
						width: 425,
						height: 390,
						image_format: 'jpeg',
						jpeg_quality: 90
					});
					$('#image-capture').click(function () {
						Webcam.attach('#live_camera');
					});
					$('#submit-form').click(function () {
						var img = $('.image-tag').val();
						var image_type = 'webcam';
						var data = { 'image': img, image_type: image_type }
						<?php
						$params = $uploadUrl;
						$params['id'] = $model->id;
						?>
						$.ajax({
							url: "<?= Url::toRoute($params) ?>",
							type: "post",
							data: data,
							cache: false,
							success: function (response) {
								$('#image_file').val(response);
								$('#close-modal').click();
								location.reload();
							}
						});

					});
					function capture_web_snapshot() {
						Webcam.snap(function (site_url) {
							$(".image-tag").val(site_url);
							document.getElementById('preview').innerHTML = '<img src="' + site_url + '"/>';
						});
					}
				</script>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" id="close-modal" data-bs-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>