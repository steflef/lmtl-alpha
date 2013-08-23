<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class Test_Response extends PHPUnit_Framework_TestCase{

    public $Response;

    public function __construct (){}

    public static function setUpBeforeClass()
    {
        fwrite(STDOUT, __METHOD__ . "\n");
    }

    public function setUp(){
        fwrite(STDOUT, __METHOD__ . "\n");
        $di = new Pimple();

        require(__DIR__ .'/../../cq_atlas/config/config.php');
        // #### Fluent.SQL Factory
        $di['db'] = $di->share(function () use ($di){
            $pdo = new PDO($di['dbDriver'].':'.$di['dbFile']);
            $fpdo = new \FluentPDO($pdo);

            return $fpdo;
        });

        $r = new \Slim\Http\Response();
        $this->Response = new \CQAtlas\Helpers\Response($r);

    }

    public function test_setStatus(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $status = 200;
        $results = $this->Response->setStatus($status);
        $this->assertInternalType('array',$results);
    }

    public function test_getStatus(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->Response->getStatus();
        $this->assertInternalType('array',$results);
    }


    public function test_setMessage(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $msg = 'ok';
        $results = $this->Response->setMessage($msg);
        $this->assertInternalType('array',$results);
    }

    public function test_getMessage(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->Response->getMessage();
        $this->assertInternalType('array',$results);
    }

    public function test_setContentType(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $contentType = 'application/json';
        $results = $this->Response->setContentType($contentType);
        $this->assertInternalType('array',$results);
    }

    public function test_getContentType(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->Response->getContentType();
        $this->assertInternalType('array',$results);
    }

    public function test_addContent(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $content = array();
        $results = $this->Response->addContent($content);
        $this->assertInternalType('array',$results);
    }

    public function test_getContent(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->Response->getContent();
        $this->assertInternalType('array',$results);
    }

    public function test_toArray(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->Response->toArray();
        $this->assertInternalType('array',$results);
    }

    public function test_toJson(){
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->Response->toJson();
        $this->assertInternalType('array',$results);
    }

    public function test_show()
    {
        fwrite(STDOUT, "---------------\n-> ".__METHOD__ . "\n");

        $results = $this->Response->show();
        $this->assertInternalType('array',$results);
    }
}