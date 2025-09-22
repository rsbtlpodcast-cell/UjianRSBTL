<?php
session_start();
require 'db.php';

// Daftar formasi sesuai urutan
$formasiList = ['Dokter','Rekam Medis','Radiologi','Perawat','ATLM'];

// Ambil data gabung semua formasi
$laporanGabung = [];
foreach($formasiList as $f){
    $stmt = $pdo->prepare("
        SELECT u.nama_lengkap, h.formasi, h.score
        FROM hasil_ujian h
        JOIN users u ON u.id = h.user_id
        WHERE h.formasi = ?
        ORDER BY h.score DESC
    ");
    $stmt->execute([$f]);
    $laporanGabung = array_merge($laporanGabung, $stmt->fetchAll(PDO::FETCH_ASSOC));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Laporan Ujian</title>
<style>
body {
    font-family: "Arial", sans-serif;
    margin: 30px;
    color: #000;
    font-size: 11pt;
}
.kop-surat {
    border-bottom: 3px solid #000;
    padding-bottom: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}
.kop-surat img {
    width: 100px;
    height: 100px;
    object-fit: contain;
    margin-right: 20px;
}
.kop-text {
    flex: 1;
    text-align: center;
}
.kop-text h1 { margin: 0; font-size: 16pt; font-weight: bold; }
.kop-text h2 { margin: 0; font-size: 14pt; font-weight: bold; }
.kop-text p { margin: 2px; font-size: 10pt; }

.judul-laporan {
    text-align: center;
    font-weight: bold;
    margin: 20px 0;
    font-size: 13pt;
    text-transform: uppercase;
}

/* Tabel rapih */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    font-size: 11pt;
}
th, td {
    border: 1px solid #444;
    padding: 6px 8px;
}
th {
    background: #ddd;
    color: #000;
    text-align: center;
    font-weight: bold;
}
td { text-align: center; }
td:nth-child(2) { text-align: left; } /* Nama Lengkap rata kiri */
tr:nth-child(even) { background: #f9f9f9; }
tr:nth-child(odd) { background: #fff; }

@media print {
    .no-print { display: none; }
    table { page-break-inside: auto; }
    tr { page-break-inside: avoid; page-break-after: auto; }
}

button {
    padding: 8px 15px;
    font-size: 12pt;
    background: #444;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
button:hover { background: #222; }
</style>
</head>
<body>

<div class="kop-surat">
    <img src="img/logokab.png" alt="Logo RSUD">
    <div class="kop-text">
        <h1>PEMERINTAH KABUPATEN BANDUNG</h1>
        <h1>DINAS KESEHATAN</h1>
        <h1>RUMAH SAKIT UMUM DAERAH BEDAS TEGALLUAR</h1>
        <p>Jln. Rancawangi Desa Tegalluar Kec. Bojongsoang Kode Pos 40287 Kab. Bandung</p>
        <p>Hotline : 081298569588, Email : rsudbedas.tegalluar@gmail.com</p>
    </div>
</div>

<div class="judul-laporan">
    <p>LAPORAN HASIL UJIAN SELEKSI TERTULIS</p>
</div>

<table>
    <thead>
        <tr>
            <th style="width:5%;">No</th>
            <th style="width:50%;">Nama Lengkap</th>
            <th style="width:25%;">Formasi</th>
            <th style="width:20%;">Skor</th>
        </tr>
    </thead>
    <tbody>
    <?php $no=1; foreach($laporanGabung as $row): ?>
        <tr>
            <td><?=$no++?></td>
            <td><?=htmlspecialchars($row['nama_lengkap'])?></td>
            <td><?=htmlspecialchars($row['formasi'])?></td>
            <td><?=htmlspecialchars($row['score'])?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div style="margin-top:50px; text-align:right; font-size:12pt;">
    <p>Tegalluar, <?=date('d F Y')?></p>
    <p><b>Kasubag Tata Usaha RSUD Bedas Tegalluar</b></p>
    <br><br><br>
    <p><u>Nora Haryanti,S.Sos</u><br>NIP. </p>
</div>

<div class="no-print" style="text-align:center; margin-top:30px;">
    <button onclick="window.print()">🖨 Cetak Laporan</button>
</div>

</body>
</html>
