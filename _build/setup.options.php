<?php


$exists = false;
$output = null;

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        $exists = $modx->getCount('modSystemSetting', array('key:LIKE' => 'bagemails.%'));
        break;
}

if ($exists) {
    return;
}

return $output;
