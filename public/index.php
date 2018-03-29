<?php

define('ROOT', dirname(__DIR__));

require ROOT.'/vendor/autoload.php';
require ROOT.'/action.php';

use DebugBar\StandardDebugBar;

$dotenv = new Dotenv\Dotenv(ROOT);
$dotenv->load();

$config_file = ROOT.'/config.ini';
if (!is_file($config_file) || !is_readable($config_file))
    die("no config.ini");
$config = parse_ini_file($config_file);

if ($_ENV['DEBUG']) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();

    $debugbar = new StandardDebugBar();
    $debugbarRenderer = $debugbar->getJavascriptRenderer();

}

$dotenv->required(['DATABASE_DSN', 'DB_USER', 'DB_PASS'])->notEmpty();
ORM::configure($_ENV['DATABASE_DSN']);
ORM::configure('username', $_ENV['DB_USER']);
ORM::configure('password', $_ENV['DB_PASS']);

$router = new Router();
$router
->get('/', 'action_index')
->get('/hello/:name', 'action_hello')
->get('/v', 'action_html_file')
->get('/db', 'action_db')
->get('/error/example', 'action_error_example')
->execute();