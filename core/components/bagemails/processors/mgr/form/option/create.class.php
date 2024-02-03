<?php

class BagEmailsFormOptionCreateProcessor extends modObjectCreateProcessor
{
    public $classKey = 'bagEmailsFormFields';
    public $languageTopics = array('bagemails:default');
    public $objectType = 'bagEmailsFormFields';
    /** @var bagEmailsFormFields $object */
    public $object;
}

return 'BagEmailsFormOptionCreateProcessor';
