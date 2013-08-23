<?php

class Test_Lmtl extends PHPUnit_Framework_TestCase
{
    //public $baseUrl = 'http://localhost/lmtl_alpha/www/collab_api';

    public function __construct(){}

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
        $this->di = $di;
        require 'models/lmtl.php';
    }

    public function test_checkUser(){
        fwrite(STDOUT, __METHOD__ . "\n");

        $email='';
        $psw='';
        $db = $this->di['db'];

        $results = Lmtl::checkUser( $db, $email, $psw);
        $this->assertInternalType('array',$results);
    }

    public function test_getUserByEmail(){
        fwrite(STDOUT, __METHOD__ . "\n");

        $email='';
        $db = $this->di['db'];
        $results = Lmtl::getUserByEmail( $db, $email);
        $this->assertInternalType('array',$results);
    }

    public function test_getUserById(){
        fwrite(STDOUT, __METHOD__ . "\n");

        $id='';
        $db = $this->di['db'];
        $results = Lmtl::getUserById( $db, $id=0);
        $this->assertInternalType('array',$results);
    }

    public function test_getUsers(){
        fwrite(STDOUT, __METHOD__ . "\n");

        $db = $this->di['db'];
        $results = Lmtl::getUsers( $db );
        $this->assertInternalType('array',$results);
    }

    public function isAdmin(){
        fwrite(STDOUT, __METHOD__ . "\n");

        $email='';
        $db = $this->di['db'];
        $results = Lmtl::isAdmin( $db, $email);
        $this->assertInternalType('array',$results);
    }

    public function updateUser(){
        fwrite(STDOUT, __METHOD__ . "\n");

        $params= array();
        $id='';
        $db = $this->di['db'];
        $results = Lmtl::updateUser( $db,$id,$params);
        $this->assertInternalType('array',$results);
    }

    public function insertUser(){
        fwrite(STDOUT, __METHOD__ . "\n");

        $params = array();
        $db = $this->di['db'];
        $results = Lmtl::insertUser( $db, $params);
        $this->assertInternalType('array',$results);
    }

    public static function deleteUser(){
        fwrite(STDOUT, __METHOD__ . "\n");

        $id = 1;
        $db = $this->di['db'];
        $results = Lmtl::deleteUser($db, $id);
        $this->assertInternalType('array',$results);
    }
}