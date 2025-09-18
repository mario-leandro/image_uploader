<?php

function headers() {
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
}

function resposta(int $status, array $message) {
    http_response_code($status);
    echo json_encode($message);
    exit;
}