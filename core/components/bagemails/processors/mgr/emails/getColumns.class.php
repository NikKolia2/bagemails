<?php

/**
 * Get list Option
 *
 * @package bagemails
 * @subpackage processors
 */

class BagEmailsEmailsGetColumnsProcessor extends modObjectGetListProcessor
{
    public $classKey = 'bagEmailsFormFields';
    public $languageTopics = array('bagemails:default');
    public $objectType = 'bagEmailsFormFields';

    
     /**
     * Can be used to adjust the query prior to the COUNT statement
     *
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount($c){
        return $c->where(["form_id" => $this->properties["form_id"]]);
    }

    public function afterIteration(array $list) {
        $fields = array_merge(["id", "createdon"], array_column($list, "key"));
        $columns = array_merge(["id", "Дата"], array_column($list, "name"));

        $list = [
            "fields" => $fields,
            "columns" => $columns
        ];

        return $list;
    }
}

return 'BagEmailsEmailsGetColumnsProcessor';