<?php

include __DIR__ . '/../api/common.php';

function getConnectionDB() {
    try {
        $db = new PDO( 
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4;port=" . DB_PORT,
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        return $db;
    } catch(PDOException $e) {
        resposta(500, ["success" => false, "message" => "Erro ao conectar ao banco: " . $e->getMessage()]);
    }
}
