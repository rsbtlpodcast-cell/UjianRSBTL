<?php
session_start();
require 'db.php';

// cek login admin
if(!isset($_SESSION['user']) || $_SESSION['user'] === null || $_SESSION['user']['role'] !== 'admin'){
    header("Location: login.php");
    exit;
}

$message = "";
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $new_username = trim($_POST['username']);
    $new_password = $_POST['password'];

    if($new_username && $new_password){
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $admin_id = $_SESSION['user']['id'];
        $stmt = $pdo->prepare("UPDATE users SET username=?, password=? WHERE id=?");
        if($stmt->execute([$new_username, $hash, $admin_id])){
            $message = "Username dan password berhasil di-reset.";
            $_SESSION['user']['username'] = $new_username; // update session
        } else {
            $message = "Gagal mereset admin.";
        }
    } else {
        $message = "Username dan password tidak boleh kosong.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Reset Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
body {
    margin:0;
    font-family:'Poppins',sans-serif;
    background: linear-gradient(135deg,#11998e,#38ef7d);
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}
.card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(12px);
    border-radius:20px;
    padding:40px 35px;
    max-width:400px;
    width:100%;
    text-align:center;
    box-shadow:0 12px 30px rgba(0,0,0,0.15);
}
h2 {
    margin:0 0 25px;
    font-size:26px;
    color:#111;
    font-weight:700;
}
input {
    width:100%;
    padding:14px 15px;
    margin-bottom:15px;
    border:1px solid #ddd;
    border-radius:12px;
    font-size:15px;
    background:#fafafa;
    transition:all .3s;
}
input:focus {
    border-color:#38ef7d;
    background:#fff;
    box-shadow:0 0 0 4px rgba(56,239,125,0.15);
    outline:none;
}
button {
    width:100%;
    padding:15px;
    background: linear-gradient(135deg,#38ef7d,#11998e);
    border:none;
    border-radius:12px;
    color:#fff;
    font-size:17px;
    font-weight:600;
    cursor:pointer;
    transition:all .3s;
}
button:hover {
    background: linear-gradient(135deg,#11998e,#38ef7d);
    transform: translateY(-2px);
    box-shadow:0 8px 20px rgba(0,0,0,0.2);
}
.message {
    margin-bottom:15px;
    padding:12px;
    border-radius:10px;
    background:#e6ffed;
    color:#2ecc71;
}
</style>
</head>
<body>
<div class="card">
    <h2>Reset Admin</h2>
    <?php if($message) echo "<div class='message'>$message</div>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username baru" required>
        <input type="password" name="password" placeholder="Password baru" required>
        <button type="submit">Reset Admin</button>
    </form>
</div>
</body>
</html>
