<?php

define('ROOT', dirname(__DIR__));
define('ROOT_VIEW', ROOT.'/view');

require ROOT.'/vendor/autoload.php';
require ROOT.'/lib.php';
require ROOT.'/action.php';

$dotenv = new Dotenv\Dotenv(ROOT);
$dotenv->load();

$config_file = ROOT.'/config.ini';
if (!is_file($config_file) || !is_readable($config_file))
    die("no config.ini");
$config = parse_ini_file($config_file);

if ($_ENV['DEBUG']) {
    error_reporting(E_ALL);
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

$dotenv->required(['DATABASE_DSN', 'DB_USER', 'DB_PASS'])->notEmpty();
ORM::configure($_ENV['DATABASE_DSN']);
ORM::configure('username', $_ENV['DB_USER']);
ORM::configure('password', $_ENV['DB_PASS']);

$router = new \Bramus\Router\Router();
$router->get('/', 'action_index');
$router->get('/hello/(\w+)', 'action_hello');
$router->get('/db', 'action_db');
$router->get('/error/example', 'action_error_example');
$router->get('/full', 'action_full');

$router->run();