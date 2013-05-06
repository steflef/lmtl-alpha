<?php

namespace CQAtlas\Helpers;

class CountFormatter extends AbstractFormatter
{
    /**
     * @var array
     */
    protected $_row;

    /**
     * @param $source
     * @param $CartoDB
     * @param string $row
     */
    public function __construct($source,$CartoDB,$row)
    {
        parent::__construct($source,$CartoDB);
        $this->_row = $row;
        //return $row['total'];
        //return $this->getOutput();
    }

    public function getOutput(){
        //return $this->_row;
        return $this->_row['total'];
       // return $this->CartoDB->getPlacesCount($this->_row['id']);
    }
}