<?php

namespace CQAtlas\Helpers;

class DelimitedReader extends AbstractReader
{
    public function __construct($source,$headerRow=0,$delimiter)
    {
        parent::__construct($source,$headerRow);
        $this->_source->addFilter(new \Ddeboer\DataImport\Source\Filter\NormalizeLineBreaks());
        $this->_file = $this->_source->getFile();
        $this->_Reader = new \Ddeboer\DataImport\Reader\CsvReader($this->_file, $delimiter);
        $this->setHeaderRowNumber($headerRow);
        $this->readRows();
        return $this;
    }
}