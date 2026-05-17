
<?php

header('Content-type: application/json');

date_default_timezone_set("America/Sao_Paulo");

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$UrlExplode = explode("/", $_GET['path']);
$limite = $_GET['limite'] ?? '10';

try {
    
    @$UrlExplode[2];
    $response1 = @file_get_contents("https://brasilapi.com.br/api/ibge/municipios/v1/$UrlExplode[2]");
    $decode1 = json_decode($response1,    true);
    $status = $http_response_header[0];
    
    if($response1 == True){
    
   
        foreach($decode1 as $decode){

        if(str_contains($status, "503")){
            $resposta503 = [
                "erro"     => True,
                "codigo"   => "SERVICO_EXTERNO_INDISPONIVEL",
                "mensagem" => "Não foi possível obter dados do serviço externo. Tente novamente em alguns instantes",
                "servico"  => "CPTEC"
            ];
            echo json_encode($resposta503, JSON_PRETTY_PRINT);
            break;
        }

          elseif(strlen($UrlExplode[2]) == 2){
            $decode2 = json_decode($response1, true);
            $decode2arraylimit = array_slice($decode2, 0, $limite);
            echo json_encode($decode2arraylimit, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        }

        elseif(strlen($UrlExplode[2]) < 2){
           $resposta400 = [
            "erro"=> true,
            "codigo"=> "SIGLA_UF_INVALIDA",
            "mensagem"=> "A sigla do estado deve conter exatamente 2 letras",
            "sigla_uf_informada"=> $UrlExplode[2]
           ];
           echo json_encode($resposta400);
            break;

        }   

    };
            
    }

    elseif($response1 == False){
        if($UrlExplode[2]){
                $resposta404 = [
                "erro" => true,
                "codigo" => "UF_NAO_ENCONTRADA",
                "mensagem" => "Estado com a sigla informada não foi encontrado",
                "sigla_uf_informada" => $UrlExplode[2]
            ];  
            echo json_encode($resposta404);
        }
        else{
        $resposta404 = [
                "erro" => true,
                "codigo" => "UF_NAO_ENCONTRADA",
                "mensagem" => "Estado com a sigla informada não foi encontrado",
                "sigla_uf_informada" => $UrlExplode[2]
            ];  
            echo json_encode($resposta404);
        }
        
    }
    
} catch (PDOException $e){
    echo json_encode(['error' => $e->getMessage()]);

}

?>
