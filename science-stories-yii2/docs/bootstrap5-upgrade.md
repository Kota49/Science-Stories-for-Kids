#How to upgrade bootstrap version in coomon modules


Step1: Check below path to update bootstrap version5
       
     Path: protected/config/params.php

    -Default we are using bootstrap version 4 and if you want to upgrade the add below two lines in params.php

    'bsVersion' => '5.x',
    'bsDependencyEnabled' => false


Step 2: Add a directory in every modules, name should be views5
     
      -Suppose wanted to upgrade blog modules then add a new directory views5
      -inside the views5 directory, you can add any other view file like normal view file (check views folder in modules)

      -For Example check below path.

      Path: protected/modules/blog/views5/index.php
	    protected/modules/blog/views5/view.php
	    protected/modules/blog/views5/_grid.php
            protected/modules/blog/views5/_form.php
            protected/modules/blog/views5/_ajax-grid.php
            protected/modules/blog/views5/add.php
            protected/modules/blog/views5/update.php

      Note: views5 directory is mandatory in every modules if you upgrading bootstrap version 5


Step 3: Go to composer.json file and update bootstrap version 5 if you are upgrading.

       - Add below line in require section in composer.json file
         "require" : {
		"yiisoft/yii2-bootstrap5": "*"
	}

Step 4:  - then update vendor for latest bootstrap classes, using below command

         composer update --prefer-dist --prefer-stable


Step 5: Update a appAsset file, if you are updating project completely in bootstrap5


       Path: 
       - protected/assets/AppAsset.php

	#
		public $depends = [ 
			'yii\web\YiiAsset',
			'yii\bootstrap5\BootstrapAsset',
			'yii\bootstrap5\BootstrapPluginAsset' 
		];
	#


