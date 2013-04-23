<?php

namespace CQAtlas\Helpers;

class CategoryFormatter extends AbstractFormatter
{
    public function __construct($source,$CartoDB)
    {
        parent::__construct($source,$CartoDB);
        return $this->getOutput();
    }

    public function getOutput(){

        $output = array(
            'fr' => '',
            'icon' => '',
            'id' => 0
        );

        if($this->_source == '') return $output;

        $Results = $this->CartoDB->selectAll('categories','WHERE id ='.$this->_source.' LIMIT 1');

        if(count($Results) === 1){
            $output['fr'] = $Results[0]['fr'];
            $output['icon'] = $Results[0]['i'];
            $output['id'] = $this->_source;
        }

        return $output;
    }
}