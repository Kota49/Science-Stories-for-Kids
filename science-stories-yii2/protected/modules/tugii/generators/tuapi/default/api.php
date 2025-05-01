<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
/**
 * This is the template for generating a CRUD controller class file.
 */
use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();

echo "<?php\n";
?>
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
#use <?= ltrim($generator->modelClass, '\\') ?>;
use yii\data\ActiveDataProvider;
use <?= ltrim($generator->baseControllerClass, '\\') ?>;

/**
 * <?= $controllerClass ?> implements the API actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n"?>
{
    public $modelClass = "<?=$generator->modelClass?>";
  
   public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete'
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ]
                ]
            ]
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        // unset($actions['create']);
        // unset($actions['update']);
        // unset($actions['delete']);
        // unset($actions['view']);
        // unset($actions['index']);
        return $actions;
    }
 

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     
    public function actionMyUpdate(<?= $actionParams ?>)
    {
    		$data = [ ];
			$model=$this->findModel($id);	
	        if ($model->load(\Yii::$app->request->post())) {
	            
	            if ($model->save()) {
    	            $data ['status'] = self::API_OK;
    				$data ['detail'] = $model;
  
	            } else {
	                $data['error'] = $model->flattenErrors;
	            }
	        } else {
	            $data['error_post'] = 'No Data Posted';
	        }
	        
	        return $data;
    }
*/
}
