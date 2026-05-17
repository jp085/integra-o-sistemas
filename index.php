<?php

header('Content-type: application/json');

date_default_timezone_set("America/Sao_Paulo");

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$UrlExplode = explode("/", $_GET['path']);

switch($UrlExplode[1]){

    case 'clima':
        require 'routes/clima.php';
        break;

    case 'cidades':
        require 'routes/cidades.php';
        break;

    case 'health':
        require 'routes/health.php';
        break;
    
}


?>
