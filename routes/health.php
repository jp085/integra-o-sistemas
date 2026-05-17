<?php

header('Content-Type: application/json; charset=UTF-8');

date_default_timezone_set("America/Sao_Paulo");

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$now = new DateTime();

$servico_externo_ok = true;

if ($path !== "/api/v1/health") {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "mensagem" => "Endpoint não encontrado"
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if ($servico_externo_ok) {

    $response = [
        "status" => "healthy",
        "versao" => "1.0.0",
        "timestamp" => $now->format("c")
    ];

} else {

    $response = [
        "status" => "degraded",
        "versao" => "1.0.0",
        "timestamp" => $now->format("c"),
        "motivo" => "Serviço externo indisponível"
    ];
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
