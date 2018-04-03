<?php

define('ROOT', dirname(__DIR__));
define('ROOT_VIEW', ROOT.'/view');

require ROOT.'/vendor/autoload.php';
require ROOT.'/lib.php';
require ROOT.'/lib_api.php';
require ROOT.'/action_api.php';

use DebugBar\StandardDebugBar;

$dotenv = new Dotenv\Dotenv(ROOT);
$dotenv->load();

$config_file = ROOT.'/config.ini';
if (!is_file($config_file) || !is_readable($config_file))
    die("no config.ini");
$config = parse_ini_file($config_file);

if ($_ENV['DEBUG']) {
    $debugbar = new StandardDebugBar();
    $debugbarRenderer = $debugbar->getJavascriptRenderer();
    $debugbar->addCollector(new DebugBar\DataCollector\ConfigCollector($config));

    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

$dotenv->required(['DATABASE_DSN', 'DB_USER', 'DB_PASS'])->notEmpty();
ORM::configure($_ENV['DATABASE_DSN']);
ORM::configure('username', $_ENV['DB_USER']);
ORM::configure('password', $_ENV['DB_PASS']);
if ($_ENV['DEBUG']) {
    $pdo = new DebugBar\DataCollector\PDO\TraceablePDO(ORM::get_db());
    $debugbar->addCollector(new DebugBar\DataCollector\PDO\PDOCollector($pdo));
}

$config_api_file = ROOT.'/config_api.ini';
if (!is_file($config_api_file) || !is_readable($config_api_file))
    die("no config_api.ini");
$config_api = parse_ini_file($config_api_file, true);

$act = _get('act');
if ($act) {
    $func = "_api_action_$act";
    if (!function_exists($func)) {
        echo "function do not exists";
        die();
    }
    $func();
} elseif ($resourse = _get('resourse')) {
    $method = _get('method', "GET");
    _api_do($resourse, $method);
} else {
    echo "Version: "._api_get_config_by_key("core", "version");
}
