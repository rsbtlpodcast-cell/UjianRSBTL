<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='user'){
    header("Location: login.php");
    exit;
}
$user=$_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Terima Kasih - RSUD</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
body{margin:0;font-family:'Poppins',sans-serif;background:#f5f7fa;}
.header{background:linear-gradient(135deg,#2C6ED5,#4A90E2);color:#fff;padding:40px 20px;text-align:center;border-bottom-left-radius:20px;border-bottom-right-radius:20px;box-shadow:0 6px 20px rgba(0,0,0,0.12);}
.header h1{margin:0;font-size:28px;font-weight:700;}
.header p{margin:6px 0 0;font-size:18px;font-weight:500;}
.container{max-width:600px;margin:40px auto;background:#fff;border-radius:20px;padding:30px 40px;box-shadow:0 12px 28px rgba(0,0,0,0.12);text-align:center;}
button{padding:16px 25px;margin-top:25px;font-size:16px;font-weight:600;border:none;border-radius:14px;background:linear-gradient(135deg,#2C6ED5,#1A4FA0);color:#fff;cursor:pointer;transition:all 0.3s;}
button:hover{background:linear-gradient(135deg,#1A4FA0,#143B7A);transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,0.12);}
</style>
</head>
<body>
<div class="header">
<h1>Terima Kasih, <?php echo htmlspecialchars($user['nama_lengkap']); ?>!</h1>
<p>Ujian Anda telah selesai.</p>
</div>
<div class="container">
<p>Hasil ujian Anda telah tersimpan dengan aman.</p>
<button onclick="window.location.href='user_dashboard.php'">Kembali ke Dashboard</button>
</div>
<footer style="text-align:center; padding:15px 0; background:#f0f0f0; color:#555; font-size:12px; border-top:1px solid #ccc; margin-top:30px;">
    &copy; <?= date('Y') ?> RSUD Bedas Tegalluar. All Rights Reserved.
</footer>

</body>
</html>
