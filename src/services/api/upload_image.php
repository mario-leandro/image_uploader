<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/common.php';
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
    $pastaUploads = __DIR__ . "/../../assets/imagens_salvas/";

    $nomeArquivo = $_FILES["imagem"]["name"];
    $caminhoDestino = $pastaUploads . $nomeArquivo;

    $infoArquivo = finfo_open(FILEINFO_MIME_TYPE);
    $tipoMime = finfo_file($infoArquivo, $_FILES['imagem']['tmp_name']);
    finfo_close($infoArquivo);

    $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];

    if (!in_array($tipoMime, $tiposPermitidos)) {
        resposta(400, ["success" => false, "error" => "Arquivo não é uma imagem válida"]);
    }

    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminhoDestino)) {
        $urlArquivo = "src/assets/imagens_salvas/" . $nomeArquivo;
        
        $db_connection = getConnectionDB();

        $sql = $db_connection->prepare(
            "INSERT INTO uploads (nome_arquivo, caminho_arquivo) VALUES (:nome, :caminho)"
        );

        $dadosArquivo = [
            ":nome"   => $nomeArquivo,
            ":caminho" => $urlArquivo
        ];

        $sql->execute($dadosArquivo);

        resposta(201, ["success" => true, "url" => $urlArquivo]);
    } else {
        resposta(500, ["success" => false, "error" => "Falha ao salvar arquivo"]);
    }
} else {
    resposta(400, ["success" => false, "error" => "Nenhum arquivo enviado"]);
}
