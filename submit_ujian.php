<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];
$formasi = $user['formasi'] ?? 'Dokter';

// Ambil jawaban peserta
$jawaban_user = $_POST['jawaban'] ?? [];
if(empty($jawaban_user)) die("Tidak ada jawaban.");

// Cek jika sudah submit
$stmt = $pdo->prepare("SELECT * FROM hasil_ujian WHERE user_id=?");
$stmt->execute([$user_id]);
if($stmt->fetch()) die("Ujian sudah dikirim sebelumnya.");

// Waktu server
$start_time = $_SESSION['ujian_start_time'] ?? date('Y-m-d H:i:s');
$end_time   = date('Y-m-d H:i:s');
$duration   = strtotime($end_time) - strtotime($start_time); // detik

// Ambil jawaban benar
$ids = array_keys($jawaban_user);
$placeholders = implode(',', array_fill(0,count($ids),'?'));
$stmt = $pdo->prepare("SELECT id, correct_option FROM questions WHERE id IN ($placeholders)");
$stmt->execute($ids);
$soal_data = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$score = 0;
foreach($jawaban_user as $id => $jawaban){
    if(isset($soal_data[$id]) && strtoupper($jawaban) == strtoupper($soal_data[$id])){
        $score += 2; // 2 poin per soal benar
    }
}

// Simpan ke database (kolom duration opsional)
$stmt = $pdo->prepare("
    INSERT INTO hasil_ujian (user_id, formasi, score, start_time, end_time, duration, status)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$user_id, $formasi, $score, $start_time, $end_time, $duration, 'selesai']);

// Bersihkan session
unset($_SESSION['ujian_jawaban']);
unset($_SESSION['ujian_waktu']);
unset($_SESSION['ujian_start_time']);

header("Location: terima_kasih.php");
exit;
