<?php

namespace Domain\DataSources;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\Credentials\Credentials;
use Exception;

class S3 extends DataSource
{
    protected $bucket;

    protected $client;


    public function __construct()
    {
        if (file_exists('credentials/s3.json')) {
            $secrets = json_decode(file_get_contents('credentials/s3.json'), true);
            $credentials = new Credentials($secrets['accessKeyId'], $secrets['secretKey']);
            $this->bucket = $secrets['bucket'];
        } else {
            throw new Exception('invalid credentials file');
        }


        $this->client = new S3Client([
            'version' => 'latest',
            'region' => 'eu-central-1',
            'credentials' => $credentials
        ]);
    }

    public function write($keyname, $body)
    {

        $result = $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key'    => $keyname,
            'Body'   => $body
        ]);

        return $result;
    }

    public function read($filename)
    {
        $result = $this->client->getObject([
            'Bucket' => $this->bucket,
            'Key'    => $filename
        ]);

        return json_decode($result['Body'], true);
    }
}
