<?php
/**
 * Update controller for BagEmails
 *
 * @package bagemails
 * @subpackage controller
 */
class BagEmailsEmailsManagerController extends modExtraManagerController {
    /** @var modResource $resource */
    public $resource;

    /** @var BagEmailes $bagemails */
    public $bagemails;

    public function initialize()
    {
        $corePath = $this->modx->getOption('bagemails.core_path', null, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/bagemails/');
        $this->bagemails = $this->modx->getService(
            'bagemails',
            'BagEmails',
            $corePath . 'model/bagemails/',
            array(
                'core_path' => $corePath
            )
        );

        parent::initialize();
    }

      /**
    * @return string
    */
    public function getPageTitle()
    {
        return $this->modx->lexicon('bagemails_emails') . ' | BagEmails';
    }

    public function getLanguageTopics() {
        return array('bagemails:default');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $assetsUrl = $this->modx->getOption('bagemails.assets_url',null,$this->modx->getOption('assets_url',null,MODX_ASSETS_URL).'components/bagemails/');
        $jsUrl = $assetsUrl.'js/mgr/';

        $this->addJavascript($mgrUrl.'assets/modext/widgets/core/modx.grid.local.property.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.element.properties.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.tv.template.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.grid.tv.security.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.panel.tv.js');

        $this->addJavascript($jsUrl.'bagemails.js');
        $this->addLastJavascript($jsUrl.'widgets/form/emails.grid.js');
        $this->addLastJavascript($jsUrl.'widgets/form/emails.form.js');
        $this->addLastJavascript($jsUrl.'widgets/form/emails.panel.js');
        $this->addLastJavascript($jsUrl.'form/emails.js');

        $form_id = $_POST["form_id"];
        $response = $this->modx->runProcessor(
            'mgr/emails/getColumns',
            array("form_id" => 1),
            array('processors_path' => MODX_CORE_PATH . 'components/bagemails/processors/')
        );

        $response = json_decode($response->response, true);
        if($response["results"]){
            $dataGrid = $response["results"];
        }else{
            $dataGrid = [];
        }

        $ready = array(
            "xtype" => "bagemails-page-emails",
            "dataGrid" => $dataGrid
        );

        $this->addHtml('<script>
            bagemails.config = '.$this->modx->toJSON($this->bagemails->config).';
            
            Ext.onReady(function() {
                MODx.add(
                    '.$this->modx->toJSON($ready).'
                );
            });
        </script>');
    }
}
