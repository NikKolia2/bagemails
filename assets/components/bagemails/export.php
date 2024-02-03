<?php
define('MODX_API_MODE', true);
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';

$modx->getService('error', 'error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');


/** @var BagEails $bagemails */
$bagemails = $modx->getService('bagemails', 'BagEmails', $modx->getOption('bagemails.core_path', null, $modx->getOption('core_path') . 'components/bagemails/') . 'model/bagemails/', array());

if (isset($_GET['token']) && isset($_GET['form_id'])) {
    if ($bagemails->checkExportToken($_GET['token'])) { 
        $_GET["date_start"] = empty($_GET['date_start'])? '': $_GET['date_start'];  
        $_GET["date_end"] = empty($_GET['date_end'])? '': $_GET['date_end']; 

        echo $bagemails->export($_GET['form_id'], $_GET["date_start"], $_GET["date_end"]);     
    } else {
        echo 'Error incorrect token';
    }
} else {
    echo 'Error incorrect option';
}

@session_write_close();