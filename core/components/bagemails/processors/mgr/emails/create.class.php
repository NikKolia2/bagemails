<?php

class BagEmailsEmailsCreateProcessor extends modObjectCreateProcessor
{
    public $classKey = 'bagEmailsHistory';
    public $languageTopics = array('bagemails:default');
    public $objectType = 'bagEmailsHistory';
    /** @var bagEmailsHistory $object */
    public $object;

    public function beforeSet() { 
        $fieldsRow = $this->modx->getCollection("bagEmailsFormFields", ["form_id" => $this->properties["form_id"]]);
        
        $data = [];
        foreach($fieldsRow as $row){
            if(isset($this->properties[$row->get("key")])){
                $data[$row->get("key")] = $this->properties[$row->get("key")];
            }
        }

        $this->properties["data"] = $this->modx->toJSON($data);
        $this->properties["createdon"] = date("Y-m-d H:s:m");
        return !$this->hasErrors(); 
    } 
}


return 'BagEmailsEmailsCreateProcessor';
