<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\storage\components;

use app\modules\storage\models\Provider;
use yii\base\Component;

class S3Provider extends Component
{

    public $s3;

    public $account_id = 0;

    public $provider;

    public function init()
    {
        parent::init();

        if ($this->provider == null) {
            if ($this->account_id) {
                $this->provider = Provider::findOne($this->account_id);
            } else {
                $this->provider = Provider::findActive()->one();
            }
        }

        if ($this->provider == null) {
            throw new \InvalidArgumentException('smtp account doesnt exists');
        }

        $this->s3 = new \Aws\S3\S3Client([

            "version" => "latest",

            "region" => $this->provider->location,

            "endpoint" => $this->provider->endpoint,

            "use_path_style_endpoint" => true,

            "credentials" => [
                'key' => $this->provider->key,
                'secret' => $this->provider->secret
            ]
        ]);
    }

    public function upload($key, $file)
    {

        // Send a PutObject request and get the result object in Minio.
        $result = $this->s3->putObject(array(
            'Bucket' => $this->provider->title, // BucketName
            'Key' => $key, // File Name
            'SourceFile' => $file
        ));

        return $result['ObjectURL'];
    }

    public function get($key, $file = null)
    {
        $retrive = $this->s3->getObject([
            'Bucket' => $this->provider->title,
            'Key' => $key,
            'SaveAs' => $file
        ]);
        return $retrive['Body'];
    }

    public function delete($key, $file = null)
    {
        $this->s3->deleteObject([
            'Bucket' => $this->provider->title,
            'Key' => $key
        ]);
        return true;
    }

    public function exists($key)
    {
        return $this->s3->doesObjectExistV2($this->provider->title, $key);
    }
}