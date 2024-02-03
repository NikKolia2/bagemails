<?php

class BagEmailsFormOptionDeleteProcessor extends modObjectRemoveProcessor
{
    public $classKey = 'bagEmailsFormFields';
    public $languageTopics = array('bagemails:default');
    public $objectType = 'bagEmailsFormFields';
}

return 'BagEmailsFormOptionDeleteProcessor';