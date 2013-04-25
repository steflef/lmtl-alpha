<?php

namespace CQAtlas\Helpers;

class DatasetFormatter extends AbstractFormatter
{
    public function __construct($source,$CartoDB)
    {
        parent::__construct($source,$CartoDB);
        return $this->getOutput($source);
    }

    public function getOutput(){

        $Results = $this->CartoDB->selectAll('datasets','WHERE id ='.$this->_source.' LIMIT 1');

        return array(
            'collection' => $Results[0]['collection'],
            'description' => $Results[0]['description'],
            'licence' => $Results[0]['licence'],
            'name' => $Results[0]['name']
        );
    }
}