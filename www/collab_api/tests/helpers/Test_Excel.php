<?php

class Test_Excel extends PHPUnit_Framework_TestCase{

    public $Excel;

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

        $meta = new stdClass;
        $meta->nom = '';
        $meta->description = '';
        $this->Excel = new \CQAtlas\Helpers\Excel($meta->nom,$meta->description);

/*        $Excel->setSheet()
            ->setMetas($meta)
            ->setDataHeaders($properties, $data->features)
            ->setData($data->features, $properties)
            ->setProperties($properties)
            ->save($di['pubDir'], $meta->nom);*/
    }

    public function test_setSheet(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->Excel->setSheet();
        $this->assertInternalType('array',$results);
    }


    public function test_getSheet(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $sourcePath = '';
        $results = $this->Excel->getSheet($sourcePath);
        $this->assertInternalType('array',$results);
    }

    public function test_setMetas(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $meta = new stdClass();
        $results = $this->Excel->setMetas($meta);
        $this->assertInternalType('array',$results);
    }

    public function test_getMetas(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->Excel->getMetas();
        $this->assertInternalType('array',$results);
    }

    public function test_setDataHeaders(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $properties = '';
        $features = '';
        $results = $this->Excel->setDataHeaders($properties, $features);
        $this->assertInternalType('array',$results);
    }

    public function test_setData(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $data = '';
        $properties = '';

        $results = $this->Excel->setData($data, $properties);
        $this->assertInternalType('array',$results);
    }

    public function test_setProperties(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $properties = '';

        $results = $this->Excel->setProperties($properties);
        $this->assertInternalType('array',$results);
    }

    public function test_getProperties(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->Excel->getProperties();
        $this->assertInternalType('array',$results);
    }

    public function test_getData(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->Excel->getData();
        $this->assertInternalType('array',$results);
    }

    public function test_save(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $dir = '';
        $fileName = '';
        $results = $this->Excel->save($dir,$fileName);
        $this->assertInternalType('array',$results);
    }
}