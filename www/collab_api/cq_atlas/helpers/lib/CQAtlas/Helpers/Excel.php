<?php
/**
 * LMTL - Lieux montréalais
 *
 * @author      Collectif Quartier <info@collectifquartier.org>
 * @copyright   2013 Collectif Quartier
 * @link        http://www.lmtl.collectifquartier.org
 * @license     http://www.lmtl.collectifquartier.org/license
 * @version     1.0.0
 * @package     LMTL
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace CQAtlas\Helpers;

/**
 * Class Excel
 * Wrapper for PHPExcel
 */
class Excel
{
    private $_objPHPExcel;
    private $_objMetaWorksheet;
    private $_objDataWorksheet;
    private $_objWriter;
    private $_name;
    private $_desc;
    private $_sheetProperties;

    /**
     * @param string $name
     * @param string $desc
     */
    public function __construct($name='', $desc='')
    {
        $this->_name = $name;
        $this->_desc = $desc;
    }

    /**
     * @return $this
     */
    public function setSheet()
    {
        $this->_objPHPExcel = new \PHPExcel();
        $this->setSheetProperties();
        $this->setMetaWorksheet();
        $this->setDataWorksheet();
        return $this;
    }

    /**
     * @param $sourcePath
     */
    public function getSheet($sourcePath)
    {
        $this->_objPHPExcel = \PHPExcel_IOFactory::load($sourcePath);
        $this->_sheetProperties = $this->getSheetProperties();
    }

    /**
     * @return mixed
     */
    private function setSheetProperties()
    {
        // #####>> Set Sheet Properties#####
        $this->_objPHPExcel->getProperties()->setCreator("CQ ROBOT");
        $this->_objPHPExcel->getProperties()->setLastModifiedBy("CQ ROBOT");
        $this->_objPHPExcel->getProperties()->setTitle($this->_name);
        $this->_objPHPExcel->getProperties()->setSubject("Created by CQ");
        $this->_objPHPExcel->getProperties()->setDescription($this->_desc);
        $this->_objPHPExcel->getProperties()->setKeywords("");
        $this->_objPHPExcel->getProperties()->setCategory("");
        return $this->_objPHPExcel;
    }

    /**
     * @return array
     */
    private function getSheetProperties()
    {
        // #####>> Set Sheet Properties#####
        $properties = array(
            'creator' => $this->_objPHPExcel->getProperties()->getCreator(),
            'lastModifiedBy' => $this->_objPHPExcel->getProperties()->getLastModifiedBy(),
            'title' => $this->_objPHPExcel->getProperties()->getTitle(),
            'subject' => $this->_objPHPExcel->getProperties()->getSubject(),
            'description' => $this->_objPHPExcel->getProperties()->getDescription(),
            'keywords' => $this->_objPHPExcel->getProperties()->getKeywords(),
            'category' => $this->_objPHPExcel->getProperties()->getCategory()
        );
        
        return $properties;
    }

    /**
     * @return mixed
     */
    private function setMetaWorksheet()
    {
        $this->_objMetaWorksheet = new \PHPExcel_Worksheet($this->_objPHPExcel, 'Meta');
        $this->_objPHPExcel->addSheet($this->_objMetaWorksheet, 0);

        // #####>> Set Metadata Headers#####
        $this->_objPHPExcel->setActiveSheetIndex(0);
        $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Description');
        $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Valeur');
        return $this->_objPHPExcel;
    }

