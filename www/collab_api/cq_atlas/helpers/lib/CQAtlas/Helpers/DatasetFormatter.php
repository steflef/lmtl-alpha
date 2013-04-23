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

        $Results = $this->CartoDB->selectAll('datasets','WHERE dataset_id ='.$this->_source.' LIMIT 1');

        return array(
            'collection' => $Results[0]['collection'],
            'desc' => $Results[0]['desc'],
            'licence' => $Results[0]['licence'],
            'name' => $Results[0]['name']
        );
    }
}