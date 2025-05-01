<style>
.btn:focus, .btn:active, button:focus, button:active {
	outline: none !important;
	box-shadow: none !important;
}

.gallery .modal-footer {
	display: block;
}

.thumb {
	margin-top: 15px;
	margin-bottom: 15px;
}

.thumb a i {
	color: #fff;
	text-aling : right;
}

.icon-style{background: #f18d05;
padding: 7px;
border-radius: 15px;}

.footer-social-menu a{color:#fff;}

.footer-social-menu{margin: 8px;}
.fa-trash{
    font-size: 20px;
}


</style>

<?php
use app\modules\social\widgets\SocialShare;
use yii\helpers\Url;

if (! empty($images)) {
    foreach ($images as $image) {
        ?>
<div class="col-lg-3 col-md-4 col-xs-6 thumb">

 <div class="float-start blog-comment-section ">
 <?php
       
        echo SocialShare::widget([
            'buttons' => [
                'linkedin' => [
                    'label' => false,
                    'options' => [
                        'class' => 'fa fa-linkedin icon-style linkedin',
                        'title'=>'Linkdn'
                    ]
                ],
                'facebook' => [
                    'label' => false,
                    'options' => [
                        'class' => 'fa fa-facebook-square icon-style facebook',
                        'title'=>'Facebook'
                    ]
                ],
                'googleplus' => [
                    'label' => false,
                    'options' => [
                        'class' => 'fa fa-google-plus icon-style google',
                        'title'=>'Google+'
                    ]
                ],
                'twitter' => [
                    'label' => false,
                    'options' => [
                        'class' => 'fa fa-twitter icon-style twitter',
                        'title'=>'Twitter'
                    ]
                ],
                'whatsapp' => [
                    'label' => false,
                    'options' => [
                        'class' => 'fa fa-whatsapp icon-style whatsapp',
                        'title'=>'Whatsapp'
                    ]
                ]
            ],
            'url' => Url::to(['file/get-file','unique_code'=>$image['unique_code']], true),
            'imageUrl' =>Url::to(['file/get-file','unique_code'=>$image['unique_code']], true),
            'title' =>$image['title'] ,
            //'description' =>$image['title'] ,
            'options' => [
                'class' => 'footer-social-menu list-inline float-end'
            ]
        ]);
        ?>  
 </div>
	<a class="label label-danger float-end" href="<?= $image['deleteUrl'] ?>"
		data-method="post"
		data-confirm="<?= \Yii::t('app', 'Are you sure you want to delete this item?') ?>">
		<i class="fa fa-trash"></i>
		</a> 
		<a id="thumb-<?= $image['id'] ?>" class="thumbnail" href="#" data-image-id="<?= $image['id'] ?>" data-bs-toggle=""
		data-title="<?= $image['title'] ?>" data-image="<?= $image['url'] ?>"
		data-bs-target="#<?= $id ?>"> 
		<img class="img-thumbnail"
		src="<?=  Yii::$app->view->theme->getUrl("img/file_thumb.png") ?>" alt="<?= $image['title'] ?>">
	</a>
	<div><?= $image['title'] ?></div><br>
    	<div> 
        	<button id="<?= $image['unique_code']?>" data-id="<?= Url::to(['share/get-file','unique_code'=>$image['unique_code']], true); ?>" class="download btn btn-success float-start">Download</button>
        	<button  id="<?= $image['id'] ?>" class="copy-link btn btn-danger float-end">Copy Link</button>
    	<input id="hidden-<?= $image['id'] ?>" aria-hidden="true" style="display: none" type="text"  value="<?= Url::to(['share/get-file','unique_code'=>$image['unique_code']], true); ?>"  >
    	 
    	 </div>
    	<div class="card">
    		
    		
    			
    		
    	</div> 
    	 
</div>
<?php
    }
}
?>

<div class="modal fade gallery" id="<?= $id ?>" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="<?= $id ?>-title"></h4>
				
				<button type="button" class="close" data-bs-dismiss="modal">
					<span aria-hidden="true">×</span><span class="sr-only">Close</span>
				</button>
				
			</div>
			<div class="modal-body">
				<img id="<?= $id ?>-image" class="img-responsive col-md-12" src="">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary float-start"
					id="<?= $id ?>-previous-image">
					<i class="fa fa-arrow-left"></i>
				</button>

				<button type="button" id="<?= $id ?>-next-image"
					class="btn btn-secondary float-end">
					<i class="fa fa-arrow-right"></i>
				</button>
			</div>
		</div>
	</div>
</div>

<script>
let modalId = $('#<?= $id ?>');

$(document)
  .ready(function () {

    loadGallery(true, 'a.thumbnail');

    //This function disables buttons when needed
    function disableButtons(counter_max, counter_current) {
      $('#<?= $id ?>-previous-image, #<?= $id ?>-next-image')
        .show();
      if (counter_max === counter_current) {
        $('#<?= $id ?>-next-image')
          .hide();
      } else if (counter_current === 1) {
        $('#<?= $id ?>-previous-image')
          .hide();
      }
    }

    /**
     *
     * @param setIDs        Sets IDs when DOM is loaded. If using a PHP counter, set to false.
     * @param setClickAttr  Sets the attribute for the click handler.
     */

    function loadGallery(setIDs, setClickAttr) {
      let current_image,
        selector,
        counter = 0;

      $('#<?= $id ?>-next-image, #<?= $id ?>-previous-image')
        .click(function () {
          if ($(this)
            .attr('id') === '<?= $id ?>-previous-image') {
            current_image--;
          } else {
            current_image++;
          }

          selector = $('[data-image-id="' + current_image + '"]');
          updateGallery(selector);
        });

      function updateGallery(selector) {
        let $sel = selector;
        current_image = $sel.data('image-id');
        $('#<?= $id ?>-title')
          .text($sel.data('title'));
        $('#<?= $id ?>-image')
          .attr('src', $sel.data('image'));
        disableButtons(counter, $sel.data('image-id'));
      }

      if (setIDs == true) {
        $('[data-image-id]')
          .each(function () {
            counter++;
            $(this)
              .attr('data-image-id', counter);
          });
      }
      $(setClickAttr)
        .on('click', function () {
          updateGallery($(this));
        });
    }
  });

// build key actions
$(document)
  .keydown(function (e) {
    switch (e.which) {
      case 37: // left
        if ((modalId.data('bs.modal') || {})._isShown && $('#<?= $id ?>-previous-image').is(":visible")) {
          $('#<?= $id ?>-previous-image')
            .click();
        }
        break;

      case 39: // right
        if ((modalId.data('bs.modal') || {})._isShown && $('#<?= $id ?>-next-image').is(":visible")) {
          $('#<?= $id ?>-next-image')
            .click();
        }
        break;
      default:
        return; // exit this handler for other keys
    }
    e.preventDefault(); // prevent the default action (scroll / move caret)
  });

$(".copy-link").on('click',function(){
	var file_id=$(this).attr('id');  
	 
	 $(this).removeClass('btn-danger');
	 $(this).addClass('btn-info'); 
	 $("#hidden-"+file_id).css({"display":"block"}); 
	 
    var copyText =$("#hidden-"+file_id);
    
	try{
		 copyText.select();
		 document.execCommand("copy")
		}
	 catch(err){
			alert(err);
	 }
	 $("#hidden-"+file_id).css({"display":"none"});
});

$(".download").on('click',function(e){
	
    window.location.href = $(this).attr('data-id');
    e.preventDefault(); 
});

</script>