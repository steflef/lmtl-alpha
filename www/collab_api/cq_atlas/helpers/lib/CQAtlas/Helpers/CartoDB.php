<?php

namespace CQAtlas\Helpers;

class CartoDB
{
    private $_di;

    private $_schema = array(
        'places'=>array(
            'fields'=>array(
                'address'   => array('type'=> 'string'),
                'city'      => array('type' =>'string'),
                'latitude'  => array('type' =>'number'),
                'location'  => array('type' =>'string'),
                'longitude' => array('type' =>'number'),
                'the_geom'=> array('type' =>'geom'),
                'postal_code' =>array('type' =>'string'),
                'tel_number' =>array('type' =>'string'),
                'website'   => array('type' =>'string'),
                'created_by' => array('type' =>'number'),
                'dataset_id' => array('type' =>'number'),
                'description'   => array('type' =>'string'),

                'label'     => array('type' =>'string'),
                'name_fr'   => array('type' =>'string'),
                'name_en'   => array('type' =>'string'),
                'place_id'  => array('type' =>'number'),
                'privacy'   => array('type' =>'number'),
                'slug'      => array('type' =>'string'),
                'status'    => array('type' =>'number'),
                'version'   => array('type' =>'number'),
                'primary_category_id' => array('type' =>'number'),
                'secondary_category_id' => array('type' =>'number'),
                'tags'      => array('type' =>'string'),
                'updated_at'=> array('type' =>'string')
            ),
            'output_tmpl'=>array(
                'id'        => 'place_id',
                'name_fr'      => 'name_fr',
                'name_en'      => 'name_en',
                'dataset'   => 'dataset_id',
                'description'   => 'description',
                'label'     => 'label',
                'location'  => array(
                    'address'       => 'address',
                    'city'          => 'city',
                    'latitude'      => 'latitude',
                    'longitude'     => 'longitude',
                    'postal_code'   => 'postal_code'
                ),
                'contacts'  => array(
                    'phone'   => 'tel_number',
                    'website' => 'website'
                ),
                'categories'  => array(
                    'primary_category'   => 'primary_category_id',
                    'secondary_category' => 'secondary_category_id'
                ),
                'tags' => 'tags',
                'updated_at' => 'updated_at'
            ),
            'output_exp'=>array(
                'dataset_id' => array(
                    'func' => 'Dataset',
                    'dependency' => 'dataset_id'
                ),
                'primary_category_id' => array(
                    'func' => 'Category',
                    'dependency' => 'primary_category_id'
                ),
                'secondary_category_id' => array(
                    'func' => 'Category',
                    'dependency' => 'secondary_category_id'
                ),
                'tags' => array(
                    'func' => 'Json2array',
                    'dependency' => 'tags'
                ),
                'the_geom' => array(
                    'func' => 'Json2array',
                    'dependency' => 'the_geom'
                )
            )
        ),
        'datasets'=>array(
            'fields'=>array(
                'attributions'=> array('type' =>'string', 'default' => 'Source:'),
                'the_geom'  => array('type'=> 'geom'),
                'collection_id' => array('type' =>'number', 'default' => 0),
                'created_by' => array('type' =>'number'),
                'dataset_id' => array('type' =>'number'),
                'dataset_extra_fields' => array('type' =>'string'),
                'description'   =>array('type' =>'string'),
                'google_drive_id' => array('type' =>'string'),
                'label'     => array('type' =>'string'),
                'licence'   => array('type' =>'string', 'default' => 'ODbL'),
                'name'   => array('type' =>'string'),
                'privacy'   => array('type' =>'number', 'default' => 1),
                'slug'      => array('type' =>'string'),
                'status'    => array('type' =>'string', 'default' => 'public'),
                'version'   => array('type' =>'number', 'default' => 1),
                'primary_category_id'   => array('type' =>'number'),
                'secondary_category_id' => array('type' =>'number'),
                'tertiary_category_id'  => array('type' =>'number'),
                /*'file_format'   => array('type' =>'string'),
                'file_hash'     => array('type' =>'string'),
                'file_mime'     => array('type' =>'string'),
                'file_size'      => array('type' =>'string'),*/
                'file_uri'      => array('type' =>'string', 'label' => 'URI du fichier'),
                'updated_at'=> array('type' =>'string')
            ),
            'form'=>array(
                'name'   => array('type' =>'text', 'label' => 'Nom/Name', 'value'=>''),
                'desc'   => array('type' =>'text', 'label' => 'Description', 'value'=>''),
                'attributions'=> array('type' =>'text', 'label' => 'Source/Attributions', 'value'=>''),
                'licence'   => array('type' =>'select', 'default' => 'ODbL', 'value'=>0),
                'primary_category_id'   => array('type' =>'select', 'label' => 'Catégorie Primaire', 'value'=>0),
                'secondary_category_id' => array('type' =>'select', 'label' => 'Catégorie Secondaire', 'value'=>0),
                'tertiary_category_id'  => array('type' =>'select', 'label' => 'Catégorie Tertiaire', 'value'=>0),
                'label'  => array('type' =>'select', 'label' => 'étiquette', 'value'=>''),
                'field_category'  => array('type' =>'select', 'label' => 'Catégories', 'value'=>'')
            ),
            'output_tmpl'=>array(
                'id'        => 'dataset_id',
                'collection' => 'collection_id',
                'name'      => 'name',
                'desc'   => 'description',
                'attributions'   => 'attributions',
                'licence'   => 'licence',
                'categories'  => array(
                    'primary_category'   => 'primary_category_id',
                    'secondary_category' => 'secondary_category_id',
                    'tertiary_category_id' => 'tertiary_category_id'
                ),
                'count' => 'count',
                'dataset_extra_fields' => 'dataset_extra_fields',
                'updated_at' => 'updated_at'

            ),
            'output_exp'=>array(
                'primary_category_id' => array(
                    'func' => 'Category',
                    'dependency' => 'primary_category_id'
                ),
                'secondary_category_id' => array(
                    'func' => 'Category',
                    'dependency' => 'secondary_category_id'
                ),
                'tertiary_category_id' => array(
                    'func' => 'Category',
                    'dependency' => 'tertiary_category_id'
                ),
                'dataset_extra_fields' => array(
                    'func' => 'Json2array',
                    'dependency' => 'dataset_extra_fields'
                ),
                'count' => array(
                    'func' => 'Count',
                    'dependency' => 'none'
                )
            )
        ),
        'categories'=>array(
            'fields'=>array(
                'id' => array('type' =>'number'),
                'parent_id' => array('type' =>'number'),
                'parent_fr'   =>array('type' =>'string'),
                'en'   =>array('type' =>'string'),
                'fr'   =>array('type' =>'string'),
                'icon' => array('type' =>'string'),
                'updated_at' => array('type' =>'string')
            ),
            'output_tmpl'=>array(
                'id'        => 'id',
                'group' => 'parent_fr',
                'fr'        => 'fr',
                'i'      => 'icon'
            ),

            'output_exp'=>array(
            )
        )
    );

