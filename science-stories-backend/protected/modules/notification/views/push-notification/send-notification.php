<?php
use app\components\TActiveForm;
use app\models\User;
use yii\helpers\Html;
use app\components\TRichTextEditor;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Remind Users'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');
?>

<div class="wrapper">
	<div class="user-create card">
	<?=  \app\components\PageHeader::widget(['title' => 'Remind Users']); ?>
	</div>

	<div class="content-section card">

		<header class="card-header">
    <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
		<div class="card-body">

    <?php
    $form = TActiveForm::begin([
        'id' => 'notification-form',
        'options' => [
            'class' => 'row'
        ]
    ]);
    ?>

<div class="col-lg-11 ml-5">

 
                  <?php echo$form->field($model, 'title')->textarea(['rows' => 6])?>
	
	<div class="form-group col-lg-12 text-right">
	
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'notification-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	

    <?php TActiveForm::end(); ?>

</div>
		</div>
		<div class="wrapper">
			<div class="card">
				<header class="card-header"><?=Yii::t('app', 'Notification history')?> </header>
				<div class="card-body">
					<div class="content-section clearfix">
					<?php echo $this->render('_notification_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
				</div>
				</div>
			</div>

		</div>
		<script src="https://cdn.ckeditor.com/4.16.2/standard-all/ckeditor.js"></script>

		<script>
	
	
             CKEDITOR.replace( 'editor1',{
	    plugins: 'mentions,emoji,basicstyles,wysiwygarea,toolbar, pastefromgdocs, pastefromlibreoffice, pastefromword,forms,exportpdf,image,autoembed,link,font,colorbutton,tabletools,iframe,notificationaggregator,dialog,divarea,filetools,sharedspace,div,liststyle,lineutils,magicline,templates,preview,bidi,justify',
                contentsCss: [
                  'http://cdn.ckeditor.com/4.16.2/full-all/contents.css',
                  'https://ckeditor.com/docs/ckeditor4/4.16.2/examples/assets/mentions/contents.css'
                ],
                height: 80,
                extraPlugins: 'autogrow',
                autoGrow_minHeight: 200,
                autoGrow_maxHeight: 600,
                autoGrow_bottomSpace: 50,
                removePlugins: 'resize',
                extraPlugins: 'editorplaceholder',
                editorplaceholder: 'Add a comment...',
                   mentions: [{
          feed: dataFeed,
          itemTemplate: '' +
            '{avatar}' +
            '{username}' +
            '{fullname}' +
            '',
          outputTemplate: '@{username} ',
          minChars: 0
        }
      ],
                removeButtons: 'PasteFromWord',

              });

              function dataFeed(opts, callback) {
                var matchProperty = 'username',
                  data = users.filter(function (item) {
                  console.log(opts.query.toLowerCase());
                    return item[matchProperty].indexOf(opts.query) == 0;
                  });

                data = data.sort(function (a, b) {
                  return a[matchProperty].localeCompare(b[matchProperty], undefined, {
                    sensitivity: 'accent'
                  });
                });

                callback(data);
              }
              	$( "#notification-form-submit" ).submit( function( e ) {
     //in case, if didn't worked, remove below comment. This will get the textarea with current status
    //CKEDITOR.instances.textarea_input_name.updateElement( );
    var messageLength = CKEDITOR.instances['textarea_input_name'].getData( ).replace( /<[^>]*>/gi, '' ).length;
    if( !messageLength )
    {
        //alert( 'Please fill required field `Text`' );
        //stop form to get submit
        e.preventDefault( );
        return false;
    }
    else
    {
        //editor is not empty, proceed to submit the form
        return true;
    }
} );
              
              
                   </script>