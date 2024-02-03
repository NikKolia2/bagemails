<?php

/**
 * The base class for BagEmails.
 *
 * @package bagemails
 */
class BagEmails
{
    /** @var \modX $modx */
    public $modx;
    public $namespace = 'bagemails';
    /** @var array $config */
    public $config = array();
    /** @var array $chunks */
    public $chunks = array();
    protected $prefix;
    
    function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;
      
        $corePath = $this->modx->getOption('bagemails.core_path', $config, MODX_CORE_PATH . 'components/bagemails/');
        $assetsUrl = $this->modx->getOption(
            'bagemails.assets_path',
            $config,
            MODX_ASSETS_PATH . 'components/bagemails/'
        );
        
        $assetsUrl = $this->modx->getOption('bagemails.assets_url', $config, MODX_ASSETS_URL . 'components/bagemails/');
        $actionUrl = $this->modx->getOption('bagemails.action_url', $config, $assetsUrl . 'action.php');
        $connectorUrl = $assetsUrl . 'connector.php';
        
        $this->config = array_merge(array(
            'assets_url' => $assetsUrl,
            'core_path' => $corePath,
            'token' => $this->getToken(),
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connector_url' => $connectorUrl,
            'connectorUrl' => $connectorUrl,
            'actionUrl' => $actionUrl,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'chunkSuffix' => '.chunk.tpl',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'exportUrl' => $assetsUrl . 'export.php'
        ), $config);
        
        $this->modx->addPackage('bagemails', $this->config['modelPath']);
        $this->modx->lexicon->load('bagemails:default');

    }

    /**
     * @param string $token
     * @return bool
     */
    public function checkExportToken($token)
    {
        return $token == $this->modx->getOption('bagemails.token', null, '') ? true : false;
    }

    public function export($form_id, $date_start = '', $date_end = ''){
        $data = json_decode($this->modx->runProcessor(
            'mgr/emails/getlist',
            array(
                "form_id" => $form_id,
                "date_start" => $date_start,
                "date_end" => $date_end
            ),
            array('processors_path' => MODX_CORE_PATH . 'components/bagemails/processors/')
        )->response, true)["results"];

        $fieldsRows = $this->modx->getCollection("bagEmailsFormFields", ["form_id" => $form_id]);
        $fields = ["id" => "id", "createdon" => "Дата"];
        foreach($fieldsRows as $row){
            $fields[$row->get("key")] = $row->get("name");
        }

       
        $data = array_merge([$fields], $data);
        file_put_contents($_SERVER["DOCUMENT_ROOT"]."/test.php", print_r($date_start, 1));
        require_once dirname(__FILE__) . '/src/PHPExcel-1.8/Classes/PHPExcel.php';
        require_once dirname(__FILE__) . '/src/PHPExcel-1.8/Classes/PHPExcel/Writer/Excel5.php';

        // Создание объекта класса PHPExcel
        $myXls = new PHPExcel();
        // Указание на активный лист
        $myXls->setActiveSheetIndex(0);
        // Получение активного листа
        $mySheet = $myXls->getActiveSheet();
        // Указание названия листа книги
        $mySheet->setTitle("Письма");
        
        
        foreach($data as $i => $row){
            $row = array_values($row);
            for($j = 0; $j < count($row); $j++){
                $mySheet->setCellValueByColumnAndRow($j, $i+1, $row[$j]);
            }
        }

       // Выводим HTTP-заголовки
        header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
        header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
        header ( "Cache-Control: no-cache, must-revalidate" );
        header ( "Pragma: no-cache" );
        header ( "Content-type: application/vnd.ms-excel" );
        header ( "Content-Disposition: attachment; filename=matrix.xls" );

        // Вывод файла
        $objWriter = new PHPExcel_Writer_Excel5($myXls);
        $objWriter->save('php://output');
    }

    /**
     * @param bool $save
     * @return string
     */
    public function getToken()
    {
        $token = $this->modx->getOption('bagemails.token', null, '');
        if (empty($token)) {
            $token = $this->generateExportToken();
        }
        return $token;
    }

     /**
     * @param bool $save
     * @return string
     */
    public function generateExportToken($save = true)
    {
        $token = md5(MODX_HTTP_HOST . time() . microtime(true) . $this->modx->user->generatePassword());
        if ($save) {
            $this->setOption('bagemails.token', $token);
        }
        return $token;
    }

       /**
     * @param string $key
     * @param string $value
     * @param bool $clearCache
     * @return bool
     */
    public function setOption($key, $value, $clearCache = true)
    {
        if (!$setting = $this->modx->getObject('modSystemSetting', $key)) {
            $setting = $this->modx->newObject('modSystemSetting');
            $setting->set('namespace', $this->namespace);
        }
        $setting->set('value', $value);
        if ($setting->save()) {
            $this->modx->config[$key] = $value;
            if ($clearCache) {
                $this->modx->cacheManager->refresh(array('system_settings' => array()));
            }
            return true;
        }
        return false;
    }

}