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
use app\models\File;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
?>

<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
<script src="https://unpkg.com/cropperjs"></script>

<style>
	.image_area {
		position: relative;
	}

	img#uploaded_image {
		height: 200px;
	}

	img {
		display: block;
		max-width: 100%;
	}

	.preview {
		overflow: hidden;
		width: 160px;
		height: 160px;
		margin: 10px;
		border: 1px solid red;
	}

	.img_btn {
		position: relative;
		width: max-content;
		left: 50%;
		transform: translate(-50%, 0);
	}

	.img_btn input {
		position: absolute;
		left: 0;
		opacity: 0;
	}

	.modal-lg {
		max-width: 1000px !important;
	}

	.img-circle {
		height: 200px !important;
	}
</style>

<div class="row justify-content-center">
	<div class="col-md-12 justify-content-center text-center">
		<div class="image_area">
			<form method="post">
				<label for="upload_image">
					<?php
					$fileModel = $model->getFiles()
						->andWhere([
							'type_id' => File::TYPE_IMAGE
						])
						->orderBy('id desc')
						->one();

					$uploadImage = (!empty($fileModel)) ? $fileModel->getImageUrl() : $this->theme->getUrl('img/default.jpeg');

					?>
					<img src="<?= $uploadImage ?>" id="uploaded_image" class="img-responsive" /><br>
					<?= Html::hiddenInput('capture_image', null, ['id' => 'capture_image_file']); ?>
					<div class="img_btn">
						<div class="overlay-btn btn btn-primary">
							<div class="text">Upload Image</div>
						</div>

						<input type="file" name="image" class="image" id="upload_image">
					</div>
				</label>
			</form>
		</div>
	</div>


	<div class="modal" id="img_upload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Crop Image Before Upload</h5>
				</div>
				<div class="modal-body">
					<div class="img-container">
						<div class="row">
							<div class="col-md-8">
								<div class="image_container">
									<img id="blah" src="#" alt="your image" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="preview">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" id="crop_button">Crop</button>
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>

</div>

<script>
	function readURL(input) {
		console.log(input);
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#blah').attr('src', e.target.result)
			};
			reader.readAsDataURL(input.files[0]);
			setTimeout(initCropper, 1000);
		}
	}




	function initCropper() {
		console.log("Came here");
		var image = document.getElementById('blah');
		var cropper = new Cropper(image, {
			aspectRatio: 8 / 8,
			viewMode: 1,
			preview: '.preview'
		});

		// On crop button clicked
		document.getElementById('crop_button').addEventListener('click', function () {
			var imgurl = cropper.getCroppedCanvas().toDataURL();
			var img = document.createElement("img");
			img.src = imgurl;

			<?php
			$params = $uploadUrl;
			$params['id'] = $model->id;
			?>
			cropper.getCroppedCanvas().toBlob(function (blob) {
				url = URL.createObjectURL(blob);
				var reader = new FileReader();
				reader.readAsDataURL(blob);
				reader.onloadend = function () {
					var base64data = reader.result;
					var image_type = 'file';
					<?php
					$params = $uploadUrl;
					$params['id'] = $model->id;
					?>

					$.ajax({
						url: "<?= Url::toRoute($params) ?>",
						method: 'POST',
						data: { image: base64data, image_type: image_type },
						success: function (data) {
							$('#capture_image_file').val(data);
							$('#uploaded_image').attr('src', data);
							$('#img_upload').hide();
							location.reload();
						}
					});
				};
			});
		});

	}

	$(document).ready(function () {

		var image = document.getElementById('sample_image');

		$('#upload_image').change(function (event) {
			$('#img_upload').show();
			readURL(this);
		});
	});


</script>