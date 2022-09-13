<?php
error_reporting(E_ERROR | E_PARSE);

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    }
    // may also be using PUT, PATCH, HEAD etc

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }

    exit(0);
}


//require_once $_SERVER['DOCUMENT_ROOT'] . '/webservices/conexionBD.php';
//require_once $_SERVER['DOCUMENT_ROOT'] . '/paths.php';

require_once __DIR__ . '/vendor/autoload.php';

use IMSExport\Application\ExportIMS\Handlers\ExportIMS;
use IMSExport\Core\Router\Teeny;

$app = new Teeny;

$app->action('post', '/export', function ($params, $body) {
    $seedId= $body['payload'];
    $export = new ExportIMS('id', compact('seedId'));
    $export->run();
});

$app->action('get', '/test', function () {
    $export = new ExportIMS('id', ['seedId' => '51250023_3_VIRTUAL_1']);
    $export->run();
});

return $app->exec();
