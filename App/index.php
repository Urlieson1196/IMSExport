<?php

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

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}


//require_once $_SERVER['DOCUMENT_ROOT'] . '/webservices/conexionBD.php';
//require_once $_SERVER['DOCUMENT_ROOT'] . '/paths.php';

require_once __DIR__ . '/vendor/autoload.php';

use IMSExport\Application\Entities\Group;
use IMSExport\Application\IMS\Exporter\Cartridge;
use IMSExport\Core\Router\Teeny;

$app = new Teeny;

$app->action('get', '/xml', function () {
    $export = new \IMSExport\Application\ExportIMS\Handlers\ExportIMS('id', ['seedId' => '51250023_3_VIRTUAL_1']);
});

$app->action('get', '/group/<groupId:num>', function ($params) {
    $group = new Group($params['groupId']);
});

/*
 *
 * <root message="hola mundo">
 *  <children1>Hola mundo 2</children1>
 *  <children2>
 *      <children3 attr="hola">texto</children3>
 *  </children2>
 * </root>
 *
 *
 * */

return $app->exec();