    public function __construct($di)
    {
        $this->_di = $di;
        return $this;
    }

    public function getSchema($table)
    {
        #Get Database Schema
        if(! array_key_exists($table,$this->_schema) ){
            throw new \Exception("$table not found in Schema.");
        }

        return $this->_schema[$table];
    }

    public function batchInsert($table,$data)
    {
        require_once 'vendor/cqatlas/cqatlas/CqUtil.php';
        #Get Database Fields
/*        if(! array_key_exists($table,$this->_schema) ){
            throw new \Exception("$table not found in Schema.");
        }

        $schema = $this->_schema[$table];*/
        $schema = $this->getSchema($table);

        # Build Queries
        $sqlStatements = array();
        $prefix = "INSERT INTO $table ";
        foreach ($data as $row) {
            $fields = array();
            $values = array();
            foreach ($row as $dataField=>$dataValue) {

                if(! array_key_exists($dataField,$schema['fields']) ){
                    continue;
                }
                $fieldType = $schema['fields'][$dataField]['type'];
                $insertData = ($fieldType === 'string')?"'".str_replace("'","''",$dataValue)."'":$dataValue;
                $fields[]=$dataField;
                $values[]=$insertData;
            }
            $sqlStatements[] = $prefix.'('.implode(',',$fields).') VALUES ('.implode(',',$values).');';
        }


        $postFields = array(
            'q' => implode('',$sqlStatements),
            'api_key' => $this->_di['cartodb_api_key']
        );

        $url = 'http://'.$this->_di['cartodb_subdomain'].'.'.$this->_di['cartodb_endpoint'];

        $curlResult = \CqUtil::curlPost($url, json_encode($postFields));
        return $curlResult;
    }

