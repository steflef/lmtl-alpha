<?php

class Test_CqUtil extends PHPUnit_Framework_TestCase
{
    public $baseUrl = 'http://localhost/lmtl_alpha/www/collab_api';

    public function __construct (){}

    function setUp(){
        echo "PHPUNIT::SETUP -> Test_CqUtil\n";
    }

    public function test_slugify()
    {
        $slug = \CQAtlas\Helpers\CqUtil::slugify("mon École aujourd'hui");
        $this->assertEquals('mon-ecole-aujourd-hui', $slug);
    }

    public function test_matchKeys()
    {
        $keys = array('id','Lng','decription');

        $results = \CQAtlas\Helpers\CqUtil::matchKeys($keys, array('lon','lng','longitude'));
        $this->assertEquals('Lng', $results);

        $results_2 = \CQAtlas\Helpers\CqUtil::matchKeys($keys, array('lat','latitude'));
        $this->assertFalse($results_2);
    }
}