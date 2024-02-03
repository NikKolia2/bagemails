<?php

/**
 * Get list Option
 *
 * @package bagemails
 * @subpackage processors
 */

class BagEmailsFormGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'bagEmailsForm';
    public $languageTopics = array('bagemails:default');
    public $objectType = 'bagEmailsForm';

     /**
     * Can be used to adjust the query prior to the COUNT statement
     *
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
 
    public function prepareRow($object){
        return [
            'id' => $object->get("id"),
            'name' => $object->get("name"),
        ]; 
    }
}

return 'BagEmailsFormGetListProcessor';