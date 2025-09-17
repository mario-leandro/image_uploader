<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/commun.php';

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
    $uploadDir = __DIR__ . "/../../assets/imagens_salvas/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = uniqid() . "-" . basename($_FILES["imagem"]["name"]);
    $targetFile = $uploadDir . $filename;

    // Valida se é imagem
    $check = getimagesize($_FILES["imagem"]["tmp_name"]);
    if ($check === false) {
        json_response(400, ["success" => false, "error" => "Arquivo não é uma imagem"]);
        exit;
    }

    // Move arquivo
    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $targetFile)) {
        $url = "src/assets/imagens_salvas/" . $filename;

        // Insere no banco
        $db = getConnectionDB();

        $stmt = $db->prepare("INSERT INTO uploads (filename, filepath) VALUES (:filename, :filepath)");
        $arr_img = [
            ":filename" => $filename,
            ":filepath" => $url
        ];

        $stmt->execute($arr_img);

        json_response(201, ["success" => true, "url" => $url]);
    } else {
        json_response(500, ["success" => false, "error" => "Falha ao salvar arquivo"]);
    }
} else {
    json_response(400, ["success" => false, "error" => "Nenhum arquivo enviado"]);
}