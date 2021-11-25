<?php 

namespace Domain\Publishing;

class Local extends Destination {
    public function publish($content) {
        file_put_contents($this->filename, $content);
    }
}