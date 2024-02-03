<?php

/** @noinspection PhpIncludeInspection */

require_once dirname(__FILE__, 4) . '/config.core.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';

/** @var modX $modx */
/** @var bagemails $bagemails */
$bagemails = $modx->getService('bagemails');
$modx->lexicon->load('bagemails:default');

$path = $modx->getOption('processorsPath', $bagemails->config, MODX_CORE_PATH . 'components/bagemails/processors/');
/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));
