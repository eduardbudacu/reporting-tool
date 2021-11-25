<?php

namespace Domain\DataSources;

use Exception;

abstract class DataSource {
    abstract public function read($filename);

    public static function create($type): DataSource {
        switch($type) {
            case 'local': {
                return new Local();
            }break;
            case 's3': {
                return new S3();
            }break;
            case 'api': {
                return new Api();
            }break;
            default:
                throw new Exception('Invalid data source specified');
        }
    }
}