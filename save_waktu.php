<?php
session_start();
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if(!isset($_SESSION['user'])) {
    echo json_encode(['status'=>'error']);
    exit;
}

$waktu = intval($data['waktu'] ?? 0);
$_SESSION['ujian_waktu'] = $waktu;

echo json_encode(['status'=>'ok']);
