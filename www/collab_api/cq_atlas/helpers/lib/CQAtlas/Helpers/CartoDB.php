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
                'longitude' => array('type' =>'number'),
                'the_geom'=> array('type' =>'geom'),
                'postal_code' =>array('type' =>'string'),
                'tel_number' =>array('type' =>'string'),
                'website'   => array('type' =>'string'),
                'created_by' => array('type' =>'number'),
                'dataset_id' => array('type' =>'number'),
                'description'   => array('type' =>'string'),
                'name'   => array('type' =>'string'),
                'id'  => array('type' =>'number'),
                'privacy'   => array('type' =>'number'),
                'status'    => array('type' =>'number'),
                'primary_category_id' => array('type' =>'number'),
                'secondary_category_id' => array('type' =>'number'),
                'tags'      => array('type' =>'string'),
                'updated_at'=> array('type' =>'string')
            ),
            'output_tmpl'=>array(
                'id'        => 'id',
                'name'      => 'name',
                'dataset'   => 'dataset_id',
                'description'   => 'description',
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
//                'dataset_id' => array(
//                    'func' => 'Dataset',
//                    'dependency' => 'dataset_id'
//                ),
//                'primary_category_id' => array(
//                    'func' => 'Category',
//                    'dependency' => 'primary_category_id'
//                ),
//                'secondary_category_id' => array(
//                    'func' => 'Category',
//                    'dependency' => 'secondary_category_id'
//                ),
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
                'sources'=> array('type' =>'string', 'default' => 'Source:'),
                'the_geom'  => array('type'=> 'geom'),
                'collection_id' => array('type' =>'number', 'default' => 0),
                'created_by' => array('type' =>'number'),
                'id' => array('type' =>'number'),
                'attributes' => array('type' =>'string'),
                'description'   =>array('type' =>'string'),
                'google_drive_id' => array('type' =>'string'),
                'label'     => array('type' =>'string'),
                'licence'   => array('type' =>'string', 'default' => 'ODbL'),
                'name'   => array('type' =>'string'),
                'privacy'   => array('type' =>'number', 'default' => 1),
                'slug'      => array('type' =>'string'),
                'status'    => array('type' =>'string', 'default' => 'public'),
                'primary_category_id'   => array('type' =>'number'),
                'secondary_category_id' => array('type' =>'number'),
                'tertiary_category_id'  => array('type' =>'number'),
                'file_uri'      => array('type' =>'string', 'label' => 'URI du fichier'),
                'updated_at'=> array('type' =>'string')
            ),
            'form'=>array(
                'name'   => array('type' =>'text', 'label' => 'Nom/Name', 'value'=>''),
                'desc'   => array('type' =>'text', 'label' => 'Description', 'value'=>''),
                'attributions'=> array('type' =>'text', 'label' => 'Sources', 'value'=>''),
                'licence'   => array('type' =>'select', 'default' => 'ODbL', 'value'=>0),
                'primary_category_id'   => array('type' =>'select', 'label' => 'Catégorie Primaire', 'value'=>0),
                'secondary_category_id' => array('type' =>'select', 'label' => 'Catégorie Secondaire', 'value'=>0),
                'tertiary_category_id'  => array('type' =>'select', 'label' => 'Catégorie Tertiaire', 'value'=>0),
                'label'  => array('type' =>'select', 'label' => 'étiquette', 'value'=>''),
                'field_category'  => array('type' =>'select', 'label' => 'Catégories', 'value'=>'')
            ),
            'output_tmpl'=>array(
                'id'        => 'id',
                'collection' => 'collection_id',
                'name'      => 'name',
                'description'   => 'description',
                'attributions'   => 'sources',
                'licence'   => 'licence',
                'categories'  => array(
                    'primary_category'   => 'primary_category_id',
                    'secondary_category' => 'secondary_category_id',
                    'tertiary_category_id' => 'tertiary_category_id'
                ),
                'count' => 'count',
                'attributes' => 'attributes',
                'updated_at' => 'updated_at'
            ),
            'output_exp'=>array(
//                'primary_category_id' => array(
//                    'func' => 'Category',
//                    'dependency' => 'primary_category_id'
//                ),
//                'secondary_category_id' => array(
//                    'func' => 'Category',
//                    'dependency' => 'secondary_category_id'
//                ),
//                'tertiary_category_id' => array(
//                    'func' => 'Category',
//                    'dependency' => 'tertiary_category_id'
//                ),
                'attributes' => array(
                    'func' => 'Json2array',
                    'dependency' => 'attributes'
                )//,
//                'count' => array(
//                    'func' => 'Count',
//                    'dependency' => 'none'
//                )
            ),
            'virtual_fields'=>array(
                'the_geom' => array(
                    'func' => 'Bbox',
                    'dependency' => 'none'
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
        ),

        'regions'=>array(
            'fields'=>array(
                'id' => array('type' =>'number'),
                'acronyme'      => array('type' =>'string'),
                'nom_abrg'  => array('type' =>'string'),
                'officiel' => array('type' =>'string'),
                'the_geom'=> array('type' =>'geom'),
                'updated_at' => array('type' =>'string')
            ),
            'output_tmpl'=>array(
                'id' => 'id',
                'acronyme'      => 'acronyme',
                'nom_abrg'  => 'nom_abrg',
                'officiel' => 'officiel',
                'the_geom'=> 'the_geom',
                'updated_at' => 'updated_at'
            ),

            'output_exp'=>array(
                'the_geom' => array(
                    'func' => 'Json2array',
                    'dependency' => 'the_geom'
                )
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

/*    public function batchInsert($table,$data)
    {
        require_once 'vendor/cqatlas/cqatlas/CqUtil.php';
        #Get Database Fields

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
    }*/

    /**
     * @param string $tableName
     * @param string $where
     * @param string $type
     * @return array
     * @throws \Exception
     */
    public function selectAll($tableName='',$query='',$type='')
    {
        #Get Database Fields
        if(! array_key_exists($tableName,$this->_schema) ){
            throw new \Exception("$tableName not found in Schema.");
        }

        # Build Queries
        $fields = $this->getFields($tableName);
        $sqlStatement = "SELECT ".implode(',',$fields)." FROM $tableName $query ;";

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();

        if($response->getStatusCode()!==200)
            throw new \Exception('CartoDb::selectAll status '.$response->getStatusCode());

        $Formatter = new \CQAtlas\Helpers\ApiGeoJsonFormatter($response->json(), $this->getSchema($tableName), $this);
        return $Formatter->setType($type)->getOutput();
    }

    /**
     * @param int $datasetId
     * @return mixed
     * @throws \Exception
     */
    public function getPlacesCount($datasetId=0)
    {
        $sqlStatement = "SELECT COUNT(id) AS total FROM places WHERE dataset_id = $datasetId;";

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();

        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::getPlacesCount status '.$response->getStatusCode());
        }

        $results = $response->json();
        return $results['rows'][0]['total'];
    }

    /**
     * @param int $datasetId
     * @return mixed
     * @throws \Exception
     */
    public function getExtent($datasetId=0)
    {
        $sqlStatement = "SELECT ST_AsGeoJson(ST_Extent(the_geom)) AS bbox FROM places WHERE dataset_id = $datasetId;";

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();

        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::getPlacesCount status '.$response->getStatusCode());
        }

        $results =  $response->json();
        return json_decode( $results['rows'][0]['bbox'], true);
    }

    /**
     * @param \stdClass $properties
     * @return mixed
     * @throws \Exception
     */
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

        $query = "SELECT MAX(id) FROM datasets;";
        $response = $client->get('?q='.$query.'&api_key='.$this->_di['cartodb_api_key'])->send()->json();
        return $response['rows'][0]['max'];
    }

    /**
     * @param array $places
     * @return array
     */
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

    /**
     * @param $tableName
     * @return array
     */
    private function getFields($tableName){
        $fields = array();
        foreach ($this->_schema[$tableName]['fields'] as $fieldName=>$fieldMeta) {
            $field = ($fieldMeta['type'] === 'geom')? 'ST_AsGeoJSON('.$fieldName.') AS the_geom':$fieldName;
            $fields[]=$field;
        }
        return $fields;
    }

    /**
     * @param $placeId
     * @return array
     * @throws \Exception
     */
    public function getPlace($placeId)
    {
        $tableName = 'places';

        # Build Queries
        $fields = $this->getFields($tableName);
        $sqlStatement = "SELECT ".implode(',',$fields)." FROM $tableName WHERE id = $placeId;";

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();

        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::getPlace status '.$response->getStatusCode());
        }

        $Formatter = new \CQAtlas\Helpers\ApiGeoJsonFormatter($response->json(), $this->getSchema($tableName), $this);
        return $Formatter->getOutput();
    }

    /**
     * @param $datasetId
     * @return array
     * @throws \Exception
     */
    public function getPlaces($datasetId)
    {
        $tableName = 'places';

        # Build Queries
        $fields = array(
            'id',
            'name',
            'description',
            //'latitude',
            //'longitude',
            'primary_category_id',
            'ST_AsGeoJSON(the_geom) AS the_geom'
        );

        $sqlStatement = "SELECT ".implode(',',$fields)." FROM $tableName WHERE dataset_id = $datasetId ORDER BY updated_at DESC;";

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();

        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::getPlace status '.$response->getStatusCode());
        }

        $Formatter = new \CQAtlas\Helpers\ApiGeoJsonFormatter($response->json(), $this->getSchema($tableName), $this);
        return $Formatter->getOutput();
    }

    public function getPlacesWithin($regionId)
    {
        $tableName = 'places';

        # Build Queries
        $fields = array(
            'places.id',
            'places.name',
            'places.description',
            'places.primary_category_id',
            'ST_AsGeoJSON(places.the_geom) AS the_geom'
        );

        $sqlStatement = "SELECT ".implode(',',$fields)." FROM $tableName,regions WHERE ST_Within(places.the_geom,regions.the_geom) AND regions.id = $regionId ORDER BY places.updated_at DESC;";

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();

        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::getPlace status '.$response->getStatusCode());
        }

        $Formatter = new \CQAtlas\Helpers\ApiGeoJsonFormatter($response->json(), $this->getSchema($tableName), $this);
        return $Formatter->getOutput();
    }

    public function getRegions()
    {
        $tableName = 'regions';

        # Build Queries
        $fields = array(
            'id',
            'nom',
            't_decoup',
            'id_ref',
            'ST_AsGeoJSON(the_geom) AS the_geom'
        );

        $sqlStatement = "SELECT ".implode(',',$fields)." FROM $tableName ORDER BY id ASC;";

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();

        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::getPlace status '.$response->getStatusCode());
        }

        $Formatter = new \CQAtlas\Helpers\ApiGeoJsonFormatter($response->json(), $this->getSchema($tableName), $this);
        return $Formatter->getOutput();
    }


    public function getRegionsIn($ids)
    {
        $tableName = 'regions';

        # Build Queries
        $fields = array(
            'id',
            'nom',
            't_decoup',
            'id_ref',
            'ST_AsGeoJSON(the_geom) AS the_geom'
        );

        $sqlStatement = "SELECT ".implode(',',$fields)." FROM $tableName WHERE id IN($ids);";

/*        echo $sqlStatement;
        exit;*/

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();

        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::getPlace status '.$response->getStatusCode());
        }

        $Formatter = new \CQAtlas\Helpers\ApiGeoJsonFormatter($response->json(), $this->getSchema($tableName), $this);
        return $Formatter->getOutput();
    }

    public function getRegion($id)
    {
        $tableName = 'regions';

        # Build Queries
        $fields = array(
            'id',
            'nom',
            't_decoup',
            'id_ref',
            'ST_AsGeoJSON(the_geom) AS the_geom'
        );

        $sqlStatement = "SELECT ".implode(',',$fields)." FROM $tableName WHERE id=$id LIMIT 1;";

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();

        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::getPlace status '.$response->getStatusCode());
        }
