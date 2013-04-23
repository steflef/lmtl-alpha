<?php

namespace CQAtlas\Helpers;

class Json2arrayFormatter extends AbstractFormatter
{
    public function __construct($source,$CartoDB)
    {
        parent::__construct($source,$CartoDB);
        return $this->getOutput();
    }

    public function getOutput(){
        return json_decode($this->_source, false);
    }
}