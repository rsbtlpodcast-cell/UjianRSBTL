<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

// Set start_time hanya sekali
if(!isset($_SESSION['ujian_start_time'])){
    $_SESSION['ujian_start_time'] = date('Y-m-d H:i:s'); // waktu klik mulai
    $_SESSION['ujian_waktu'] = 60*60; // 60 menit
    $_SESSION['ujian_jawaban'] = [];
}

header("Location: ujian.php");
exit;
