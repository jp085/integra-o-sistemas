<?php

header('Content-type: application/json');

date_default_timezone_set("America/Sao_Paulo");

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if(!isset($_GET['path'])){
    $_GET['path'] = '';
} 

$UrlExplode = explode("/", $_GET['path']);


if(!isset($UrlExplode[1])){
    $UrlExplode[1] = '';
} 

switch($UrlExplode[1]){
    case '':
        echo json_encode([
            "api"       => "online",
            "version"   => "1.0",
            "endpoints" => [
                "/clima",
                "/cidades",
                "/health"
            ]
        ]);
        exit;
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
