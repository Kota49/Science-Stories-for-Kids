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
namespace app\components\actions;

use app\components\TActiveForm;
use Yii;
use yii\base\Action;
use yii\web\UploadedFile;

/**
 * Import action
 *
 * public function actions()
 * {
 * return [
 *
 * 'import' => [
 * 'class' => 'app\components\actions\ImportAction',
 * 'modelClass' => Gateway::class,
 * ]
 * ];
 * }
 */
class ImportAction extends Action
{

    /**
     *
     * @var string name of the model
     */
    public $modelClass;

    public function run()
    {
        $import = new ImportForm();

        $modelClass = $this->modelClass;
        $model = new $modelClass();
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $import->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($import);
        }
        if ($import->load($post)) {
            $uploaded_file = UploadedFile::getInstance($import, "file");

            if ($uploaded_file != null) {

                $filename = $uploaded_file->tempName;

                $str = file_get_contents($filename);
                $json = json_decode($str, true);
                unset($json['id']);
                unset($json['created_by_id']);
                unset($json['created_on']);
                $model->setAttributes($json);
                if ($model->save()) {
                    \Yii::$app->controller->redirect([
                        'view',
                        'id' => $model->id
                    ]);
                }
            }
        }
        return \Yii::$app->controller->render('@app/components/actions/views/import.php', [
            'model' => $modelClass,
            'import' => $import
        ]);
    }
}
?>
