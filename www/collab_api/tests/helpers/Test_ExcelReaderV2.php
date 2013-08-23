<?php


class Test_ExcelReaderV2 extends PHPUnit_Framework_TestCase{

    public $Reader;

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

        $sourcePath = '';
        $row = 0;

        $this->Reader = new \CQAtlas\Helpers\ExcelReaderV2($sourcePath,$row);
    }

    public function test_setDataWorksheet(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $activeSheet = 0;
        $results = $this->Reader->setDataWorksheet($activeSheet);
        $this->assertInternalType('array',$results);
    }

    protected function test_getData(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->Reader->getData();
        $this->assertInternalType('array',$results);
    }

    public function test_setHeaderRowNumber(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $number = 0;
        $results = $this->Reader->setHeaderRowNumber($number);
        $this->assertInternalType('array',$results);
    }


    public function test_getHeaderRow(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->Reader->getHeaderRow();
        $this->assertInternalType('array',$results);
    }

    public function test_getRows(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->Reader->getRows();
        $this->assertInternalType('array',$results);
    }

    public function test_getRowsCount(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->Reader->getRowsCount();
        $this->assertInternalType('array',$results);
    }
}