    public function selectAll($tableName='',$where='')
    {
        #Get Database Fields
        if(! array_key_exists($tableName,$this->_schema) ){
            throw new \Exception("$tableName not found in Schema.");
        }

        # Build Queries
        $fields = $this->getFields($tableName);
        $sqlStatement = "SELECT ".implode(',',$fields)." FROM $tableName $where ;";

/*        echo "\n\r $sqlStatement <br> \n\r";
        exit;*/
        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();

        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::selectAll status '.$response->getStatusCode());
        }

        $Formatter = new \CQAtlas\Helpers\ApiJsonFormatter($response->json(), $this->getSchema($tableName), $this);
        return $Formatter->getOutput();
    }

    public function getPlacesCount($datasetId=0)
    {

        # Build Queries
        $sqlStatement = "SELECT COUNT(*) AS total FROM places WHERE dataset_id = $datasetId;";
        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();

        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::getPlacesCount status '.$response->getStatusCode());
        }

        $results = $response->json();
        return $results['rows'][0]['total'];
    }

    protected function getBBox(Array $data){

        $minLat = $data[0]['latitude'];
        $minLon = $data[0]['longitude'];
        $maxLat = $data[0]['latitude'];
        $maxLon = $data[0]['longitude'];

        foreach ($data as $value) {
            $minLat = ( $minLat > $value['latitude'])?$value['latitude']:$minLat;
            $maxLat = ( $maxLat < $value['latitude'])?$value['latitude']:$maxLat;

            $minLon = ( $minLon > $value['longitude'])?$value['longitude']:$minLon;
            $maxLon = ( $maxLon < $value['longitude'])?$value['longitude']:$maxLon;
        }

        $sql= "ST_GeomFromText('POLYGON(($minLon $maxLat,$minLon $maxLat,$minLon $minLat,$maxLon $minLat,$minLon $maxLat))')";

        return array(
            'minLon' => $minLon,
            'maxLat' => $maxLat,
            'maxLon' => $maxLon,
            'minLat' => $minLat,
            'sql' => $sql
        );
    }

    public function addDataset(\stdClass $metas,Array $datas)
    {
        # Build Querie
        $datasetsfields = $this->getFields('datasets');
        $placesfields = $this->getFields('places');

        $fixDatas = [];
        foreach ($datas as $key=>$value) {
            $fixDatas[$key] = $value;
            //$fixDatas[$key]['longitude'] = $value['lon'];
            //$fixDatas[$key]['latitude'] = $value['lat'];
        }
        $datas = $fixDatas;

        $dataset_extra_fields = [];
        foreach ($datas[0] as $key=>$data) {
            //echo "$key > $data <br>";
            if( !in_array($key,$placesfields) && substr($key,0,1) !== '_'){
                $dataset_extra_fields[] = $key;
            }
        }

        $metas->dataset_extra_fields = json_encode($dataset_extra_fields);

        $newDataset = [];
        foreach ($datasetsfields as $field) {
            if( array_key_exists($field,$metas) ){
                $newDataset[$field] = $metas->$field;
            }
        }

        // Format geom field
       // $bbox = $this->getBBox($datas);
       // $newDataset['the_geom'] = $bbox;

 /*       echo '<pre><code>';
        print_r($metas);
        echo "VS REAL";
        print_r($datasetsfields);

        print_r($dataset_extra_fields);
        print_r($newDataset);
        print_r($datasetsfields);
        print_r($placesfields);
        echo '</code></pre>';
        exit;*/

        //$Formatter = new \CQAtlas\Helpers\SqlDbFormatter($response->json(), $this->getSchema($tableName), $this);
        $fields = array();
        $values = array();
        foreach ($metas as $k=>$v) {
            $fields[] = $k;
            $values[] = $v;
        }

        echo '<pre><code>';
        print_r($fields);
        print_r($values);
        echo '</code></pre>';
exit;

        $sqlStatement = "SELECT ".implode(',',$fields)." FROM $tableName $where ;";
        echo "<br>$sqlStatement";

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        //$response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();
        $response = $client->post('',null, array(
            'q' => $sqlStatement,
            'api_key' => $this->_di['cartodb_api_key']
        ))->send();
        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::addDataset status '.$response->getStatusCode());
        }

        //$Formatter = new \CQAtlas\Helpers\ApiJsonFormatter($response->json(), $this->getSchema($tableName), $this);
        //return $Formatter->getOutput();
        return true;
    }

    public function createDataset(\stdClass $properties)
    {
        $fields = array();
        $values = array();
        foreach ($properties as $k=>$v) {
            $v =(is_string($v))?"'".trim($v)."'":$v;
            $fields[] = $k;
            $values[] = $v;
        }

        $sqlStatement = "INSERT INTO datasets (".implode(',',$fields).") VALUES (".implode(',',$values)." );";

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->post('',null, array(
            'q' => $sqlStatement,
            'api_key' => $this->_di['cartodb_api_key']
        ))->send();
        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::addDataset status '.$response->getStatusCode());
        }

        $query = "SELECT MAX(dataset_id) FROM datasets;";
        $response = $client->get('?q='.$query.'&api_key='.$this->_di['cartodb_api_key'])->send()->json();
        return $response['rows'][0]['max'];
    }

    public function addPlaces(Array $places)
    {
        $statements = array();
        $placeCount = 0;
        foreach ($places as $place) {
            $fields = array();
            $values = array();
            foreach ($place as $k=>$v) {
                $v =(is_string($v) && (substr($v,0,3)!== 'ST_') && $v!=='NULL')?"'".str_replace(  "'", "''",trim($v))."'":$v;
                $fields[] = $k;
                $values[] = $v;
            }

            $statements[] = "INSERT INTO places (".implode(',',$fields).") VALUES (".implode(',',$values)." );";
            $placeCount++;
        }


        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->post('',null, array(
            'q' => implode('',$statements),
            'api_key' => $this->_di['cartodb_api_key']
        ))->send()->json();

        return array( 'cartodb' => $response, 'count' => $placeCount);
    }

    private function getFields($tableName){
        $fields = array();
        foreach ($this->_schema[$tableName]['fields'] as $fieldName=>$fieldMeta) {
            $field = ($fieldMeta['type'] === 'geom')? 'ST_AsGeoJSON('.$fieldName.') AS the_geom':$fieldName;
            $fields[]=$field;
        }
        return $fields;
    }

    public function getPlace($placeId)
    {
        $tableName = 'places';

        # Build Queries
        $fields = $this->getFields($tableName);
        $sqlStatement = "SELECT ".implode(',',$fields)." FROM $tableName WHERE place_id = $placeId;";

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();

        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::getPlace status '.$response->getStatusCode());
        }
        $Formatter = new \CQAtlas\Helpers\ApiJsonFormatter($response->json(), $this->getSchema($tableName), $this);
        return $Formatter->getOutput();
    }

    public function getPlaces($datasetId)
    {
        $tableName = 'places';

        # Build Queries
        $fields = array(
            'place_id',
            'name_fr',
            'description',
            'label',
            'latitude',
            'longitude',
            'primary_category_id'//,
            //'secondary_category_id'
        );
        $sqlStatement = "SELECT ".implode(',',$fields)." FROM $tableName WHERE dataset_id = $datasetId;";

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();

        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::getPlace status '.$response->getStatusCode());
        }

        $Formatter = new \CQAtlas\Helpers\ApiJsonFormatter($response->json(), $this->getSchema($tableName), $this);
        return $Formatter->getOutput();
    }

    public function getPlacesNear($placeId,$lon,$lat,$distance=5000)
    {
        $tableName = 'places';

        # Build Queries
        $fields = $this->getFields($tableName);
        $sqlStatement = "SELECT ".implode(',',$fields).", ST_Distance( ST_Transform(ST_SetSRID(ST_Point($lon,$lat),4326),26918), ST_Transform( the_geom, 26918) ) AS distance ";
        $sqlStatement .="FROM $tableName ";
        $sqlStatement .="WHERE ST_Distance( ST_Transform(ST_SetSRID(ST_Point($lon,$lat),4326),26918), ST_Transform( the_geom, 26918) )< $distance";
        $sqlStatement .="AND place_id <> $placeId ";
        $sqlStatement .="ORDER BY distance asc ";
        $sqlStatement .="LIMIT 20";

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();

        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::getPlace status '.$response->getStatusCode());
        }

        $Formatter = new \CQAtlas\Helpers\ApiJsonFormatter($response->json(), $this->getSchema($tableName), $this);
        return $Formatter->getOutput();
    }
}