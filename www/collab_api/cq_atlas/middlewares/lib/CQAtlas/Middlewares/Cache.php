<?php

//namespace Slim\Extras\Middleware;
namespace CQAtlas\Middlewares;

class Cache extends \Slim\Middleware
{
    /**
     * Cache token key name.
     *
     * @var string
     */
    protected $key;

    /**
     * Constructor.
     *
     * @param string    $key        The CSRF token key name.
     * @return void
     */
    public function __construct($key = 'csrf_token')
    {
/*        if (! is_string($key) || empty($key) || preg_match('/[^a-zA-Z0-9\-\_]/', $key)) {
            throw new \OutOfBoundsException('Invalid CSRF token key "' . $key . '"');
        }*/

        $this->key = $key;
    }

    // ###Call Middleware
    public function call()
    {
        $key = $this->app->request()->getResourceUri();
        echo "Cache Middleware > $key <br>";
/*        $rsp = $this->app->response();

        $data = $this->fetch($key);
        if ($data) {
            // cache hit... return the cached content
            $rsp["Content-Type"] = $data["content_type"];
            $rsp->body($data["body"]);
            return;
        }*/

        // cache miss... continue on to generate the page
        $this->next->call();

/*        if ($rsp->status() == 200) {
            // cache result for future look up
            $this->save($key, $rsp["Content-Type"], $rsp->body());
        }*/
    }

    protected function fetch($key)
    {
        $query = "SELECT content_type, body FROM cache
            WHERE key = " . $this->db->quote($key);
        $result = $this->db->query($query);
        $row = $result->fetch(\PDO::FETCH_ASSOC);
        $result->closeCursor();
        return $row;
    }

    protected function save($key, $contentType, $body)
    {
        $query = sprintf("INSERT INTO cache (key, content_type, body)
            VALUES (%s, %s, %s)",
            $this->db->quote($key),
            $this->db->quote($contentType),
            $this->db->quote($body)
        );
        $this->db->query($query);
    }

}