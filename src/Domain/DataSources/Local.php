<?php

namespace Domain\DataSources;

class Local extends DataSource
{
    /**
     * Reads file from the local data directory
     * 
     * @param string $filename
     */
    public function read($filename)
    {
        return json_decode(file_get_contents("data/{$filename}"), true);
    }
}
