<?php

namespace Domain\Publishing;

use Exception;

abstract class Destination {
    protected $filename;

    abstract function publish($content);

    public function __construct(string $filename) {
        $this->filename = $filename;
    }

    /**
     * Factory method for creating a publishing destination
     * 
     * @return Domain\Publishing\Destination
     */
    public static function create($type, $filename) {
        switch($type) {
            case 'local': {
                return new Local($filename);
            } break;
            case 'datastudio':{ 
                return new DataStudio($filename);
            }
            default:
                throw new Exception('Invalid destination specified');
        }
    }
}