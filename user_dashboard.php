<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// Cek status ujian
$stmt = $pdo->prepare("SELECT status FROM hasil_ujian WHERE user_id=?");
$stmt->execute([$user['id']]);
$hasil = $stmt->fetch();
$ujian_selesai = ($hasil && $hasil['status'] === 'selesai');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard User - RSUD</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body { margin:0; font-family:'Poppins', sans-serif; background:#f5f7fa; display:flex; flex-direction:column; min-height:100vh; }
.header { background: linear-gradient(135deg, #2C6ED5, #4A90E2); color:#fff; padding:40px 20px; text-align:center; border-bottom-left-radius:20px; border-bottom-right-radius:20px; box-shadow:0 6px 20px rgba(0,0,0,0.12); }
.header h1 { margin:0; font-size:30px; font-weight:700; }
.header p { margin:6px 0 0; font-size:18px; font-weight:500; }
.container { flex:1; max-width:1000px; margin:40px auto; display:flex; flex-wrap:wrap; gap:25px; justify-content:center; }
.card { flex:1 1 280px; background:#fff; border-radius:20px; padding:25px; box-shadow:0 12px 28px rgba(0,0,0,0.12); transition:all 0.3s; position:relative; overflow:hidden; }
.card::before { content:""; position:absolute; top:0; left:0; width:100%; height:6px; background: linear-gradient(135deg, #2C6ED5, #4A90E2); border-top-left-radius:20px; border-top-right-radius:20px; }
.card:hover { box-shadow:0 16px 36px rgba(0,0,0,0.16); transform: translateY(-4px); }
.card h2 { margin-top:0; font-size:20px; color:#2C6ED5; margin-bottom:18px; }
.info-item { display:flex; align-items:center; gap:12px; margin-bottom:12px; font-size:16px; font-weight:500; }
.info-item i { font-size:20px; color:#2C6ED5; }
button { width:100%; padding:16px; margin-top:15px; border:none; border-radius:14px; font-size:16px; font-weight:600; cursor:pointer; transition: all 0.3s; }
.btn-ujian { background: linear-gradient(135deg, #2C6ED5, #1A4FA0); color:#fff; }
.btn-ujian:disabled { background:#ccc; cursor:not-allowed; }
.btn-logout { background:#c0392b; color:#fff; }
.btn-logout:hover { background:#a02925; }
button:hover:not(:disabled) { transform:translateY(-2px); box-shadow:0 8px 20px rgba(0,0,0,0.12); }
footer { text-align:center; padding:15px 0; background:#f0f0f0; color:#555; font-size:12px; border-top:1px solid #ccc; margin-top:auto; }
@media(max-width:768px){ .container{ margin:20px; } .card { flex:1 1 100%; padding:20px; } button{ padding:14px; font-size:15px; } }
</style>
</head>
<body>
<div class="header">
    <h1>Selamat Datang, <?php echo htmlspecialchars($user['nama_lengkap']); ?> 👋</h1>
    <p>Formasi: <?php echo htmlspecialchars($user['formasi']); ?></p>
</div>
<div class="container">
    <div class="card">
        <h2>Informasi Akun</h2>
        <div class="info-item"><i class="fa fa-user"></i> <span>Username: <?php echo htmlspecialchars($user['username']); ?></span></div>
        <div class="info-item"><i class="fa fa-id-badge"></i> <span>Formasi: <?php echo htmlspecialchars($user['formasi']); ?></span></div>
        <div class="info-item"><i class="fa fa-circle-check"></i> <span>Status: <?php echo $ujian_selesai ? 'Selesai' : 'Belum Ujian'; ?></span></div>
        <button class="btn-ujian" onclick="window.location.href='ujian.php'" <?php echo $ujian_selesai ? 'disabled' : ''; ?>>Mulai Ujian</button>
    </div>
    <div class="card">
        <h2>Tips Persiapan Ujian</h2>
        <div class="info-item"><i class="fa fa-lightbulb"></i> <span>Baca soal dengan teliti</span></div>
        <div class="info-item"><i class="fa fa-clock"></i> <span>Kelola waktu dengan baik</span></div>
        <button class="btn-logout" onclick="window.location.href='logout.php'">Logout</button>
    </div>
</div>

<footer>
    &copy; <?= date('Y') ?> RSUD Bedas Tegalluar. All Rights Reserved.
</footer>
</body>
</html>
