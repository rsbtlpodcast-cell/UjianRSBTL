<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Formasi
$formasiList = ['Dokter','Rekam Medis','Radiologi','Perawat','ATLM'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin RSUD</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600,700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<style>
body {margin:0; font-family:'Poppins',sans-serif; background:#f5f7fa;}
header {background:linear-gradient(135deg,#2C6ED5,#4A90E2); color:#fff; padding:15px 40px; display:flex; justify-content:space-between; align-items:center;}
header h1{margin:0;font-size:24px;}
.nav a {color:#fff; text-decoration:none; margin-left:20px; font-weight:500; transition:0.3s;}
.nav a:hover {opacity:0.8;}
.container {max-width:1000px; margin:30px auto; padding:0 20px;}
.card {background:#fff; border-radius:15px; padding:20px; box-shadow:0 8px 20px rgba(0,0,0,0.1); margin-bottom:25px;}
.card h2 {margin-top:0;color:#2C6ED5;font-size:20px;margin-bottom:15px;}
.chart-container {display:flex; flex-wrap:wrap; justify-content:space-between; gap:20px;}
.chart-box {width:48%;}
.chart-box h3 {text-align:center; margin-bottom:10px; font-size:16px;}
table {width:100%; border-collapse:collapse; margin-top:15px; font-size:14px;}
th, td {padding:8px 10px; border:1px solid #ddd; text-align:center;}
th {background:#f0f0f0;}
.progress {background:#eee; border-radius:10px; height:20px; margin-bottom:10px;}
.progress-bar {height:100%; border-radius:10px; text-align:center; color:#fff; line-height:20px;}
.progress-login {background:#2C6ED5;}
.progress-selesai {background:#FFCE56; color:#000;}
footer {text-align:center; margin-top:20px; font-size:13px; color:#666;}
@media(max-width:800px){.chart-box{width:100%;}}
</style>
</head>
<body>
<header style="background: linear-gradient(135deg,#2C6ED5,#4A90E2); 
               color:#fff; 
               padding:15px 40px; 
               display:flex; 
               justify-content:space-between; 
               align-items:center; 
               border-radius:0 0 20px 20px; 
               box-shadow: 0 4px 20px rgba(0,0,0,0.15); 
               position:relative; 
               overflow:hidden;">
    <!-- Logo RSUD -->
    <div style="display:flex; align-items:center; gap:15px;">
        <img src="img/logorsud.png" alt="Logo RSUD" 
     style="height:80px; border-radius:10px; 
            box-shadow:0 2px 10px rgba(0,0,0,0.2);
            filter: brightness(0) invert(1);">
        <h1 style="margin:0; font-size:26px; font-weight:700; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
            Dashboard Admin
        </h1>
    </div>

    <!-- Menu -->
    <nav class="nav" style="display:flex; gap:20px;">
        <a href="admin_dashboard.php" style="position:relative; padding:5px 10px; border-radius:8px; transition:0.3s;">Menu Utama</a>
        <a href="admin_soal.php" style="position:relative; padding:5px 10px; border-radius:8px; transition:0.3s;">Upload / Kelola Soal</a>
        <a href="admin_laporan.php" style="position:relative; padding:5px 10px; border-radius:8px; transition:0.3s;">Laporan Hasil Ujian</a>
        <a href="logout.php" style="position:relative; padding:5px 10px; border-radius:8px; transition:0.3s; background:#FF5E5E;">Logout</a>
    </nav>

    <!-- Animasi gradient overlay -->
    <div style="position:absolute; top:0; left:0; width:100%; height:100%; background:linear-gradient(120deg, rgba(255,255,255,0.1), rgba(255,255,255,0)); pointer-events:none; animation:slide 8s linear infinite;"></div>
</header>

<style>
.nav a:hover{
    background: rgba(255,255,255,0.2);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
@keyframes slide{
    0% {background-position:0 0;}
    50% {background-position:200% 0;}
    100% {background-position:0 0;}
}
</style>

<div class="container">

    <!-- Persentase keseluruhan -->
    <div class="card">
        <h2>Persentase Keseluruhan Peserta</h2>
        <p>Login: <span id="totalLogin">0</span> peserta</p>
        <div class="progress">
            <div class="progress-bar progress-login" id="barLogin" style="width:0%">0%</div>
        </div>
        <p>Selesai Ujian: <span id="totalSelesai">0</span> peserta</p>
        <div class="progress">
            <div class="progress-bar progress-selesai" id="barSelesai" style="width:0%">0%</div>
        </div>
    </div>

    <!-- Chart Login vs Selesai per Formasi -->
    <div class="card">
        <h2>Grafik Login vs Selesai per Formasi</h2>
        <div class="chart-container">
            <div class="chart-box">
                <h3>Login</h3>
                <canvas id="loginChart" height="200"></canvas>
            </div>
            <div class="chart-box">
                <h3>Selesai Ujian</h3>
                <canvas id="selesaiChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Total peserta per formasi -->
    <div class="card">
        <h2>Total Peserta per Formasi</h2>
        <table id="tablePeserta">
            <tr>
                <th>Formasi</th>
                <th>Login</th>
                <th>Selesai</th>
            </tr>
        </table>
    </div>

</div>

<footer>
    © <?=date('Y')?> RSUD Bedas Tegalluar. All rights reserved.
</footer>

<script>
let formasi = [];
let loginChart, selesaiChart;

function loadData(){
    $.ajax({
        url:'admin_dashboard_data.php',
        dataType:'json',
        success:function(data){
            formasi = data.labels;

            // Update progress
            let totalLogin = data.login.reduce((a,b)=>a+b,0);
            let totalSelesai = data.selesai.reduce((a,b)=>a+b,0);

            $('#totalLogin').text(totalLogin);
            $('#totalSelesai').text(totalSelesai);

            $('#barLogin').css('width','100%').text(totalLogin+' peserta');
            let persenSelesai = totalLogin>0 ? Math.round(totalSelesai/totalLogin*100) : 0;
            $('#barSelesai').css('width', persenSelesai+'%').text(totalSelesai+' peserta');

            // Update table
            let table = $('#tablePeserta');
            table.find("tr:gt(0)").remove(); // hapus semua baris kecuali header
            for(let i=0;i<formasi.length;i++){
                table.append('<tr><td>'+formasi[i]+'</td><td>'+data.login[i]+'</td><td>'+data.selesai[i]+'</td></tr>');
            }

            // Update charts
            if(!loginChart){
                const ctxLogin = document.getElementById('loginChart').getContext('2d');
                loginChart = new Chart(ctxLogin, {
                    type:'pie',
                    data:{labels:formasi,datasets:[{data:data.login, backgroundColor:['#2C6ED5','#4A90E2','#FFCE56','#FF6384','#9966FF']}]},
                    options:{plugins:{legend:{position:'top'}}}
                });
            } else {
                loginChart.data.datasets[0].data = data.login;
                loginChart.update();
            }

            if(!selesaiChart){
                const ctxSelesai = document.getElementById('selesaiChart').getContext('2d');
                selesaiChart = new Chart(ctxSelesai, {
                    type:'pie',
                    data:{labels:formasi,datasets:[{data:data.selesai, backgroundColor:['#2C6ED5','#4A90E2','#FFCE56','#FF6384','#9966FF']}]},
                    options:{plugins:{legend:{position:'top'}}}
                });
            } else {
                selesaiChart.data.datasets[0].data = data.selesai;
                selesaiChart.update();
            }
        }
    });
}

// Load pertama kali
loadData();

// Update tiap 5 detik
setInterval(loadData, 5000);
</script>
</body>
</html>
