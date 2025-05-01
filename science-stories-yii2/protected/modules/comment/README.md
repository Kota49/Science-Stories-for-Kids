# COMMENT

#### About Brief
A comment module, it is a feature commonly found on websites and blogs that allows users to leave comments or feedback on specific content. 
It provides a platform for interaction and engagement between the website owner and visitors, as well as among the visitors themselves.


## INSTALLATION 

## If you need to install single module 

Steps-
1. Go to ***htdocs*** or ***html*** folder.
2. Then, Go to your project->protected->modules and Open your terminal by ***ctrl+alt+t*** . 
3. Type ***git clone  http://192.168.10.21/yii2/modules/comment.git*** in your terminal. Wait till installation complete.

> add below code in your .gitmodule file.

        [submodule "protected/modules/comment"]
        path = protected/modules/comment
        url = http://192.168.10.21/yii2/modules/comment.git

> add below code in web.php file, located at protected/config/web.php

        $config['modules']['comment'] = [
         'class' => 'app\modules\comment\Module'
        ];

> add module in side nav bar, located at protected/base/TBaseController.php

         if (yii::$app->hasModule('comment'))
                   $this->nav_left[] = \app\modules\comment\Module::subNav();

## During setup new project > you need to use these steps

Steps- 
1. Go to ***htdocs*** or ***html*** > you project .
2. 1st check  .gitmodule file comment module lines ( exists or not)

        [submodule "protected/modules/comment"]
        path = protected/modules/comment
        url = http://192.168.10.21/yii2/modules/comment.git

3. Then, run (in your project root)  ***bash ../scripts/clone-submodules.sh*** in your terminal.
4. Then, run (in your project root)  ***php console.php module/migrate*** in your terminal.

## HOW TO USE

Call comment widget where you want to add like

echo CommentsWidget::widget([
    'model' => $model
]);


