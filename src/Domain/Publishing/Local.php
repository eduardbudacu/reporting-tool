<?php 

namespace Domain\Publishing;

/**
 * Publishes report on the local drive
 */
class Local extends Destination {
    /**
     * Saves content on the local storage
     * 
     * @param string $content
     */
    public function publish($content) {
        file_put_contents($this->filename, $content);
    }
}