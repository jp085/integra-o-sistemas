<?php
header('Content-type: application/json');
date_default_timezone_set("America/Sao_Paulo");

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$UrlExplode = explode("/", $_GET['path']);
try {
    
    $UrlExplode[1];
    $response1 = @file_get_contents("https://brasilapi.com.br/api/cptec/v1/cidade/$UrlExplode[1]");
    $decode1 = json_decode($response1,    true);
    if($response1 == True){
        foreach($decode1 as $decode){
       
       /* if(strtolower($decode['nome']) == strtolower($UrlExplode[1])){
            $id = $decode['id'];
            $response2 = file_get_contents("https://brasilapi.com.br/api/cptec/v1/clima/previsao/$id");
            $decode2 = json_decode($response2, true);
            echo json_encode($decode2,JSON_PRETTY_PRINT);
            
        }*/

          if(strlen($UrlExplode[1]) >= 2){
            $id = $decode['id'];
            $response2 = file_get_contents("https://brasilapi.com.br/api/cptec/v1/clima/previsao/$id");
            $decode2 = json_decode($response2, true);
            echo json_encode($decode2,  JSON_PRETTY_PRINT);
            break;
        }
        elseif(strlen($UrlExplode[1]) < 2){
           $resposta400 = [
            "erro"=> true,
            "codigo"=> "NOME_INVALIDO",
            "mensagem"=> "O nome da cidade deve conter pelo menos 2 caracteres",
            "nome_informado"=> $UrlExplode[1]
           ];
           echo json_encode($resposta400);
            break;

        }   

    };
            
    }
    elseif($response1 == False){
        $resposta404 = [
                "erro" => true,
                "codigo" => "CIDADE_NAO_ENCONTRADA",
                "mensagem" => "Nenhuma cidade encontrada com o nome informado",
                "nome_informado" => $UrlExplode[1]
            ];
            echo json_encode($resposta404);
    }


    
} catch (PDOException $e){
    echo json_encode(['error' => $e->getMessage()]);

}

?>
