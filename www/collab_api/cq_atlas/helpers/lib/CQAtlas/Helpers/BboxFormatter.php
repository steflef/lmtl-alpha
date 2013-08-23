<?php

namespace CQAtlas\Helpers;

class BboxFormatter extends AbstractFormatter
{
    protected $_row;

    public function __construct($source,$CartoDB, $row)
    {
        parent::__construct($source,$CartoDB);
        $this->_row = $row;
    }

    public function getOutput(){

        return $this->_row['bbox'];
    }
}