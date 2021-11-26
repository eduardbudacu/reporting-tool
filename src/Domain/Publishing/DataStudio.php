<?php

namespace Domain\Publishing;

use Exception;
use Google\Cloud\Storage\StorageClient;

/**
 * Publishes report into google cloud storage
 */
class DataStudio extends Destination
{
    /**
     * @var Google\Cloud\Storage\StorageClient
     */
    protected $client;

    public function __construct($filename)
    {
        if(file_exists('credentials/google.json')) {
            $this->client = new StorageClient([
                'keyFile' => json_decode(file_get_contents('credentials/google.json'), true)
            ]);
        } else {
            throw new Exception('Invalid google credentials');
        }
        parent::__construct($filename);
    }


    /**
     * Publish content to google storage bucket
     * 
     * @param string $content File content
     */
    public function publish($content)
    {
        $bucket = $this->client->bucket('otriumpublishing');
        $object = $bucket->upload($content, [
            'name' => $this->filename
        ]);
    }
}
