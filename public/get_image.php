<?php
// Localização: /public/get_image.php

if (empty($_GET['path'])) {
    header("HTTP/1.1 400 Bad Request");
    exit;
}

$relativePath = $_GET['path'];

// Define a raiz dos uploads subindo um nível a partir de /public
$basePath = realpath(__DIR__ . '/assets/uploads/');

if (!$basePath) {
    header("HTTP/1.1 500 Internal Server Error");
    die("Erro: Pasta de uploads não encontrada no servidor.");
}

// Monta o caminho final
$targetFile = realpath($basePath . '/' . $relativePath);

// SEGURANÇA: Garante que o arquivo está DENTRO da pasta de uploads (evita ../../)
if ($targetFile === false || strpos($targetFile, $basePath) !== 0) {
    header("HTTP/1.1 403 Forbidden");
    die("Acesso negado.");
}

if (!file_exists($targetFile)) {
    header("HTTP/1.1 404 Not Found");
    die("Arquivo físico não existe: " . $relativePath);
}

// Detecta o tipo da imagem
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $targetFile);
finfo_close($finfo);

// Cabeçalhos para o Fabric.js e navegador
header("Access-Control-Allow-Origin: *");
header("Content-Type: " . $mimeType);
header("Content-Length: " . filesize($targetFile));

readfile($targetFile);
exit;
