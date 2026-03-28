<?php
session_start();

require_once './core/Env.php';

Env::load(__DIR__ . '/.env');

foreach (glob(__DIR__ . '/configs/*.php') as $file) {
    require_once $file;
}

foreach (glob(__DIR__ . '/core/*.php') as $file) {
    require_once $file;
}
foreach (glob(__DIR__ . '/app/helpers/*.php') as $file) {
    require_once $file;
}
foreach (glob(__DIR__ . '/app/models/*.php') as $file) {
    require_once $file;
}
foreach (glob(__DIR__ . '/app/middleware/*.php') as $file) {
    require_once $file;
}
foreach (glob(__DIR__ . '/app/controllers/*.php') as $file) {
    require_once $file;
}
foreach (glob(__DIR__ . '/app/services/*.php') as $file) {
    require_once $file;
}

$routes = new Router();

foreach (glob(__DIR__ . '/routes/*.php') as $file) {
    require_once $file;
}


$requestUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$methodRes = $_SERVER['REQUEST_METHOD'];

$routes->xulyPath($methodRes, $requestUrl);
