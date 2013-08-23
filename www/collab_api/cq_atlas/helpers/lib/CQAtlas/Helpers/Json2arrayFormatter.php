<?php

namespace CQAtlas\Helpers;

/**
 * Class Json2arrayFormatter
 * @package CQAtlas\Helpers
 */
class Json2arrayFormatter extends AbstractFormatter
{
    /**
     * @param $source
     * @param $CartoDB
     */
    public function __construct($source,$CartoDB)
    {
        parent::__construct($source,$CartoDB);
        return $this->getOutput();
    }

    /**
     * @return mixed|void
     */
    public function getOutput(){
        return json_decode($this->_source, false);
    }
}