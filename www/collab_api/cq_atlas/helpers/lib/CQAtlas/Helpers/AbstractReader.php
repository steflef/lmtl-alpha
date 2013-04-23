<?php

namespace CQAtlas\Helpers;

abstract class AbstractReader
{
    protected $_source;
    protected $_file;
    protected $_Reader;
    private $_rows = array();

    public function __construct($source,$headerRow=0)
    {
        $this->_source = new \Ddeboer\DataImport\Source\Stream( $source );
    }

    public function setHeaderRowNumber($number=0){
        $this->_Reader->setHeaderRowNumber($number);
        return $this;
    }

    public function getHeaderRow(){
        return $this->_Reader->getColumnHeaders();
    }

    public function getRows(){
        return $this->_rows;
    }

    protected  function readRows(){
        foreach($this->_Reader as $row){
            $this->_rows[] = $row;
        }
        return $this->_rows;
    }

    public function getRowsCount(){
        return count($this->_rows);
    }
}