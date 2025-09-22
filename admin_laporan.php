<?php
session_start();
require 'db.php';

// Cek admin
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    header("Location: login.php");
    exit;
}

// Daftar formasi
$formasiList = ['Dokter','Rekam Medis','Radiologi','Perawat','ATLM'];

// Ambil semua peserta sekaligus
$laporanGabung = [];
foreach($formasiList as $f){
    $stmt = $pdo->prepare("
        SELECT h.id, u.nama_lengkap, h.formasi, h.score
        FROM hasil_ujian h
        JOIN users u ON u.id = h.user_id
        WHERE h.formasi = ?
        ORDER BY h.score DESC
    ");
    $stmt->execute([$f]);
    $laporanGabung[$f] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Hasil Ujian</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background: linear-gradient(to right, #1f3c88, #3c7dcf); margin: 0; padding: 50px 0; color: #333; }
.container { max-width: 1200px; margin: 0 auto; background: #fff; padding: 30px 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
h2 { text-align: center; margin-bottom: 30px; color: #1f3c88; font-weight: 600; letter-spacing: 1px; }
.search-box { margin-bottom:20px; text-align:right; }
.search-box input { padding:8px 12px; width:250px; border-radius:10px; border:1px solid #ccc; font-size:14px; }

table { width: 100%; border-collapse: collapse; font-size: 15px; margin-bottom: 30px; }
th, td { padding: 10px; border: 1px solid #ddd; }
th { background: #f0f0f0; color: #000; text-transform: uppercase; letter-spacing: 1px; text-align: center; }
td { text-align: center; }
td:first-child { font-weight: bold; }
td:nth-child(2) { text-align: left; }
tr:nth-child(even) { background: #f9f9f9; }
tr:hover { background: #f0f8ff; }

a.nama-link { color: #1f3c88; text-decoration: none; font-weight: 500; }
a.nama-link:hover { text-decoration: underline; }

.back-btn, .print-btn { display: inline-block; text-decoration: none; padding: 8px 15px; background: #1f3c88; color: #fff; border-radius: 10px; transition: 0.3s; font-size: 14px; margin-top: 10px; }
.back-btn:hover, .print-btn:hover { background: #3c7dcf; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }

.print-all-container { display:flex; justify-content:flex-end; margin-bottom:10px; }
footer { text-align:center; margin-top:20px; font-size:13px; color:#666; }
</style>
</head>
<body>
<div class="container">
<h2>Laporan Hasil Ujian</h2>

<!-- Tombol Cetak Semua Laporan -->
<div class="print-all-container">
    <a class="print-btn" href="cetak_laporan.php" target="_blank">🖨 Cetak Semua Laporan</a>
</div>

<!-- Pencarian realtime -->
<div class="search-box">
    <input type="text" id="searchInput" placeholder="Cari nama peserta...">
</div>

<table id="laporanTable">
<tr>
    <th>No</th>
    <th>Nama Lengkap</th>
    <th>Formasi</th>
    <th>Skor</th>
</tr>
<?php 
$no=1; 
foreach($formasiList as $formasi): 
    if(!empty($laporanGabung[$formasi])):
        foreach($laporanGabung[$formasi] as $l): ?>
<tr>
    <td><?=$no++?></td>
    <td>
        <a href="admin_laporan_edit.php?id=<?=$l['id']?>" class="nama-link">
            <?=htmlspecialchars($l['nama_lengkap'])?>
        </a>
    </td>
    <td><?=htmlspecialchars($l['formasi'])?></td>
    <td><?=htmlspecialchars($l['score'])?></td>
</tr>
<?php 
        endforeach;
    endif;
endforeach; ?>
</table>

<a class="back-btn" href="admin_dashboard.php">← Kembali ke Dashboard</a>
<footer>© <?=date('Y')?> RSUD Bedas Tegalluar. All rights reserved.</footer>
</div>

<script>
// Filter realtime
document.getElementById("searchInput").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#laporanTable tr:not(:first-child)");
    rows.forEach(r => {
        let nama = r.cells[1].textContent.toLowerCase();
        r.style.display = nama.indexOf(filter) > -1 ? "" : "none";
    });
});
</script>
</body>
</html>
