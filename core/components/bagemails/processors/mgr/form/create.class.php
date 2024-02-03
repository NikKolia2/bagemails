<?php

class BagEmailsFormCreateProcessor extends modObjectCreateProcessor
{
    public $classKey = 'bagEmailsForm';
    public $languageTopics = array('bagemails:default');
    public $objectType = 'bagEmailsForm';
    /** @var bagEmailsForm $object */
    public $object;

   
}

return 'BagEmailsFormCreateProcessor';
