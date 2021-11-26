<?php

namespace Domain\DataSources;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\Credentials\Credentials;
use Exception;

/**
 * Reads and writes data from AWS S3
 */
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

    /**
     * Writes data to bucket
     * 
     * @param string $keyname Object name
     * @param string $body Object content
     */
    public function write($keyname, $body)
    {

        $result = $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key'    => $keyname,
            'Body'   => $body
        ]);

        return $result;
    }

    /**
     * Reads data from S3 bucket
     * 
     * @param string $filename Object name 
     *
     * @returns array
     */
    public function read($filename)
    {
        $result = $this->client->getObject([
            'Bucket' => $this->bucket,
            'Key'    => $filename
        ]);

        return json_decode($result['Body'], true);
    }
}
