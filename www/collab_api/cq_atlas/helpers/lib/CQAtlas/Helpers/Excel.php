<?php

namespace CQAtlas\Helpers;

class Excel
{
    private $_objPHPExcel;
    private $_objMetaWorksheet;
    private $_objDataWorksheet;
    private $_objWriter;
    private $_name;
    private $_desc;

    private $_sheetProperties;

    public function __construct($name='', $desc='')
    {
        $this->_name = $name;
        $this->_desc = $desc;
        //$this->setSheet();
    }

    public function setSheet()
    {
        $this->_objPHPExcel = new \PHPExcel();
        $this->setSheetProperties();
        $this->setMetaWorksheet();
        $this->setDataWorksheet();
        return $this;
    }

    public function getSheet($sourcePath)
    {
        $this->_objPHPExcel = \PHPExcel_IOFactory::load($sourcePath);
        $this->_sheetProperties = $this->getSheetProperties();
        //$this->getMetaWorksheet();
        //$this->getDataWorksheet();
    }

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


/*    public function setMetas($meta=array())
    {
        $this->_objPHPExcel->setActiveSheetIndex(0);
        $rowCount =2;
        foreach ($meta as $desc=>$val) {
            $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rowCount, $desc);
            $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $rowCount, $val);
            $rowCount++;
        }
        return $this;
    }*/

    // OBJ
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

/*        $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rowCount, 'Jeu de données');
        $rowCount++;
        foreach ($meta->form as $attribute=>$obj) {
            $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rowCount, $attribute);
            $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $rowCount, $obj->value);
            $rowCount++;
        }*/

        return $this;
    }

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


    private function setDataWorksheet()
    {
        $this->_objDataWorksheet = new \PHPExcel_Worksheet($this->_objPHPExcel, 'Data');
        $this->_objPHPExcel->addSheet($this->_objDataWorksheet, 1);
        $this->_objPHPExcel->setActiveSheetIndex(1);
        return $this->_objPHPExcel;
    }

/*    public function setDataHeaders($headers=array())
    {
        $this->_objPHPExcel->setActiveSheetIndex(1);

        // #####>> Sets Headers to Data Worksheet#####
        $colCount = 0;
        foreach ($headers as $header) {
            $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colCount, 1, $header['title']);
            $colCount++;
        }

        return $this;
    }*/

    // OBJ
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


    // #####>> Add Data to Data Worksheet#####
/*    public function setData($data=array())
    {
        $this->_objPHPExcel->setActiveSheetIndex(1);

        for($r=0;$r<count($data);$r++){
            for($c=0;$c<count($data[$r]);$c++){
                $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($c, $r+2, $data[$r][$c]);
            }
        }
        return $this;
    }*/

    // OBJ
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
/*                echo '<pre><code>';
                print_r($g);
                print_r($v);
                echo '</code></pre>';*/
                $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row+2, $v);
                $column ++;
            }

            $row ++;
        }

/*
        for($r=0;$r<count($data);$r++){
            for($c=0;$c<count($data[$r]);$c++){
                $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($c, $r+2, $data[$r][$c]);
            }
        }*/
        return $this;
    }

    // OBJ
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

    public function save($dir='',$fileName='')
    {
        $this->_objWriter = new \PHPExcel_Writer_Excel2007($this->_objPHPExcel);
        $this->_objWriter->save($dir.'/'.$fileName.'.xlsx');
        return $this;
    }
}