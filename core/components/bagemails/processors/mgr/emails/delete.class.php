<?php

class BagEmailsEmailsDeleteProcessor extends modObjectRemoveProcessor
{
    public $classKey = 'bagEmailsHistory';
    public $languageTopics = array('bagemails:default');
    public $objectType = 'bagEmailsHistory';
}

return 'BagEmailsEmailsDeleteProcessor';