    /**
     * @param $meta
     * @return $this
     */
    public function setMetas($meta)
    {
        $this->_objPHPExcel->setActiveSheetIndex(0);
        $rowCount =2;
        //$this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rowCount, 'Document physique');
        //$rowCount++;
        foreach ($meta as $desc=>$val) {
            if($desc !== 'properties' && $desc !=='form'){
                $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rowCount, $desc);
                $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $rowCount, $val);
                $rowCount++;
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getMetas()
    {
        $this->_objPHPExcel->setActiveSheetIndex(1);
        $objWorksheet = $this->_objPHPExcel->getActiveSheet();

        $highestRow = $objWorksheet->getHighestRow();

        $cells = array();
        for($i=2;$i<=$highestRow;$i++){
            $key = $objWorksheet->getCellByColumnAndRow(0, $i)->getValue();
            $value = $objWorksheet->getCellByColumnAndRow(1, $i)->getValue();
            $cells[$key] = $value;
        }

        return $cells;
    }

    /**
     * @return mixed
     */
    private function setDataWorksheet()
    {
        $this->_objDataWorksheet = new \PHPExcel_Worksheet($this->_objPHPExcel, 'Data');
        $this->_objPHPExcel->addSheet($this->_objDataWorksheet, 1);
        $this->_objPHPExcel->setActiveSheetIndex(1);
        return $this->_objPHPExcel;
    }

    /**
     * @param $properties
     * @param $features
     * @return $this
     */
    public function setDataHeaders($properties, $features)
    {
        $this->_objPHPExcel->setActiveSheetIndex(1);

        // #####>> Sets Headers to Data Worksheet#####
        $colCount = 0;
        foreach ($properties as $p) {
            $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colCount, 1, $p->title);
            $colCount++;
        }
        $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colCount, 1, '_lonlat');
        $colCount++;
        foreach ($features[0]->_geo as $k=>$v) {
            $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colCount, 1, '_'.$k);
            $colCount++;
        }
        return $this;
    }

    /**
     * @param $data
     * @param $properties
     * @return $this
     */
    public function setData($data, $properties)
    {
        $this->_objPHPExcel->setActiveSheetIndex(1);

        $row = 0;
        foreach ($data as $d) {
            $column = 0;
            foreach ($properties as $p) {
                $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row+2, $d->properties->{$p->title});
                $column ++;
            }

                $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row+2, $d->geometry->coordinates[0].",". $d->geometry->coordinates[1]);
                $column ++;

            foreach ($d->_geo as $g=>$v) {
                $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row+2, $v);
                $column ++;
            }

            $row ++;
        }

        return $this;
    }

    /**
     * @param $properties
     * @return $this
     */
    public function setProperties($properties)
    {
        $this->_objMetaWorksheet = new \PHPExcel_Worksheet($this->_objPHPExcel, 'Properties');
        $this->_objPHPExcel->addSheet($this->_objMetaWorksheet, 2);
        $this->_objPHPExcel->setActiveSheetIndex(2);
        $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Champ');
        $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Type');
        $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Description');
        $row = 2;
        foreach ($properties as $p) {
            $column = 0;
            foreach ($p as $type=>$value) {
                $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $value);
                $column ++;
            }

            $row ++;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getProperties(){
        $this->_objPHPExcel->setActiveSheetIndex(3);
        $objWorksheet = $this->_objPHPExcel->getActiveSheet();

        $header = array();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

        for ($col = 0; $col < $highestColumnIndex; ++$col) {
            $header[] = $objWorksheet->getCellByColumnAndRow($col, 1)->getValue();
        }

        $cells = array();
        $rows =0;
        foreach ($objWorksheet->getRowIterator() as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $cols =0;
            foreach ($cellIterator as $cell) {
                //echo "($cols,$rows)<br>";
                $cells[$rows][$header[$cols]] = $cell->getValue();
                $cols ++;
            }
            $rows++;
        }

        $cells = array_slice($cells,1);
        return $cells;
    }

    /**
     * @return array
     */
    public function getData(){
        $this->_objPHPExcel->setActiveSheetIndex(2);
        $objWorksheet = $this->_objPHPExcel->getActiveSheet();

        $header = array();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

        for ($col = 0; $col < $highestColumnIndex; ++$col) {
            $header[] = $objWorksheet->getCellByColumnAndRow($col, 1)->getValue();
        }

        $cells = array();
        $rows =0;
        foreach ($objWorksheet->getRowIterator() as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $cols =0;
            foreach ($cellIterator as $cell) {
                //echo "($cols,$rows)<br>";
                $cells[$rows][$header[$cols]] = $cell->getValue();
                $cols ++;
            }
            $rows++;
        }

        $cells = array_slice($cells,1);
        return $cells;
    }

    /**
     * @param string $dir
     * @param string $fileName
     * @return $this
     */
    public function save($dir='',$fileName='')
    {
        $this->_objWriter = new \PHPExcel_Writer_Excel2007($this->_objPHPExcel);
        $this->_objWriter->save($dir.'/'.$fileName.'.xlsx');
        return $this;
    }
}