# storage

#### About Brief
 It's used as the storage component S3

## INSTALLATION 

## If you need to install single module 

Steps-
1. Go to ***htdocs*** or ***html*** folder.
2. Then, Go to your project->protected->modules and Open your terminal by ***ctrl+alt+t*** . 
3. Type ***git clone  http://192.168.10.21/yii2/modules/storage.git*** in your terminal. Wait till installation complete.
4. After clone run (project->protected->modules) this command ***php console.php installer/install/module -m=storage*** in termianl. Open your terminal by ***ctrl+alt+t*** .

> add below code in your .gitmodule file.

        [submodule "protected/modules/storage"]
        path = protected/modules/storage
        url = http://192.168.10.21/yii2/modules/storage.git

> add below code in web.php file, located at protected/config/web.php

        $config['modules']['storage'] = [
         'class' => 'app\modules\storage\Module'
        ];
        
> add below code in console.php file, located at protected/config/console.php

        $config['modules']['storage'] = [
         'class' => 'app\modules\storage\Module'
        ];
        
> add module in side nav bar, located at protected/base/TBaseController.php

         if (yii::$app->hasModule('storage'))
                   $this->nav_left[] = \app\modules\storage\Module::subNav();

## During setup new project > you need to use these steps

Steps- 
1. Go to ***htdocs*** or ***html*** > you project .
2. 1st check  .gitmodule file blog module lines ( exists or not)

        [submodule "protected/modules/storage"]
        path = protected/modules/storage
        url = http://192.168.10.21/yii2/modules/storage.git

3. Then, run (in your project root)  ***bash ../scripts/clone-submodules.sh*** in your terminal.

4. Add aws/aws-sdk-php extension in your composer.json

5. Update Composer composer update --prefer-dist --prefer-stable

6.Extend  app\models\File to app\modules\storage\models\File


## Set S3 Configurations > Open your project > Goto Storage module > Set following Configurations in Provider Section

> Title : test
> Key : test
> Secret : test@123
> Endpoint : http://192.168.9.101:9000

## Test S3

1. Upload a file in your project.
2. Test using command line 
   * For Upload file use php console.php storage/file/upload 
   * For Download file use php console.php storage/file/download 


## How to use module > you need to use these steps

Steps- 

1. Go to the Browser and open your project.
2. Add Type and Description(how to use).
3. Add or Import storage Provider with valid credentials.











 
