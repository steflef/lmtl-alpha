<?php

namespace CQAtlas\Helpers;

class ApiGeoJsonFormatter
{
    private $_source;
    private $_schema;
    private $CartoDB;
    private $_type = 'geo';
    protected $cache = array();

    public function __construct($source, $schema, $CartoDB)
    {
        ini_set('memory_limit', '256M');
        $this->_source = $source;
        $this->_schema = $schema;
        $this->CartoDB = $CartoDB;
        return $this;
    }

    private function getFields(){
        return $this->_schema['fields'];
    }

    private function getVirtualFields(){
        if(array_key_exists('virtual_fields',$this->_schema))
            return $this->_schema['virtual_fields'];

        return array();
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

    public function setType($type){
        $this->_type = $type;
        return $this;
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

    protected function getFormatter($formatter, $source){
        $className = '\\CQAtlas\\Helpers\\'.$formatter.'Formatter';
        $Exp = new $className($source, $this->CartoDB);
        return $Exp->getOutput();
    }

    /**
     * @return array
     */
    public function getOutput(){

        switch($this->_type){
            case 'list':
                return $this->getListOutput();
            break;
            case 'geo':
            default:
                return $this->getGeoOutput();
            break;
        }
    }

    /**
     * @return array
     */
    private function getListOutput(){

        $tmpl = $this->getOutputTemplate();
        $expendFields = $this->getExpandedFields();
        $data = $this->getSource();
        $output = array();

        foreach ($data['rows'] as $row) {
            $tempTmpl = $tmpl;

            foreach ($tmpl as $item=>$targetField)
            {

                if( is_array( $targetField ) )
                {
                    foreach ($targetField as $itemLevel2=>$level2TargetField)
                    {
                        if( array_key_exists((String)$level2TargetField,$row) )
                        {
                            if( array_key_exists((String)$level2TargetField,$expendFields) )
                            {
                                $cacheKey = $expendFields[$level2TargetField]['func'].'_'.$row[$level2TargetField];
                                if($value = $this->getCache($cacheKey))
                                {
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

                            unset ($tempTmpl[$item][$itemLevel2]);
                            //$tempTmpl[$item][$itemLevel2]='';
                        }
                    }
                }else
                {

                    if( array_key_exists((String)$targetField,$row) )
                    {
                        if( array_key_exists((String)$targetField,$expendFields) )
                        {
                            $cacheKey = $expendFields[$targetField]['func'].'_'.$row[$targetField];
                            if($value = $this->getCache($cacheKey))
                            {
                                # From Cache
                                $tempTmpl[$item]=$value;
                            }else{

                                # Set Cache
                                $className = '\\CQAtlas\\Helpers\\'.$expendFields[$targetField]['func'].'Formatter';
                                $Exp = new $className($row[$targetField], $this->CartoDB, $row);
                                $tempTmpl[$item]=$Exp->getOutput();
                                $this->setCache($cacheKey,$tempTmpl[$item]);
                            }
                        }else{

                            $tempTmpl[$item]=$row[$targetField];
                        }

                    }else{

                        if( array_key_exists((String)$targetField,$expendFields) )
                        {
                            if($expendFields[$targetField]['dependency'] === 'none'){
                                //echo (String)$targetField;
                                $className = '\\CQAtlas\\Helpers\\'.$expendFields[$targetField]['func'].'Formatter';
                                $Exp = new $className($row, $this->CartoDB);
                                $tempTmpl[$item]=$Exp->getOutput();
                            }else{
                                unset ($tempTmpl[$item]);
                            }

                        }else{
                            unset ($tempTmpl[$item]);
                           // $tempTmpl[$item]='';
                        }
                    }
                }

                if( is_array( $targetField) ){
                    if( empty($tempTmpl[$item])){
                        unset($tempTmpl[$item]);
                    }
                }

            }
            // Distance calculation
            if( array_key_exists('distance',$row) )
            {
                $tempTmpl['distance']=$row['distance'];
            }

            # $output[] = $tempTmpl;

            $output[$tempTmpl['id']] = $tempTmpl;
        }

        return $output;
    }

    /**
     * @return array
     */
    private function getGeoOutput(){

        $expendFields = $this->getExpandedFields();
        $data = $this->getSource();
        $output = array();
        $virtualFields = $this->getVirtualFields();

        $output['geoJson'] = array(
            'type' => 'FeatureCollection',
            'features' => array()
        );


        foreach ($data['rows'] as $row) {


            $tempTmpl = array();
            foreach ($row as $attribute=>$value) {
                //print_r($attribute);


                if( array_key_exists((String)$attribute,$expendFields) )
                {
                    $cacheKey = $expendFields[$attribute]['func'].'_'.$row[$attribute];
                    if($value = $this->getCache($cacheKey))
                    {
                        # From Cache
                        $tempTmpl[$attribute]=$value;
                    }else{

                        # Set Cache
                        $className = '\\CQAtlas\\Helpers\\'.$expendFields[$attribute]['func'].'Formatter';
                        $Exp = new $className($row[$attribute], $this->CartoDB, $row);
                        $tempTmpl[$attribute]=$Exp->getOutput();
                        $this->setCache($cacheKey,$tempTmpl[$attribute]);
                    }
                }else{

                    $tempTmpl[$attribute]=$value;
                }

                // Virtual Fields
                foreach ($virtualFields as $virtua=>$params) {

                    # Set Cache
                    $className = '\\CQAtlas\\Helpers\\'.$params['func'].'Formatter';
                    $Exp = new $className($row[$attribute], $this->CartoDB, $row);
                    $tempTmpl[$virtua]=$Exp->getOutput();
                }


                // Distance calculation
//                if( array_key_exists('distance',$row) )
//                {
//                    $tempTmpl['distance']=$row['distance'];
//                }


                //$output[] = $tempTmpl;
            }

            $feature = array(
                'id' => '',
                'geometry' => array(
                    'type' => 'Point',
                    'coordinates' => array()
                ),

//                'crs' => array(
//                    'type' => 'EPSG',
//                    'properties' => array(
//                        'code' => 4326
//                    )
//                ),
                'properties' => $tempTmpl
            );

            if(array_key_exists('the_geom',$tempTmpl)){

                $feature['geometry'] = ($tempTmpl['the_geom'] == '')?'null':$tempTmpl['the_geom'];
                unset($feature['properties']['the_geom']);
            }else{
                $feature['geometry'] = 'null';
            }
            if(array_key_exists('id',$feature['properties'])){
                $feature['id'] = $feature['properties']['id'];
            }

            $output['geoJson']['features'][] = $feature;

        }

/*        echo '<pre><code>';
        print_r($output);
        echo '</code></pre>';
        exit;*/
        return $output;
    }
}