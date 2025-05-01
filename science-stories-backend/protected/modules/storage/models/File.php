<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\storage\models;

use app\components\helpers\TFileHelper;
use app\models\User;
use Yii;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\modules\storage\Module;
use yii\helpers\Url;

/**
 * This is the model class for table "tbl_storage_file".
 *
 * @property integer $id
 * @property string $name
 * @property integer $size
 * @property string $key
 * @property string $model_type
 * @property integer $model_id
 * @property integer $project_id
 * @property integer $account_id
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property User $createdBy
 */
class File extends \app\components\TActiveRecord
{

    const TYPE_FILE = 0;

    const TYPE_LINK = 1;

    const TYPE_URL = 2;

    const TYPE_AWS_S3 = 3;

    const TYPE_IMAGE = 4;

    const UPLOAD_PATH = 'upload_path';

    public function __toString()
    {
        return (string) $this->name;
    }

    public static function getTypeOptions()
    {
        return [
            self::TYPE_FILE => "File",
            self::TYPE_LINK => "SybolicLink",
            self::TYPE_URL => "URL",
            self::TYPE_IMAGE => "Image"
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (! isset($this->created_on)) {
                $this->created_on = date('Y-m-d H:i:s');
            }

            if (! isset($this->created_by_id)) {
                $this->created_by_id = self::getCurrentUser();
            }
        } else {}
        return parent::beforeValidate();
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "New",
            self::STATE_ACTIVE => "Active",
            self::STATE_DELETED => "Deleted"
        ];
    }

    public function getState()
    {
        $list = self::getStateOptions();
        return isset($list[$this->state_id]) ? $list[$this->state_id] : 'Not Defined';
    }

    public function getStateBadge()
    {
        $list = [
            self::STATE_INACTIVE => "secondary",
            self::STATE_ACTIVE => "success",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge bg-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getActionOptions()
    {
        return [
            self::STATE_INACTIVE => "Deactivate",
            self::STATE_ACTIVE => "Activate",
            self::STATE_DELETED => "Delete"
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%storage_file}}';
    }

    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'name',
                    'size',
                    'key',
                    'model_type',
                    'model_id',
                    'type_id',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'size',
                    'type_id',
                    'model_id',
                    'account_id',
                    'state_id',
                    'project_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'name'
                ],
                'string',
                'max' => 1024
            ],
            [
                [
                    'key'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'model_type'
                ],
                'string',
                'max' => 128
            ],
            [
                [
                    'name',
                    'key',
                    'model_type'
                ],
                'trim'
            ],
            [
                [
                    'state_id'
                ],
                'in',
                'range' => array_keys(self::getStateOptions())
            ],
            [
                [
                    'type_id'
                ],
                'in',
                'range' => array_keys(self::getTypeOptions())
            ]
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'size' => Yii::t('app', 'Size'),
            'key' => Yii::t('app', 'Key'),
            'model_type' => Yii::t('app', 'Model Type'),
            'model_id' => Yii::t('app', 'Model'),
            'project_id' => Yii::t('app', 'Project'),
            'account_id' => Yii::t('app', 'Account'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'created_on' => Yii::t('app', 'Create On'),
            'created_by_id' => Yii::t('app', 'Created By')
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::class, [
            'id' => 'created_by_id'
        ]);
    }

    public function getAccount()
    {
        return $this->hasOne(Provider::class, [
            'id' => 'account_id'
        ]);
    }

    public static function getHasManyRelations()
    {
        $relations = [];
        return $relations;
    }

    public static function getHasOneRelations()
    {
        $relations = [];
        $relations['created_by_id'] = [
            'createdBy',
            'User',
            'id'
        ];
        $relations['account_id'] = [
            'account',
            'Provider',
            'id'
        ];
        return $relations;
    }

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }
        $this->removeLocalFile();
        $this->removeRemoteFile();
        return true;
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['name'] = $this->name;
        $json['size'] = $this->size;
        $json['key'] = $this->key;
        $json['url'] = $this->absoluteUrl;
        $json['model_type'] = $this->model_type;
        $json['model_id'] = $this->model_id;
        $json['project_id'] = $this->project_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {
            // createdBy
            $list = $this->createdBy;

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['createdBy'] = $relationData;
            } else {
                $json['CreatedBy'] = $list;
            }
        }
        return $json;
    }

    public static function add($model, $data = null, $filename = null, $image = false)
    {
        if (empty($data)) {
            return null;
        }
        if (isset($filename)) {
            $old = File::find()->where([
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'name' => basename($filename)
            ])->one();

            if ($old) {
                return $old;
            }
        }
        $attachment = new File();
        $attachment->loadDefaultValues();
        $attachment->model_id = $model->id;
        $attachment->model_type = get_class($model);
        $attachment->type_id = ($image) ? File::TYPE_IMAGE : File::TYPE_FILE;

        if ($model->hasAttribute('project_id')) {
            $attachment->project_id = $model->project_id;
        }

        $attachment->created_by_id = self::getCurrentUser();

        $models = StringHelper::dirname($attachment->model_type);
        $module = StringHelper::dirname($models);
        $dir = StringHelper::basename($module);
        if ($dir == 'app') {
            $dir = '.';
        }
        $dir = $dir . '/' . StringHelper::basename($attachment->model_type);

        if (! is_dir(UPLOAD_PATH . $dir)) {
            TFileHelper::createDirectory(UPLOAD_PATH . $dir);
        }

        if ($data instanceof UploadedFile) {
            $attachment->name = $data->basename . '.' . $data->extension;
            $filename = $attachment->model_id . '_' . $attachment->name;

            $filename = preg_replace("/[^A-Za-z0-9\_\-\.]/", '-', $filename);
            $filename = $dir . '/' . $filename;
            if (is_file(UPLOAD_PATH . $filename)) {
                TFileHelper::unlink(UPLOAD_PATH . $filename);
            }
            $data->saveAs(UPLOAD_PATH . $filename);
        } else {

            $attachment->name = basename($filename);
            $filename = $attachment->model_id . '_' . preg_replace("/[^A-Za-z0-9\_\-\.]/", '-', $filename);

            $filename = $dir . '/' . $filename;
            if (is_file(UPLOAD_PATH . $filename)) {
                TFileHelper::unlink(UPLOAD_PATH . $filename);
            }
            @file_put_contents(UPLOAD_PATH . $filename, $data);
        }

        $attachment->size = 0;

        if (! is_file(UPLOAD_PATH . $filename)) {
            return null;
        }
        $attachment->size = @filesize(UPLOAD_PATH . $filename);

        $attachment->key = $filename;

        if (! $attachment->save()) {
            return null;
        }

        $attachment->upload(true);

        return $attachment;
    }

    public function getModel()
    {
        $modelType = $this->model_type;
        if (class_exists($modelType)) {
            return $modelType::findOne($this->model_id);
        }
        return null;
    }

    public function getFullPath()
    {
        $filename = UPLOAD_PATH . $this->key;
        if (! is_file($filename)) {
            $dir = StringHelper::basename($this->model_type);
            if (is_dir(UPLOAD_PATH . $dir)) {
                $filename = UPLOAD_PATH . $dir . '/' . $this->key;
            }
        }
        File::log('getFullPath  :' . $filename);
        return $filename;
    }

    public function getTempFullPath()
    {
        return TFileHelper::getTempDirectory() . '/' . StringHelper::basename($this->key);
    }

    public function rename()
    {
        $path = $this->getFullPath();

        if (is_file($path)) {
            File::log("Update file :" . $this->id . ' - ' . $this . '==>' . $path);
            $dir = dirname($this->key);
            if (empty($dir) || $dir == '.') {
                // We Must Move
                $dir = StringHelper::basename($this->model_type);
                if (! is_dir(UPLOAD_PATH . $dir)) {
                    @mkdir(UPLOAD_PATH . $dir, true);
                }
                $path_dst = str_replace('/' . $dir . '_', '/' . $dir . '/', $path);
                if (rename($path, $path_dst)) {
                    File::log("New file :" . $this->id . ' - ' . $this . '==>' . $path_dst);
                    $this->key = str_replace(UPLOAD_PATH, '', $path_dst);
                    $this->updateAttributes([
                        'key'
                    ]);
                }
                if (! isset($this->project_id) && $dir == 'Project') {
                    $this->project_id = $this->model_id;
                    $this->updateAttributes([
                        'project_id'
                    ]);
                }
            }
        }
    }

    public function isAllowed($manager = false)
    {
        $model = $this->getModel();
        if ($model && $model->isAllowed()) {
            return true;
        }
        return false;
    }

    public static function findByKey($id)
    {
        if (is_numeric($id)) {
            $model = self::findOne($id);
        } else {
            $model = self::find()->where([
                'key' => $id
            ]);
        }
        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }

    public function removeLocalFile()
    {
        $path = $this->getFullPath();
        self::log("Delete File full Path:" . $path);
        if (is_file($path)) {

            @unlink($path);
        }
    }

    public function removeRemoteFile()
    {
        $provider = File::getProvider();
        if ($provider) {
            $url = $provider->delete($this->key);
            self::log("delete :" . $url);
        }
    }

    public static function getProvider($id = null)
    {
        $provider = null;

        if (is_numeric($id)) {
            $provider = Provider::findOne($id);
        }
        if (! $provider || ! $provider->isActive()) {
            $provider = Provider::findActive()->one();
        }
        if ($provider && $provider->isActive()) {

            return $provider->getCient();
        }
        return null;
    }

    public function uploadIfDoesntExists($delete_local = false)
    {
        try {
            $provider = File::getProvider();
            if ($provider) {
                self::log(__FUNCTION__ . "Uploading file :" . $this->id . ' - ' . $this);
                $exists = $provider->exists($this->key);
                self::log("exists :" . $exists);
                if (empty($exists)) {

                    $fullPath = $this->getFullPath();
                    if (! is_file($fullPath)) {

                        self::log("Not Found:" . $fullPath);
                        return false;
                    }
                    $url = $provider->upload($this->key, $fullPath);
                    self::log("Uploaded :" . $url);

                    $this->account_id = $provider->account_id;
                    $this->updateAttributes([
                        'account_id'
                    ]);
                    $exists = 1;
                } else {
                    self::log("Already Uploaded :");
                }
                if ($exists && $delete_local) {
                    $this->removeLocalFile();
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo $e->getTraceAsString();
        }
        return true;
    }

    public function upload($delete_local = false)
    {
        try {
            $provider = File::getProvider();
            $fullPath = $this->getFullPath();
            if ($provider && is_file($fullPath)) {
                self::log("Uploading file :" . $this->id . ' - ' . $this);

                $url = $provider->upload($this->key, $fullPath);
                self::log("Uploaded :" . $url);

                $this->account_id = $provider->account_id;
                $this->updateAttributes([
                    'account_id'
                ]);
                if ($delete_local) {
                    $this->removeLocalFile();
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo $e->getTraceAsString();
        }
    }

    public function getDownloadedPath()
    {
        $filepath = $this->getFullPath();
        $provider = File::getProvider($this->account_id);

        if ($provider) {
            $filepath = $this->getTempFullPath();
            if (! is_file($filepath)) {
                file_put_contents($filepath, $provider->get($this->key));
            }
        }
        return $filepath;
    }

    public static function getUploadPath()
    {
        $settings = \Yii::$app->settings;
        $path = $settings->getValue(self::UPLOAD_PATH, null, Module::NAME);

        if (is_null($path)) {
            \Yii::$app->settings->setValue(self::UPLOAD_PATH, BASE_PATH . '/uploads/', Module::NAME);
        }

        return $path;
    }

    public function getImageUrl($thumbnail = false)
    {
        $params = [
            '/' . $this->getControllerID() . '/image'
        ];
        $params['id'] = $this->id;

        if (isset($this->key) && ! empty($this->key)) {
            $params['file'] = $this->key;
        }

        if ($thumbnail)
            $params['thumbnail'] = is_numeric($thumbnail) ? $thumbnail : 150;
        return Url::toRoute($params);
    }

    public function getSizeFormatted()
    {
        return Yii::$app->formatter->asShortSize($this->size);
    }
}
