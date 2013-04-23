<?php

namespace CQAtlas\Helpers;

class Response
{
    protected $_response;
    protected $_extraContent;
    protected $_status;
    protected $_msg;
    protected $_contentType;

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

    public function setStatus($status=200){
        $this->_status = $status;
        return $this;
    }

    public function getStatus(){
        return $this->_status;
    }

    public function setMessage($msg='ok'){
        $this->_msg = $msg;
        return $this;
    }

    public function getMessage(){
        return $this->_msg;
    }

    public function setContentType($contentType='application/json'){
        $this->_contentType = $contentType;
        return $this;
    }

    public function getContentType(){
        return $this->_contentType;
    }

    public function addContent(Array $content){
        $this->_extraContent = $content;
        return $this;
    }

    public function getContent(){
        return $this->_extraContent;
    }

    public function toArray(){
        $basic = array(
            "status" => $this->_status,
            "msg" => $this->_msg
        );

        return array_merge($basic, $this->_extraContent);
    }

    public function show()
    {
/*        $response = array(
            "status" => $this->_status,
            "msg" => $this->_msg
        );*/

        $this->_response['Content-Type'] = $this->_contentType;
        $this->_response['Encoding'] = ' UTF-8';
        $this->_response->status($this->_status);
        //echo json_encode($response);
        $this->_response->body(json_encode($this->toArray()));
    }
}