<?php

namespace CQAtlas\Helpers;

class CountFormatter extends AbstractFormatter
{
    protected $_row;
    public function __construct($source,$CartoDB)
    {
        parent::__construct($source,$CartoDB);
        return $this->getOutput();
    }

    public function getOutput(){
        return $this->CartoDB->getPlacesCount($this->_source['id']);
    }
}