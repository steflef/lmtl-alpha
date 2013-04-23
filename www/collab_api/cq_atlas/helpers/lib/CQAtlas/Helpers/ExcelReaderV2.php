<?php

namespace CQAtlas\Helpers;

class ExcelReaderV2
{


    protected $_source;
    protected $_header;
    protected $_rows;
    protected $_activeSheet=0;
    protected $_objPHPExcel;

    public function __construct($source,$headerRow=0)
    {
        //parent::__construct($source,$headerRow);

        $this->_source = $source;
        $this->_objPHPExcel = \PHPExcel_IOFactory::load($source);
        $this->_header = array();
        $this->getData();
        $this->sanitize();
        return $this;
    }

    public function setDataWorksheet($activeSheet = 0)
    {
        $this->_activeSheet = $activeSheet;
        $this->_objPHPExcel->setActiveSheetIndex($activeSheet);
        return $this;
    }

    protected function getData(){
        $this->_objPHPExcel->setActiveSheetIndex($this->_activeSheet);
        $objWorksheet = $this->_objPHPExcel->getActiveSheet();

        $this->_header = array();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

        for ($col = 0; $col < $highestColumnIndex; ++$col) {
            $this->_header[] = $objWorksheet->getCellByColumnAndRow($col, 1)->getCalculatedValue();
        }

        $cells = array();
        $rows =0;
        foreach ($objWorksheet->getRowIterator() as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $cols =0;
            foreach ($cellIterator as $cell) {
                //echo "($cols,$rows)<br>";
                $cells[$rows][$this->_header[$cols]] = $cell->getCalculatedValue();
                $cols ++;
            }
            $rows++;
        }

        $cells = array_slice($cells,1);
        $this->_rows = $cells;
        return $cells;
    }

    public function setHeaderRowNumber($number=0){
        $this->_Reader->setHeaderRowNumber($number);
        return $this;
    }

    public function getHeaderRow(){
        return $this->_header;
    }

    public function getRows(){
        return $this->_rows;
    }

    private function sanitize(){
        $cleanRows = array();
        $headers = $this->getHeaderRow();
        foreach ($this->_rows as $row) {
            if($row[$headers[0]] == '') continue;
            $cleanRows[] = $row;
        }
        $this->_rows = $cleanRows;

        return $this;
    }

    public function getRowsCount(){
        return count($this->_rows);
    }
}