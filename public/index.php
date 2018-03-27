<?php

define('ROOT', dirname(__DIR__));

require ROOT.'/vendor/autoload.php';
require ROOT.'/action.php';

$dotenv = new Dotenv\Dotenv(ROOT);
$dotenv->load();

$config_file = ROOT.'/config.ini';
if (!is_file($config_file) || !is_readable($config_file))
    die("no config.ini");
$config = parse_ini_file($config_file);

$router = new Router();
$router
->get('/', 'action_index')
->get('/hello/:name', 'action_hello')
->get('/v', 'action_html_file')
->execute();