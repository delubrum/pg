


<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

header("Content-Security-Policy: script-src 'unsafe-eval' 'unsafe-inline' *;");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("X-Permitted-Cross-Domain-Policies: none");
header("Referrer-Policy: no-referrer");
header("Feature-Policy: geolocation 'self';");
// header("Expect-CT: max-age=86400, enforce, report-uri=\"https://example.com/ct-report\"");
header("Permissions-Policy: autoplay=(self), camera=(), microphone=()");

date_default_timezone_set('America/Bogota');
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createUnsafeImmutable('./'); $dotenv->load();
require_once 'config/db.php';
$controller = 'Home';
if(!isset($_REQUEST['c']))
{
    require_once "app/controllers/$controller" . "Controller.php";
    $controller = $controller . 'Controller';
    $controller = new $controller;
    $controller->Index();
}
else
{
    $controller = $_REQUEST['c'];
    $accion = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'Index';
    require_once "app/controllers/$controller" . "Controller.php";
    $controller = $controller . 'Controller';
    $controller = new $controller;
    if(is_callable(array( $controller, $accion ) )){
        call_user_func( array( $controller, $accion ) );
    }else{
        header("Location: 404.html");
    }
}