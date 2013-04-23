<?php

namespace CQAtlas\Helpers;

class ExcelReader extends AbstractReader
{
    public function __construct($source,$headerRow=0)
    {
        parent::__construct($source,$headerRow);

        $this->_file = $this->_source->getFile();
        $this->_Reader = new \Ddeboer\DataImport\Reader\ExcelReader($this->_file);
        $this->setHeaderRowNumber($headerRow);

        $this->readRows();
        return $this;
    }
}