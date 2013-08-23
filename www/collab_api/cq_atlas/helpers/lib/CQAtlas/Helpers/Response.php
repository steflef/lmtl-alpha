<?php

namespace CQAtlas\Helpers;

/**
 * Class Response
 * @package CQAtlas\Helpers
 */
class Response
{
    protected $_response;
    protected $_extraContent;
    protected $_status;
    protected $_msg;
    protected $_contentType;

    /**
     * @param $Response
     * @param int $status
     * @param string $msg
     * @param string $contentType
     */
    public function __construct($Response, $status=200, $msg='ok', $contentType='application/json')
    {
        $this->_response = $Response;
        $this->_extraContent = array();
        $this->_status = $status;
        $this->_msg = $msg;
        $this->_contentType = $contentType;
        $this->_response->status($status);

        return $this;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status=200){
        $this->_status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(){
        return $this->_status;
    }

    /**
     * @param string $msg
     * @return $this
     */
    public function setMessage($msg='ok'){
        $this->_msg = $msg;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(){
        return $this->_msg;
    }

    /**
     * @param string $contentType
     * @return $this
     */
    public function setContentType($contentType='application/json'){
        $this->_contentType = $contentType;
        return $this;
    }

    /**
     * @return string
     */
    public function getContentType(){
        return $this->_contentType;
    }

    /**
     * @param array $content
     * @return $this
     */
    public function addContent(Array $content){
        $this->_extraContent = $content;
        return $this;
    }

    /**
     * @return array
     */
    public function getContent(){
        return $this->_extraContent;
    }

    /**
     * @return array
     */
    public function toArray(){
        $basic = array(
            "status" => $this->_status,
            "msg" => $this->_msg
        );

        return array_merge($basic, $this->_extraContent);
    }

    /**
     * @return string
     */
    public function toJson(){

        return json_encode($this->toArray());
    }

    public function show()
    {
        $this->_response['Content-Type'] = $this->_contentType;
        $this->_response['Encoding'] = ' UTF-8';
        $this->_response->status($this->_status);
        $this->_response->body(json_encode($this->toArray()));
    }
}