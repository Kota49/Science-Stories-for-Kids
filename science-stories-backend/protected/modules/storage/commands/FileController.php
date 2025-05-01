<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\storage\commands;

use app\components\TConsoleController;
use app\components\helpers\TArrayHelper;
use function app\components\helpers\TLogHelper\log as strtotime;
use app\models\EmailQueue as EmailQueueBase;
use app\modules\storage\models\File;
use yii\base\Exception;

class FileController extends TConsoleController
{

    public $deleteLocal = false;

    public function options($actionID)
    {
        return TArrayHelper::merge(parent::options($actionID), [
            'deleteLocal'
        ]);
    }

    public function optionAliases()
    {
        return TArrayHelper::merge(parent::optionAliases(), [
            'dl' => 'deleteLocal'
        ]);
    }

    /**
     * Truncate table
     */
    public function actionTruncate()
    {
        $query = File::find()->orderBy('id asc');

        File::log("Cleaning up files : " . $query->count());
        $query->limit(1000);
        foreach ($query->each() as $file) {
            File::log("Deleting  file :" . $file->id . ' - ' . $file);
            try {
                $file->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
        if ($query->count() == 0) {
            $file::truncate();
        }
    }

    /**
     * Remove orphan files
     */
    public function actionRemoveOrphan()
    {
        $query = File::find()->orderBy('id asc');

        if (! $this->force) {
            $query->limit($this->limit);
        }
        if ($this->offset) {
            $query->andWhere([
                '>',
                'id',
                $this->offset
            ]);
        }
        File::log("checking orphan files : " . $query->count());

        foreach ($query->each() as $file) {
            if ($file->getModel()) {
                // File::log("NOT orphan : " . $file);
                continue;
            }
            File::log("Deleting  orphan file :" . $file->id . ' - ' . $file);

            if (! $this->dryRun) {
                $file->delete();
            }
        }
    }

    /**
     * Upload File
     *
     * @param number $id
     */
    public function actionUpload($id)
    {
        $file = File::findOne($id);

        self::log("  file : " . $file);
        if ($file) {
            self::log("Uploading file :" . $file->id . ' - ' . $file);
            try {
                $file->upload();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
    }

    /**
     * Download File
     *
     * @param number $id
     */
    public function actionDownload($id)
    {
        $file = File::findOne($id);

        self::log("  file : " . $file);
        if ($file) {
            self::log("Download file :" . $file->id . ' - ' . $file);
            try {
                $provider = File::getProvider($file->account_id);
                if ($provider) {
                    file_put_contents($file->getFullPath(), $provider->get($file->key));
                }
            } catch (Exception $e) {
                self::log($e->getMessage());
                self::log($e->getTraceAsString());
            }
        }
    }

    /**
     * Upload File
     *
     * @param number $id
     */
    public function actionUploadAll()
    {
        $fileQuery = File::find()->orderBy('id DESC');

        if (! $this->force) {
            $fileQuery->andWhere([
                'account_id' => null
            ]);
        }

        if ($this->offset) {
            $fileQuery->andWhere([
                '<',
                'id',
                $this->offset
            ]);
        }

        self::log("  files count: " . $fileQuery->count());
        foreach ($fileQuery->each() as $file) {
            try {

                $file->uploadIfDoesntExists($this->deleteLocal);
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
        self::log("Uploading done");
    }
}

