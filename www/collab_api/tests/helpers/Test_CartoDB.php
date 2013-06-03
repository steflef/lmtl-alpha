<?php

class Test_CartoDB extends PHPUnit_Framework_TestCase
{
    public $baseUrl = 'http://localhost/lmtl_alpha/www/collab_api';
    protected $CartoDB;
    protected $datasetId = '103';
    protected $placeId = '9636';
    protected $regionId = 3;
    protected $lon = -73.59246;
    protected $lat = 45.528293;

    public function __construct (){}

    public static function setUpBeforeClass()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
    }

    function setUp(){
        fwrite(STDOUT, __METHOD__ . "\n");
        $di = new Pimple();

        require(__DIR__ .'/../../cq_atlas/config/config.php');
        // #### Fluent.SQL Factory
        $di['db'] = $di->share(function () use ($di){
            $pdo = new PDO($di['dbDriver'].':'.$di['dbFile']);
            $fpdo = new \FluentPDO($pdo);

            return $fpdo;
        });

        $this->CartoDB = new \CQAtlas\Helpers\CartoDB($di);
    }

    public function test_getSchema()
    {
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");
        // PLACES
        $placesSchema = $this->CartoDB->getSchema("places");
        $this->assertInternalType('array',$placesSchema);

        // DATASETS
        $datasetsSchema = $this->CartoDB->getSchema("datasets");
        $this->assertInternalType('array',$datasetsSchema);

        // CATEGORIES
        $catSchema = $this->CartoDB->getSchema("categories");
        $this->assertInternalType('array',$catSchema);

        // REGIONS
        $regionsSchema = $this->CartoDB->getSchema("regions");
        $this->assertInternalType('array',$regionsSchema);

        try {
            // ... Code that is expected to raise an exception ...
            $this->CartoDB->getSchema("noschema");
        }

        catch (\Exception $expected) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

     public function test_selectAll(){
         fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");
         $tableName = 'places';
         $query = 'LIMIT 1';
         $type = 'list';
         fwrite(STDOUT,  "QUERYING CARTOBD .... PROCESSING ....\n");
         $results = $this->CartoDB->selectAll($tableName, $query, $type);
         $this->assertInternalType('array',$results);

         fwrite(STDOUT,  "Testing geo query\n");
         $type = 'geo';
         fwrite(STDOUT,  "QUERYING CARTOBD .... PROCESSING ....\n");
         $results = $this->CartoDB->selectAll($tableName, $query, $type);
         $this->assertInternalType('array',$results);
         $this->assertEquals('FeatureCollection', $results['geoJson']['type']);
     }

    public function test_getPlacesCount(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");
        $results = $this->CartoDB->getPlacesCount();
        $this->assertEquals($results,0);


        $results = $this->CartoDB->getPlacesCount($this->datasetId);
        $this->assertEquals($results,795);
        $this->assertGreaterThan(0,$results);
    }

    public function test_getExtent()
    {
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");
        $results = $this->CartoDB->getExtent();
        $this->assertEquals($results,0);

        $results = $this->CartoDB->getExtent($this->datasetId);
        $this->assertInternalType('array',$results);
        $this->assertEquals($results['type'],'Polygon');
    }

/*    public function createDataset(){
        $dataset = new stdClass;

        $dataset->google_drive_id = '';
        $dataset->collection_id = 0;
        $dataset->privacy = 1;
        $dataset->status = 0;
        $dataset->created_by = 2999;
        $dataset->sources = '';
        $dataset->licence = '';
        $dataset->description = 'UNIT TESTING';
        $dataset->name = 'UNIT TEST';
        $dataset->label = '';
        $dataset->tertiary_category_id = 0;
        $dataset->secondary_category_id = 0;
        $dataset->primary_category_id = 0;

        //$results = $this->CartoDB->createDataset($dataset);
    }


    public function addPlaces()
    {
        $places = array();
        //$results = $this->CartoDB->addPlaces(Array $places);
    }*/

    public function test_getPlace(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");
        //VALID
        $results = $this->CartoDB->getPlace($this->placeId);
        $this->assertInternalType('array',$results);
        $this->assertEquals($results['geoJson']['type'],'FeatureCollection');

        //INVALID ID
        $results = $this->CartoDB->getPlace();
        $this->assertInternalType('array',$results);
        $this->assertEquals(count($results['geoJson']['features']),0);

    }


    public function test_getPlaces(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->CartoDB->getPlaces($this->datasetId);
        $this->assertInternalType('array',$results);
        $this->assertEquals($results['geoJson']['type'],'FeatureCollection');

        //INVALID ID
        $results = $this->CartoDB->getPlaces();
        $this->assertInternalType('array',$results);
        $this->assertEquals(count($results['geoJson']['features']),0);
    }

    public function test_getPlacesWithin(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->CartoDB->getPlacesWithin($this->regionId);
        $this->assertInternalType('array',$results);
        $this->assertEquals($results['geoJson']['type'],'FeatureCollection');
        $this->assertInternalType('array',$results);
        $this->assertEquals(count($results['geoJson']['features']),183);

        //INVALID ID
        $results = $this->CartoDB->getPlacesWithin();
        $this->assertInternalType('array',$results);
        $this->assertEquals(count($results['geoJson']['features']),0);
    }

    // LONG test
/*    public function test_getRegions(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->CartoDB->getRegions();
        $this->assertInternalType('array',$results);
        $this->assertEquals($results['geoJson']['type'],'FeatureCollection');
    }*/

    public function test_getRegionsIn(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");


        $results = $this->CartoDB->getRegionsIn('3,4');
        $this->assertInternalType('array',$results);
        $this->assertEquals($results['geoJson']['type'],'FeatureCollection');
        $this->assertEquals(count($results['geoJson']['features']),2);
    }

    public function test_getRegion(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->CartoDB->getRegion($this->regionId);
        $this->assertInternalType('array',$results);
        $this->assertEquals($results['geoJson']['type'],'FeatureCollection');

        // INVALID
        $results = $this->CartoDB->getRegion();
        $this->assertInternalType('array',$results);
        $this->assertEquals($results['geoJson']['type'],'FeatureCollection');
        $this->assertEquals(count($results['geoJson']['features']),0);
    }

    public function test_getRegionsList(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->CartoDB->getRegionsList();
        $this->assertInternalType('array',$results);
        $this->assertGreaterThan(0,$results['total_rows']);
    }

    public function test_getPlacesNear(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->CartoDB->getPlacesNear($this->placeId,$this->lon,$this->lat);
        $this->assertInternalType('array',$results);
        $this->assertEquals($results['geoJson']['type'],'FeatureCollection');
    }

    public function test_getDatasets(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->CartoDB->getDatasets();
        $this->assertInternalType('array',$results);
        $this->assertEquals($results['geoJson']['type'],'FeatureCollection');
    }
}