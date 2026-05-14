<?php
header('Content-type: application/json');
date_default_timezone_set("America/Sao_Paulo");

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$UrlExplode = explode("/", $_GET['path']);
try {
    
    $UrlExplode[1];
    $response1 = file_get_contents("https://brasilapi.com.br/api/cptec/v1/cidade/$UrlExplode[1]");
    $decode1 = json_decode($response1,    true);
    foreach($decode1 as $decode){
        /*echo strtolower($valor2['nome']);
        echo " ";
        echo $UrlExplode[1];"*/
        if(strtolower($decode['nome']) == strtolower($UrlExplode[1])){
            $id = $decode['id'];
            $response2 = file_get_contents("https://brasilapi.com.br/api/cptec/v1/clima/previsao/$id");
            $decode2 = json_decode($response2, true);
            var_dump($decode2);
        }
    }
    
} catch (PDOException $e){
    echo json_encode(['error' => $e->getMessage()]);

}

?>
