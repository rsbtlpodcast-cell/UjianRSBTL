<?php
session_start();
require 'db.php';

$formasiList = ['Dokter','Rekam Medis','Radiologi','Perawat','ATLM'];
$login = [];
$selesai = [];

foreach($formasiList as $f){
    // Jumlah login
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM hasil_ujian WHERE formasi=?");
    $stmt->execute([$f]);
    $login[] = (int)$stmt->fetchColumn();

    // Jumlah selesai
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM hasil_ujian WHERE formasi=? AND status='selesai'");
    $stmt->execute([$f]);
    $selesai[] = (int)$stmt->fetchColumn();
}

echo json_encode([
    'labels'=>$formasiList,
    'login'=>$login,
    'selesai'=>$selesai
]);
