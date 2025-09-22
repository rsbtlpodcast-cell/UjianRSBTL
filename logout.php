<?php
session_start();
require 'db.php';

// Jika user sedang login, set is_logged_in = 0
if (isset($_SESSION['user'])) {
    $stmt = $pdo->prepare("UPDATE users SET is_logged_in = 0 WHERE id = ?");
    $stmt->execute([$_SESSION['user']['id']]);
}

// Hapus semua session
session_unset();
session_destroy();

// Redirect ke login
header("Location: login.php");
exit;
?>
