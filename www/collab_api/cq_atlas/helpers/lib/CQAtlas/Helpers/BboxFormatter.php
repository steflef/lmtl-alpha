<?php

namespace CQAtlas\Helpers;

class BboxFormatter extends AbstractFormatter
{
    protected $_row;

    public function __construct($source,$CartoDB, $row)
    {
        parent::__construct($source,$CartoDB);
        $this->_row = $row;
        return $this->getOutput();
    }

    public function getOutput(){

        //$output = "Rock the Box!";

        $output = $this->CartoDB->getExtent($this->_row['id']);

        return $output;
    }
}