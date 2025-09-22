<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Update status login realtime
        $update = $pdo->prepare("UPDATE users SET is_logged_in = 1 WHERE id = ?");
        $update->execute([$user['id']]);

        $_SESSION['user'] = $user;

        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Login Ujian RSUD Bedas Tegalluar</title>
  <style>
    body {
      margin:0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #ffffff, #f0f0f0);
      display:flex;
      flex-direction:column;
      justify-content:center;
      align-items:center;
      min-height:100vh;
    }
    .card {
      background: rgba(255,255,255,0.95);
      backdrop-filter: blur(12px);
      border-radius:20px;
      box-shadow:0 12px 30px rgba(0,0,0,0.12);
      padding:45px 40px;
      max-width:430px;
      width:100%;
      text-align:center;
      border:1px solid #eee;
    }
    .logo { width:150px; margin-bottom:22px; }
    h2 { margin:0 0 30px; font-size:26px; color:#111; font-weight:700; letter-spacing:0.5px; }
    .input-group { margin-bottom:20px; text-align:left; }
    .input-group label { display:block; margin-bottom:6px; font-size:14px; font-weight:500; color:#333; }
    .input-group input {
      width:100%; padding:14px 15px; border:1px solid #ddd;
      border-radius:12px; font-size:15px; background:#fafafa; transition:all .3s;
    }
    .input-group input:focus {
      border-color:#007BFF; background:#fff;
      box-shadow:0 0 0 4px rgba(0,123,255,0.15); outline:none;
    }
    button {
      width:100%; padding:15px; margin-top:12px;
      background: linear-gradient(135deg, #007BFF, #0056b3);
      border:none; border-radius:12px; color:#fff;
      font-size:17px; font-weight:600; cursor:pointer; transition: all .3s;
    }
    button:hover {
      background: linear-gradient(135deg, #0056b3, #00408d);
      transform: translateY(-2px);
      box-shadow:0 8px 20px rgba(0,0,0,0.15);
    }
    .error { color:#c0392b; margin-bottom:18px; font-size:14px; }
    .success { color:#27ae60; margin-bottom:18px; font-size:14px; }
    .link { margin-top:24px; font-size:14px; color:#555; }
    .link a { color:#007BFF; text-decoration:none; font-weight:500; }
    .link a:hover { text-decoration:underline; }
    footer {
      margin-top:25px;
      font-size:12px;
      color:#555;
      text-align:center;
      padding:10px 0;
      border-top:1px solid #ddd;
      width:100%;
      max-width:430px;
    }
    @media(max-width:480px){
      .card { padding:30px 24px; border-radius:16px; }
      h2 { font-size:22px; }
      .logo { width:95px; }
    }
  </style>
</head>
<body>
  <div class="card">
    <img src="img/logorsud.png" alt="Logo RSUD" class="logo">
    <h2>E-Login Sistem Ujian</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if (!empty($_SESSION['success'])) { echo "<p class='success'>" . $_SESSION['success'] . "</p>"; unset($_SESSION['success']); } ?>
    <form method="post">
      <div class="input-group">
        <label>Username</label>
        <input type="text" name="username" required>
      </div>
      <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>
      <button type="submit">Login</button>
    </form>
    <div class="link">
      <p>Belum punya akun? <a href="register.php">Registrasi di sini</a></p>
    </div>
  </div>

  <!-- Copyright -->
  <footer>
    &copy; <?= date('Y') ?> RSUD Bedas Tegalluar. All Rights Reserved.
  </footer>
</body>
</html>
