<?php


class Test_DelimitedReader extends PHPUnit_Framework_TestCase{

    public function __construct (){}

    public static function setUpBeforeClass()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
    }

    function setUp(){
        fwrite(STDOUT, __METHOD__ . "\n");
    }

    public function test_csv(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $source = '/Applications/MAMP/htdocs/lmtl_alpha/www/collab_api/storage/tests/testdots.csv';
        $headerRow = 0;
        $delimiter = ',';
        $Reader = new CQAtlas\Helpers\DelimitedReader($source,$headerRow,$delimiter);

        $rowsCount = $Reader->getRowsCount();
        $this->assertInternalType('array',$rowsCount);

        $rows = $Reader->getRows();
        $this->assertInternalType('array',$rows);
    }

    public function test_scsv(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $source = '';
        $headerRow = 0;
        $delimiter = ';';
        $Reader = new CQAtlas\Helpers\DelimitedReader($source,$headerRow,$delimiter);

        $rowsCount = $Reader->getRowsCount();
        $this->assertInternalType('array',$rowsCount);

        $rows = $Reader->getRows();
        $this->assertInternalType('array',$rows);
    }

    public function test_psv(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $source = '';
        $headerRow = 0;
        $delimiter = '|';
        $Reader = new CQAtlas\Helpers\DelimitedReader($source,$headerRow,$delimiter);

        $rowsCount = $Reader->getRowsCount();
        $this->assertInternalType('array',$rowsCount);

        $rows = $Reader->getRows();
        $this->assertInternalType('array',$rows);
    }
}