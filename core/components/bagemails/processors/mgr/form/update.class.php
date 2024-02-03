<?

class BagEmailsFormUpdateProcessor extends modObjectUpdateProcessor
{
    public $classKey = 'bagEmailsForm';
    public $languageTopics = array('bagemails:default');
    public $objectType = 'bagEmailsForm';
    
    public $object;
}

return 'BagEmailsFormUpdateProcessor';