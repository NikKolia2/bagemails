<?php

/**
 * Get list Option
 *
 * @package bagemails
 * @subpackage processors
 */

class BagEmailsFormOptionGetListProcessor extends modObjectGetListProcessor
{
  
    public $classKey = 'bagEmailsFormFields';
    public $languageTopics = array('bagemails:default');
    public $objectType = 'bagEmailsFormFields';

    public function prepareQueryBeforeCount($c){
        return $c->where(["form_id" => $this->properties["form_id"]]);
    }

    public function prepareRow($object){
        return [
            'id' => $object->get("id"),
            'name' => $object->get("name"),
            'key' => $object->get("key"),
        ]; 
    }
}

return 'BagEmailsFormOptionGetListProcessor';