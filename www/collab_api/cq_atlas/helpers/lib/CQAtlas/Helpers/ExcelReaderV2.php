<?php

namespace CQAtlas\Helpers;

/**
 * Class ExcelReaderV2
 * @package CQAtlas\Helpers
 */
class ExcelReaderV2
{
    protected $_source;
    protected $_header;
    protected $_rows;
    protected $_activeSheet=0;
    protected $_objPHPExcel;

    /**
     * @param $source
     * @param int $headerRow
     */
    public function __construct($source,$headerRow=0)
    {
        $this->_source = $source;
        $this->_objPHPExcel = \PHPExcel_IOFactory::load($source);
        $this->_header = array();
        $this->getData();
        $this->sanitize();
        return $this;
    }

    /**
     * @param int $activeSheet
     * @return $this
     */
    public function setDataWorksheet($activeSheet = 0)
    {
        $this->_activeSheet = $activeSheet;
        $this->_objPHPExcel->setActiveSheetIndex($activeSheet);
        return $this;
    }

    /**
     * @return array
     */
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

    /**
     * @param int $number
     * @return $this
     */
    public function setHeaderRowNumber($number=0){
        $this->_Reader->setHeaderRowNumber($number);
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaderRow(){
        return $this->_header;
    }

    /**
     * @return mixed
     */
    public function getRows(){
        return $this->_rows;
    }

    /**
     * @return $this
     */
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

    /**
     * @return int
     */
    public function getRowsCount(){
        return count($this->_rows);
    }
}