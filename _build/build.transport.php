<?php
/**
 * Copyright © Ivan Klimchuk - All Rights Reserved
 * Unauthorized copying, changing, distributing this file, via any medium, is strictly prohibited.
 * Written by Ivan Klimchuk <ivan@klimchuk.com>, 2019
 */

set_time_limit(0);
error_reporting(E_ALL | E_STRICT); ini_set('display_errors',true);

ini_set('date.timezone', 'Europe/Minsk');

// здесь мы задаем базовые параметры пакета, его название, версию и прочее. Так же зависимости от версии PHP и MODX
define('PKG_NAME', 'bagemails');
define('PKG_NAME_LOWER', strtolower(PKG_NAME));
define('PKG_VERSION', '0.1.21');
define('PKG_RELEASE', 'pl');
define('PKG_SUPPORTS_PHP', '5.4');
define('PKG_SUPPORTS_MODX', '2.6.5');

// затем подключаем класс xPDO из папки, в которую composer загрузил весь modx revolution, включая xpdo.
require_once __DIR__ . '/xpdo/xpdo/xpdo.class.php';

// Может удивить, но факт. xPDO требует указать параметры подключения к БД, но на деле, пока запросы к БД не выполняются, он этот коннект не будет подымать, поэтому мы можем использовать любые данные для подключения.
/* instantiate xpdo instance */
$xpdo = new xPDO('mysql:host=localhost;dbname=modx;charset=utf8', 'root', '',
    [xPDO::OPT_TABLE_PREFIX => 'modx_', xPDO::OPT_CACHE_PATH => __DIR__ . '/../../../core/cache/'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]
);
$cacheManager= $xpdo->getCacheManager();
$xpdo->setLogLevel(xPDO::LOG_LEVEL_INFO);
$xpdo->setLogTarget();

// эта переменная хранит префикс пути, куда складывать элементы на сайте. В частности, это папка самого компонента
$target = sprintf('components/%s/', PKG_NAME_LOWER);

// здесь описаны пути к основным элементах для сборки пакета
$root = dirname(__DIR__) . '/';
$sources = [
    'build' => $root . '_build/',
    'data' => $root . '_build/data/',
    'docs' => $root . 'docs/',
    'resolvers' => $root . '_build/resolvers/',
    'validators' => $root . '_build/validators/',
    'implants' => $root . '_build/implants/',
    'elements' => $target . 'elements/',
];

$signature = implode('-', [PKG_NAME, PKG_VERSION, PKG_RELEASE]);

// данный участок отвечает за сборку релиза или обычную. 
// Если вызвать команду `php _build/build.transport.php release`, тогда пакет будет собран в папке _packages, иначе в папке core/packages установленного MODX сайта.
$release = false;
if (!empty($argv) && $argc > 1) {
    $release = $argv[1];
}

$directory = $release === 'release' ? $root . '_packages/' : __DIR__ . '/../../../core/packages/';
$filename = $directory . $signature . '.transport.zip';
echo $filename;

/* remove the package if it's already been made */
if (file_exists($filename)) {
    unlink($filename);
}
if (file_exists($directory . $signature) && is_dir($directory . $signature)) {
    $cacheManager = $xpdo->getCacheManager();
    if ($cacheManager) {
        $cacheManager->deleteTree($directory . $signature, true, false, []);
    }
}

// таким нетривиальным способом мы загружаем необходимые для работы классы
// увы, нормальный PSR-4 есть только в отрефакторенной версии MODX 3, в 2.x все по старинке, вот так.
$xpdo->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);
$xpdo->loadClass('transport.xPDOVehicle', XPDO_CORE_PATH, true, true);
$xpdo->loadClass('transport.xPDOObjectVehicle', XPDO_CORE_PATH, true, true);
$xpdo->loadClass('transport.xPDOFileVehicle', XPDO_CORE_PATH, true, true);
$xpdo->loadClass('transport.xPDOScriptVehicle', XPDO_CORE_PATH, true, true);

// создаем объект транспортного пакета
$package = new xPDOTransport($xpdo, $signature, $directory);

// загружаем классы MODX, необходимые для работы.
$xpdo->setPackage('modx', __DIR__ . '/model/');
$xpdo->loadClass(modAccess::class);
$xpdo->loadClass(modAccessibleObject::class);
$xpdo->loadClass(modAccessibleSimpleObject::class);
$xpdo->loadClass(modPrincipal::class);
$xpdo->loadClass(modElement::class);
$xpdo->loadClass(modScript::class);


// создаем пространство имен, куда поместим наш пакет
$namespace = $xpdo->newObject(modNamespace::class);
$namespace->fromArray([
    'path' => '{core_path}components/' . PKG_NAME_LOWER . '/',
    'assets_path' => '{assets_path}components/' . PKG_NAME_LOWER . '/',
]);
$namespace->set('name', PKG_NAME_LOWER);

// и кладем его в пакет вот так
// все что в массиве, это конфигурация этого элемента в пакете, как он должен вести себя при установке или обновлении. Подробно разбирать не буду, это тема отдельной заметки.
$package->put($namespace, [
    xPDOTransport::UNIQUE_KEY => 'name',
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::NATIVE_KEY => PKG_NAME_LOWER,
    'namespace' => PKG_NAME_LOWER
]);

// создаем базовую категорию
$category = $xpdo->newObject(modCategory::class);
$category->fromArray(['id' => 1, 'category' => "bagemails", 'parent' => 0]);


// а это упаковка Меню
$menu = include __DIR__ . '/data/menu.php';
if (is_array($menu)) {
    foreach ($menu as $item) {
        $package->put($item, [
            xPDOTransport::PRESERVE_KEYS => true,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'text',
            xPDOTransport::RELATED_OBJECTS => true,
        ]);
    }
}

// регистрируем валидаторы
$validators = [
    ['type' => 'php', 'source' => $sources['validators'] . 'validate.phpversion.php'],
];


// и резолверы. Это файловые резолверы, они указывают, куда каки папки будут скопированы после установки пакета.
$resolvers = [
    ['type' => 'file', 'source' => $root . 'assets/' . $target, 'target' => sprintf("return MODX_ASSETS_PATH . '%s';", dirname($target))],
    ['type' => 'file', 'source' => $root . 'core/' . $target, 'target' => sprintf("return MODX_CORE_PATH . '%s/';", dirname($target))],
    ['type' => 'php', 'source' => $sources['resolvers'] . 'resolve.extension.php'],
    ['type' => 'php', 'source' => $sources['resolvers'] . 'resolve.settings.php']
];

$package->put($category, [
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false, // обратите внимание, это говорит, что ключи будут созданы MODX автоматически
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::ABORT_INSTALL_ON_VEHICLE_FAIL => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::NATIVE_KEY => true,
    'package' => 'modx',
    'resolve' => $resolvers,
    'validate' => $validators
]);

// устанавливаем необходимые атрибуты пакета - это файл изменений, файл с документацией и лицензией.
// эти файлы в папке docs в корне пакета, потому что так удобнее, как я и говорил.
//$package->setAttribute('changelog', file_get_contents($sources['docs'] . 'changelog.txt'));
$package->setAttribute('license', file_get_contents($sources['docs'] . 'license.txt'));
$package->setAttribute('readme', file_get_contents($sources['docs'] . 'readme.txt'));
$package->setAttribute('requires', [
    'php' => '>=' . PKG_SUPPORTS_PHP,
    'modx' => '>=' . PKG_SUPPORTS_MODX
]);

// и наконец, пакуем пакет
if ($package->pack()) {
    $xpdo->log(xPDO::LOG_LEVEL_INFO, 'Package built');
}