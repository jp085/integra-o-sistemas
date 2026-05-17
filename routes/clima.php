<?php

header('Content-type: application/json');

date_default_timezone_set("America/Sao_Paulo");

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$UrlExplode = explode("/", $_GET['path']);

try {
    
    @$UrlExplode[2];
    $response1 = @file_get_contents("https://brasilapi.com.br/api/cptec/v1/cidade/$UrlExplode[2]");
    $decode1 = json_decode($response1,    true);
    $status = $http_response_header[0];
    
    if(empty($UrlExplode[2])){
        foreach($decode1 as $decode){
            $id = $decode['id'];
            $response2 = file_get_contents("https://brasilapi.com.br/api/cptec/v1/clima/previsao/$id");
            $decode2 = json_decode($response2, true);
            echo json_encode($decode2, JSON_UNESCAPED_UNICODE);
            exit;
      }
    }

    if($response1 !== False){
   
        foreach($decode1 as $decode){

        if(str_contains($status, "503")){
            http_response_code(503);
            $resposta503 = [
                "erro"     => True,
                "codigo"   => "SERVICO_EXTERNO_INDISPONIVEL",
                "mensagem" => "Não foi possível obter dados do serviço externo. Tente novamente em alguns instantes",
                "servico"  => "CPTEC"
            ];
            echo json_encode($resposta503, JSON_UNESCAPED_UNICODE);
            exit;
        }

          elseif(strlen($UrlExplode[2]) >= 2){
            $id = $decode['id'];
            $response2 = file_get_contents("https://brasilapi.com.br/api/cptec/v1/clima/previsao/$id");
            $decode2 = json_decode($response2, true);
            echo json_encode($decode2, JSON_UNESCAPED_UNICODE);
            exit;
        }

        elseif(strlen($UrlExplode[2]) < 2){
        http_response_code(400);
           $resposta400 = [
            "erro"=> true,
            "codigo"=> "NOME_INVALIDO",
            "mensagem"=> "O nome da cidade deve conter pelo menos 2 caracteres",
            "nome_informado"=> $UrlExplode[2]
           ];
           echo json_encode($resposta400);
            exit;

        }   

    };
            
    }

    elseif($response1 == False){
        http_response_code(404);
        if($UrlExplode[2]){
                $resposta404 = [
                "erro" => true,
                "codigo" => "CIDADE_NAO_ENCONTRADA",
                "mensagem" => "Nenhuma cidade encontrada com o nome informado",
                "nome_informado" => $UrlExplode[2]
            ];  
            echo json_encode($resposta404);
            exit;
        }
        else{
        $resposta404 = [
                "erro" => true,
                "codigo" => "CIDADE_NAO_ENCONTRADA",
                "mensagem" => "Nenhuma cidade encontrada com o nome informado",
                "nome_informado" => $UrlExplode[2]
            ];  
            echo json_encode($resposta404);
            exit;
        }
        
    }
    
} catch (Exception $e){
    echo json_encode(['error' => $e->getMessage()]);

}

?>
