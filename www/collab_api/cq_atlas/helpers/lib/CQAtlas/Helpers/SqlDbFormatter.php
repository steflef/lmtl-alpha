<?php

namespace CQAtlas\Helpers;

class SqlDbFormatter
{
    private $_source;
    private $_schema;
    private $CartoDB;
    protected $cache = array();

    public function __construct($source, $schema, $CartoDB)
    {
        $this->_source = $source;
        $this->_schema = $schema;
        $this->CartoDB = $CartoDB;
        return $this;
    }

    private function getFields(){
        return $this->_schema['fields'];
    }

    public function getOutputTemplate(){
        return $this->_schema['output_tmpl'];
    }

    private function getExpandedFields(){
        return $this->_schema['output_exp'];
    }

    private function getSource(){
        return $this->_source;
    }

    protected function setCache($key, $value){
        $this->cache[$key] = $value;
    }

    protected function getCache($key){
        if(array_key_exists($key, $this->cache)){
            return $this->cache[$key];
        }
        return false;
    }

    public function getOutput(){

        $tmpl = $this->getOutputTemplate();

        $expendFields = $this->getExpandedFields();

        $data = $this->getSource();

        $output = array();
        foreach ($data['rows'] as $row) {
            $tempTmpl = $tmpl;

            foreach ($tmpl as $item=>$targetField) {

                if( is_array( $targetField ) ){
                    foreach ($targetField as $itemLevel2=>$level2TargetField) {
                        //echo ">>$itemLevel2=>$level2TargetField<br>";
                        if( array_key_exists((String)$level2TargetField,$row) ){

                            if( array_key_exists((String)$level2TargetField,$expendFields) ){

                                $cacheKey = $expendFields[$level2TargetField]['func'].'_'.$row[$level2TargetField];
                                if($value = $this->getCache($cacheKey)){
                                    # From Cache
                                    $tempTmpl[$item][$itemLevel2]=$value;
                                }else{
                                    # Set Cache
                                    $className = '\\CQAtlas\\Helpers\\'.$expendFields[$level2TargetField]['func'].'Formatter';
                                    $Exp = new $className($row[$level2TargetField], $this->CartoDB);
                                    $tempTmpl[$item][$itemLevel2]=$Exp->getOutput();
                                    $this->setCache($cacheKey,$tempTmpl[$item][$itemLevel2]);
                                }

                            }else{
                                $tempTmpl[$item][$itemLevel2]=$row[$level2TargetField];
                            }
                        }else{
                            $tempTmpl[$item][$itemLevel2]='';
                        }
                    }

                }else{
                    //echo "$item=>$targetField<br>";
                    if( array_key_exists((String)$targetField,$row) ){
                        //echo "OK!! >> $row[$targetField] <br>";
                        if( array_key_exists((String)$targetField,$expendFields) ){

                            $cacheKey = $expendFields[$targetField]['func'].'_'.$row[$targetField];
                            if($value = $this->getCache($cacheKey)){
                                # From Cache
                                $tempTmpl[$item]=$value;
                            }else{
                                # Set Cache
                                $className = '\\CQAtlas\\Helpers\\'.$expendFields[$targetField]['func'].'Formatter';
                                $Exp = new $className($row[$targetField], $this->CartoDB);
                                $tempTmpl[$item]=$Exp->getOutput();
                                $this->setCache($cacheKey,$tempTmpl[$item]);
                            }
                        }else{
                            $tempTmpl[$item]=$row[$targetField];
                        }

                    }else{
                        $tempTmpl[$item]='';
                    }
                }
            }
            // Distance calculation
            if( array_key_exists('distance',$row) ){
                $tempTmpl['distance']=$row['distance'];
            }
            $output[] = $tempTmpl;
        }

        return $output;
    }
}