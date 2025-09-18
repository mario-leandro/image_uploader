<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/common.php';

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
    $uploadDir = __DIR__ . "/../../assets/imagens_salvas/";

    $filename = uniqid() . "-" . basename($_FILES["imagem"]["name"]);
    $targetFile = $uploadDir . $filename;

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['imagem']['tmp_name']);
    finfo_close($finfo);

    $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($mimeType, $tiposPermitidos)) {
        resposta(400, ["success" => false, "error" => "Arquivo não é uma imagem válida"]);
    }

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

        resposta(201, ["success" => true, "url" => $url]);
    } else {
        resposta(500, ["success" => false, "error" => "Falha ao salvar arquivo"]);
    }
} else {
    resposta(400, ["success" => false, "error" => "Nenhum arquivo enviado"]);
}