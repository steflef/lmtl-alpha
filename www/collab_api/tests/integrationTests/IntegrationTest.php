<?php

//use \Slim\Slim;
require_once __DIR__ . '/../../vendor/autoload.php';

\Slim\Slim::autoload('Slim_Environment');


class IntegrationTest extends PHPUnit_Framework_TestCase {

    protected $backupGlobals = false;
    protected $backupStaticAttributes = false;

    public function request($method, $path, $options=array()) {
        // Capture STDOUT
        ob_start();
    
        // Prepare a mock environment
        \Slim\Environment::mock(array_merge(array(
            'REQUEST_METHOD' => $method,
            'PATH_INFO' => $path,
            'SERVER_NAME' => 'localhost',
            'USER_AGENT' => 'PHPUNIT',
        ), $options));

        // Run the application
        require __DIR__ . '/../index.php';

        $this->app = $app;

        $this->request = $app->request();
        $this->response = $app->response();

        // Return STDOUT
        return ob_get_clean();
    }

    public function get($path, $options=array()) {

        $this->request('GET', $path, $options);
    }

    public function post($path, $options=array()) {
        $this->request('POST', $path, $options);
    }


    public function testIndex() {

        $this->get('/');
        $this->assertEquals('303', $this->response->status());
        $this->assertEquals('http://localhost/lmtl_alpha/www/collab_api/login', $this->response['Location']);
    }

    public function testGetDatasets() {

        $this->get('/datasets/1');
        $this->assertEquals('200', $this->response->status());
        $this->assertEquals('http://localhost/lmtl_alpha/www/collab_api/datasets', $this->response['Location']);
    }



}
