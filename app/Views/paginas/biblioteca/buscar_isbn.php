<?php
// Exemplo simples de ponte em PHP
header('Content-Type: application/json');

$isbn = preg_replace('/\D/', '', $_GET['isbn'] ?? '');

if (strlen($isbn) < 10) {
    echo json_encode(['error' => 'ISBN inválido']);
    exit;
}

$res = ['titulo' => '', 'autor' => '', 'data' => '', 'capa' => ''];

// Tenta Google Books via PHP (evita erro 503 do navegador)
$url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" . $isbn;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (isset($data['items'][0]['volumeInfo'])) {
    $info = $data['items'][0]['volumeInfo'];
    $res['titulo'] = $info['title'] ?? '';
    $res['autor'] = isset($info['authors']) ? implode(', ', $info['authors']) : '';
    $res['data'] = $info['publishedDate'] ?? '';

    if (isset($info['imageLinks']['thumbnail'])) {
        // Substitui por HTTPS e remove bordas
        $res['capa'] = str_replace(['http:', '&edge=curl'], ['https:', ''], $info['imageLinks']['thumbnail']);
    }
}

echo json_encode($res);
