
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
    $status = http_response_code();

    if(empty($UrlExplode[2])){
            http_response_code(400);
           $resposta400 = [
            "erro"=> true,
            "codigo"=> "SIGLA_UF_INVALIDA",
            "mensagem"=> "A sigla do estado deve conter exatamente 2 letras",
            "sigla_uf_informada"=> null
           ];
           echo json_encode($resposta400);
            exit;

        } 

    elseif(strlen($UrlExplode[2]) < 2 || strlen($UrlExplode[2]) > 2){
            http_response_code(400);
           $resposta400 = [
            "erro"=> true,
            "codigo"=> "SIGLA_UF_INVALIDA",
            "mensagem"=> "A sigla do estado deve conter exatamente 2 letras",
            "sigla_uf_informada"=> $UrlExplode[2]
           ];
           echo json_encode($resposta400);
            exit;

    }

    elseif($response1 !== False){
    
   
        foreach($decode1 as $decode){

        if(str_contains($status, "503")){
            http_response_code(503);
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
            if ($limite > 100){
                $limite = 100;
            }

            http_response_code(200);
            $now = new datetime();

            $time = array("Consultado em: " => $now-> format('Y-m-d H:i:s'));

            $uf = $UrlExplode[2];
            $qtd = array("Quantidade retornada: " => $limite);
            $decode1 = json_decode($response1, true);
            $cidades = [];

            foreach($decode1 as $decode){
                $cidades[] = [
                "nome" => $decode["nome"]
                ];
            }
            $cidades = array_slice($cidades,0,$limite);

            $resposta = 
            ["uf" => $uf,
             "Quantidade retornada" => $limite,
             "cidades" => $cidades,
             "consultado em" => $time   
            ];

            echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        }
  

    };
            
    }

    elseif($response1 == False){
        if(empty($UrlExplode[2])){
            http_response_code(404);
                $resposta404 = [
                "erro" => true,
                "codigo" => "UF_NAO_ENCONTRADA",
                "mensagem" => "Estado com a sigla informada não foi encontrado",
                "sigla_uf_informada" => null
            ];  
            echo json_encode($resposta404);
        }
        elseif(strlen($UrlExplode[2]) == 2){
            http_response_code(404);
        $resposta404 = [
                "erro" => true,
                "codigo" => "UF_NAO_ENCONTRADA",
                "mensagem" => "Estado com a sigla informada não foi encontrado",
                "sigla_uf_informada" => $UrlExplode[2]
            ];  
            echo json_encode($resposta404);
        }
        
    }
    
} catch (Exception $e){
    echo json_encode(['error' => $e->getMessage()]);

}

?>
