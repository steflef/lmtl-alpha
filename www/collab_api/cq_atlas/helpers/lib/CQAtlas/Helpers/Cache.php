<?php

namespace CQAtlas\Helpers;

/**
 * Class Cache
 * @package CQAtlas\Helpers
 */
class Cache
{
    /**
     * Cache token key name.
     *
     * @var string
     */
    protected $key;

    protected $app;
    protected $di;

    /**
     * @param \Slim\Slim $app
     * @param \Pimple $di
     */
    public function __construct(\Slim\Slim $app, \Pimple $di)
    {
        $this->app = $app;
        $this->di = $di;
        $this->key = $this->slugify($this->app->request()->getResourceUri());
    }

    public function call()
    {

        //echo "Cache Middleware > $key <br>";

        $rsp = $this->app->response();

        $data = $this->fetch();
        if ($data) {
            // cache hit... return the cached content
            $rsp['Content-Type'] = 'application/json';
            $rsp['Encoding'] = 'UTF-8';
            $rsp->body($data);
            $this->app->stop();
        }
    }

    protected function fetch()
    {
        $path = $this->di['storageDir'].'/cache/'.$this->key;
        if( file_exists($path)){
            return file_get_contents($path);
        }

        return false;
    }

    public function save( $body )
    {
        $path = $this->di['storageDir'].'/cache/'.$this->key;
        return file_put_contents($path, $body);
    }

    public function bust( $key )
    {
        $path = $this->di['storageDir'].'/cache/'.$key;
        if(file_exists($path)){
            rename($path, $path.'_old_'.date('c'));
        }

        return true;
    }

    protected function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }
}