//        print_r( $response->json());
//        echo $response->getBody();
//        exit;
        $Formatter = new \CQAtlas\Helpers\ApiGeoJsonFormatter($response->json(), $this->getSchema($tableName), $this);
        return $Formatter->getOutput();
    }

    public function getRegionsList()
    {
        $tableName = 'regions';

        # Build Queries
        $fields = array(
            'id',
            'nom',
            't_decoup',
            'id_ref',
        );

        $sqlStatement = "SELECT ".implode(',',$fields)." FROM $tableName ORDER BY id ASC;";

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        $response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();

        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::getPlace status '.$response->getStatusCode());
        }

        return $response->json();
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

    public function getDatasets()
    {
        $tableName = 'datasets';

        $sqlStatement = "SELECT datasets.*, sub_1.bbox, sub_2.total  FROM datasets
LEFT JOIN (SELECT dataset_id, ST_AsGeoJson(ST_Extent(the_geom)) AS bbox FROM places GROUP BY dataset_id) sub_1 ON datasets.id = sub_1.dataset_id
LEFT JOIN (SELECT dataset_id, COUNT(id) AS total FROM places GROUP BY dataset_id) sub_2  ON datasets.id = sub_2.dataset_id ORDER BY datasets.updated_at DESC;";

        $client = new \Guzzle\Http\Client('http://steflef.cartodb.com/api/v2/sql');
        //$response = $client->get('?q='.$sqlStatement.'&api_key='.$this->_di['cartodb_api_key'])->send();
        $request = $client->post('',null, array(
            'q' => $sqlStatement,
            'api_key' => $this->_di['cartodb_api_key']
        ));

        $response = $request->send();
//        echo '<pre><code>';
//        print_r($response->json());
//        echo '</code></pre>';
//        exit;
        if($response->getStatusCode()!==200){
            throw new \Exception('CartoDb::getDatasets status '.$response->getStatusCode());
        }

        $Formatter = new \CQAtlas\Helpers\ApiGeoJsonFormatter($response->json(), $this->getSchema($tableName), $this);
        return $Formatter->getOutput();
    }
}