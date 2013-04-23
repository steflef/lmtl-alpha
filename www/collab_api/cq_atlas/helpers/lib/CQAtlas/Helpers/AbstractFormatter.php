<?php

namespace CQAtlas\Helpers;

abstract class AbstractFormatter
{
    protected $_source;
    protected $CartoDB;

    public function __construct($source, $CartoDB, $row = '')
    {
        $this->_source = $source;
        $this->CartoDB = $CartoDB;
    }

    public function getOutput(){}
}