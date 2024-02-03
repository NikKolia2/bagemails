<?php

/**
 * Get list Option
 *
 * @package bagemails
 * @subpackage processors
 */

class BagEmailsEmailsExportProcessor extends modObjectGetListProcessor
{
    public $defaultSortField = 'createdon';
    public $classKey = 'bagEmailsHistory';
    public $languageTopics = array('bagemails:default');
    public $objectType = 'bagEmailsHistory';

    public function prepareQueryBeforeCount($c){
        $c->where(["form_id" => $this->properties["form_id"]]);

        if ($date_start = $this->getProperty('date_start')) {
            $c->andCondition(array(
                'createdon:>=' => date('Y-m-d 00:00:00', strtotime($date_start)),
            ), null, 1);
        }
        if ($date_end = $this->getProperty('date_end')) {
            $c->andCondition(array(
                'createdon:<=' => date('Y-m-d 23:59:59', strtotime($date_end)),
            ), null, 1);
        }

        return $c;
    }

    public function prepareRow($object){
        $data = json_decode($object->get("data"), true); 
        $o = [
            "id" => $object->get("id"),
            "createdon" => $object->get("createdon"),
        ];

        return array_merge($o, $data);
    }

    public function afterIteration(array $list) {
        $this->modx->bagemails->export($list);
        return [];
    }
    
}

return 'BagEmailsEmailsExportProcessor';