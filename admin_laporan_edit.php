<?php
require 'db.php';

// Ambil data berdasarkan ID
$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID tidak valid");
}

// Ambil data peserta dari tabel hasil_ujian
$stmt = $pdo->prepare("
    SELECT h.id, u.nama_lengkap, h.formasi, h.score 
    FROM hasil_ujian h
    JOIN users u ON u.id = h.user_id
    WHERE h.id = ?
");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Data tidak ditemukan");
}

// Update skor jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newScore = $_POST['score'];
    $update = $pdo->prepare("UPDATE hasil_ujian SET score = ? WHERE id = ?");
    $update->execute([$newScore, $id]);
    header("Location: admin_laporan.php"); // kembali ke laporan
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Skor</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f5f5f5;
    padding: 50px;
}
.container {
    max-width: 500px;
    margin: auto;
    background: #fff;
    padding: 20px 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
h2 { text-align: center; }
label { display:block; margin-top:15px; }
input[type="number"] {
    width: 100%; padding: 8px; margin-top: 5px;
    border: 1px solid #ccc; border-radius: 5px;
}
button {
    margin-top: 20px;
    padding: 10px 15px;
    background: #1f3c88;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
button:hover { background: #3c7dcf; }
a {
    display: inline-block;
    margin-top: 10px;
    text-decoration: none;
    color: #1f3c88;
}
</style>
</head>
<body>
<div class="container">
    <h2>Edit Skor Peserta</h2>
    <form method="POST">
        <p><b>Nama Peserta:</b> <?=htmlspecialchars($data['nama_lengkap'])?></p>
        <p><b>Formasi:</b> <?=htmlspecialchars($data['formasi'])?></p>
        
        <label>Skor Baru</label>
        <input type="number" name="score" value="<?=htmlspecialchars($data['score'])?>" required>
        
        <button type="submit">Simpan Perubahan</button>
    </form>
    <a href="admin_laporan.php">← Kembali ke Laporan</a>
</div>
</body>
</html>
