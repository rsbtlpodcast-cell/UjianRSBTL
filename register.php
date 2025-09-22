<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $username     = trim($_POST['username']);
    $password     = $_POST['password'];
    $formasi      = trim($_POST['formasi']);

    if(empty($formasi)){
        $error = "Pilih formasi terlebih dahulu!";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Username sudah terdaftar!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (nama_lengkap, username, password, formasi, role, is_logged_in) VALUES (?, ?, ?, ?, 'user', 0)");
            if ($stmt->execute([$nama_lengkap, $username, $hash, $formasi])) {
                $_SESSION['success'] = "Registrasi berhasil, silakan login.";
                header("Location: login.php");
                exit;
            } else {
                $error = "Terjadi kesalahan, coba lagi.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Registrasi Akun RSUD</title>
<style>
body {
  margin:0;
  font-family: 'Poppins', sans-serif;
  background:#f0f2f5;
  display:flex;
  flex-direction:column;
  align-items:center;
  padding:40px 0;
  min-height:100vh;
}
.container {
  display:flex;
  width:100%;
  max-width:1200px;
  border-radius:20px;
  overflow:hidden;
  box-shadow:0 12px 28px rgba(0,0,0,0.12);
  background:#fff;
}
.left-side {
  flex:1;
  background: linear-gradient(135deg, #4A90E2, #2C6ED5);
  display:flex;
  flex-direction:column;
  padding:50px 40px;
  color:#fff;
  justify-content:flex-start;
  position:relative;
}
.left-side h1 { font-size:28px; font-weight:700; margin-bottom:18px; }
.left-side p { font-size:16px; line-height:1.6; color:rgba(255,255,255,0.9); }
.right-side { flex:1; display:flex; justify-content:center; align-items:center; padding:50px 50px; }
.card {
  width:100%;
  max-width:500px;
  text-align:center;
  padding:40px 35px;
  border-radius:16px;
  box-shadow:0 10px 28px rgba(0,0,0,0.08);
  background:#fff;
}
h2 { margin-bottom:30px; font-size:24px; font-weight:600; color:#222; }
.input-group { margin-bottom:18px; text-align:left; }
.input-group label { display:block; margin-bottom:6px; font-size:14px; font-weight:500; color:#444; }
.input-group input {
  width:100%; padding:13px 15px; border:1px solid #ddd;
  border-radius:10px; font-size:15px; background:#fafafa; transition:all .3s;
}
.input-group input:focus {
  border-color:#2C6ED5; background:#fff;
  box-shadow:0 0 0 3px rgba(44,110,213,0.15); outline:none;
}
.formasi-options {
  display:grid; grid-template-columns:repeat(auto-fit, minmax(110px, 1fr));
  gap:12px; margin-top:10px;
}
.formasi-option {
  border:2px solid #ddd; border-radius:12px; padding:16px 8px;
  cursor:pointer; text-align:center; font-weight:600; font-size:14px;
  background:#fafafa; transition:all .3s;
}
.formasi-option:hover {
  border-color:#2C6ED5; background:#e6f0ff;
  transform:translateY(-2px); box-shadow:0 5px 12px rgba(0,0,0,0.08);
}
.formasi-option.selected {
  border-color:#2C6ED5; background:#d0e4ff; color:#2C6ED5;
  box-shadow:0 0 0 3px rgba(44,110,213,0.15);
}
.hidden-input { display:none; }
button {
  width:100%; padding:16px; margin-top:20px;
  background: linear-gradient(135deg, #2C6ED5, #1A4FA0);
  border:none; border-radius:12px; color:#fff;
  font-size:16px; font-weight:600; cursor:pointer; transition: all .3s;
}
button:hover {
  background: linear-gradient(135deg, #1A4FA0, #143B7A);
  transform: translateY(-2px); box-shadow:0 5px 16px rgba(0,0,0,0.12);
}
.error, .success {
  padding:12px; border-radius:10px; margin-bottom:15px; font-size:14px;
}
.error { background:#ffe6e6; color:#c0392b; }
.success { background:#e6ffed; color:#27ae60; }
.link { margin-top:18px; font-size:14px; color:#555; }
.link a { color:#2C6ED5; text-decoration:none; font-weight:500; }
.link a:hover { text-decoration:underline; }
footer {
  margin-top:25px; font-size:12px; color:#555;
  text-align:center; padding:10px 0;
  border-top:1px solid #ddd; width:100%; max-width:1200px;
}
@media(max-width:1200px){
  .container { flex-direction:column; width:95%; }
  .left-side, .right-side { padding:30px 20px; }
}
</style>
</head>
<body>
<div class="container">
  <div class="left-side">
    <h1>Selamat Datang di RSUD Bedas Tegalluar</h1>
    <p>Silakan registrasi untuk memulai ujian. Pilih formasi sesuai jabatan Anda.</p>
  </div>
  <div class="right-side">
    <div class="card">
      <h2>Registrasi Akun</h2>
      <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
      <?php if (!empty($_SESSION['success'])) { echo "<p class='success'>".$_SESSION['success']."</p>"; unset($_SESSION['success']); } ?>
      <form method="post">
        <div class="input-group">
          <label>Nama Lengkap</label>
          <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap" required>
        </div>
        <div class="input-group">
          <label>Username</label>
          <input type="text" name="username" placeholder="Masukkan username" required>
        </div>
        <div class="input-group">
          <label>Password</label>
          <input type="password" name="password" placeholder="Minimal 6 karakter" required minlength="6">
        </div>
        <div class="input-group">
          <label>Pilih Formasi</label>
          <input type="hidden" name="formasi" id="formasiInput" class="hidden-input" required>
          <div class="formasi-options">
            <div class="formasi-option" data-value="Dokter">👨‍⚕️ Dokter</div>
            <div class="formasi-option" data-value="Perawat">🩺 Perawat</div>
            <div class="formasi-option" data-value="Radiologi">🩻 Radiologi</div>
            <div class="formasi-option" data-value="Rekam Medis">📑 Rekam Medis</div>
            <div class="formasi-option" data-value="ATLM">🧪 ATLM</div>
          </div>
        </div>
        <button type="submit">Registrasi</button>
      </form>
      <div class="link">
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
      </div>
    </div>
  </div>
</div>

<!-- Copyright -->
<footer>
  &copy; <?= date('Y') ?> RSUD Bedas Tegalluar. All Rights Reserved.
</footer>

<script>
const options = document.querySelectorAll('.formasi-option');
const formasiInput = document.getElementById('formasiInput');
options.forEach(opt => {
  opt.addEventListener('click', () => {
    options.forEach(o => o.classList.remove('selected'));
    opt.classList.add('selected');
    formasiInput.value = opt.getAttribute('data-value');
  });
});
</script>
</body>
</html>
