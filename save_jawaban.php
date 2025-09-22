<?php
session_start();
require 'db.php';
$user_id = $_SESSION['user']['id'] ?? null;
if(!$user_id) exit;

$data = json_decode(file_get_contents('php://input'), true);
if(!$data) exit;

$_SESSION['ujian_jawaban'] = array_merge($_SESSION['ujian_jawaban'] ?? [], $data);

// Simpan ke DB jika mau real-time (opsional)
foreach($data as $soal_id => $jawaban){
    $stmt = $pdo->prepare("REPLACE INTO jawaban_ujian (user_id, soal_id, jawaban) VALUES (?,?,?)");
    $stmt->execute([$user_id,$soal_id,$jawaban]);
}
echo json_encode(['success'=>true]);
