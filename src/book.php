<?php

header('Content-Type: application/json');

$isbn = $_GET['isbn'] ?? '';

if (!$isbn) {
    echo json_encode([
        'error' => 'ISBN not found'
    ]);
    exit;
}

$url = "https://api.openbd.jp/v1/get?isbn=" . urlencode($isbn);

echo file_get_contents($url);
