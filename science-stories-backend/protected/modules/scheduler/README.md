# Scheduler

#### About Brief
1. It is used for manage cron jobs of projects dynamically.
2. Run crons according to mentioned time.

## INSTALLATION 

## If you need to install single module 

Steps-
1. Go to ***htdocs*** or ***html*** folder.
2. Then, Go to your project->protected->modules and Open your terminal by ***ctrl+alt+t*** . 
3. Type ***git clone  http://192.168.10.21/yii2/modules/scheduler.git*** in your terminal. Wait till installation complete.
4. After clone run this command ***php console.php/module migrate for install initial database. Open your terminal by ***ctrl+alt+t*** .

> add below code in your .gitmodule file.

        [submodule "protected/modules/scheduler"]
        path = protected/modules/scheduler
        url = http://192.168.10.21/yii2/modules/scheduler.git

> add below code in web.php file, located at protected/config/web.php

        $config['modules']['scheduler'] = [
         'class' => 'app\modules\scheduler\Module'
        ];
 
> add module in side nav bar, located at protected/base/TBaseController.php

         if (yii::$app->hasModule('scheduler'))
                   $this->nav_left[] = \app\modules\scheduler\Module::subNav();

## During setup new project > you need to use these steps

Steps- 
1. Go to ***htdocs*** or ***html*** > you project .
2. 1st check  .gitmodule file scheduler module lines ( exists or not)

        [submodule "protected/modules/scheduler"]
        path = protected/modules/scheduler
        url = http://192.168.10.21/yii2/modules/scheduler.git

3. Then, run (in your project root)  ***bash ../scripts/clone-submodules.sh*** in your terminal.

#### Uses
Steps:
1. Enable scheduler from scheduler settings.
2. Create a file in your with the name of 'scheduler.default.txt'
3. Add crons in this file > add time and cron according to following example.
  ex:  ****  test/data
4. Import this file using ***scheduler/cronjob/import***
5. After running command all crons are added in cronjob table
6. Create scheduler.php file in your project
7. For Test Run php scheduler.php




