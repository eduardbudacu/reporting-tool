<?php

namespace Domain\DataSources;

class Local extends DataSource
{
    public function read($filename)
    {
        return json_decode(file_get_contents("data/{$filename}"), true);
    }
}
