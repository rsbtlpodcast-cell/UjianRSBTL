<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];
$formasi_user = $user['formasi'] ?? 'Dokter';

// Inisialisasi ujian
if(!isset($_SESSION['ujian_start_time'])){
    $_SESSION['ujian_start_time'] = date('Y-m-d H:i:s');
    $_SESSION['ujian_waktu'] = 60*60; // 60 menit
    $_SESSION['ujian_jawaban'] = [];
}

$waktu = $_SESSION['ujian_waktu'];
$jawaban_tersimpan = $_SESSION['ujian_jawaban'] ?? [];

// Ambil soal sesuai formasi user
$stmt = $pdo->prepare("SELECT * FROM questions WHERE formasi=? ORDER BY id ASC");
$stmt->execute([$formasi_user]);
$soal = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Ujian RSUD - <?=htmlspecialchars($formasi_user)?></title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
body{margin:0;font-family:'Poppins',sans-serif;background:#f5f7fa;}
.header{background:linear-gradient(135deg,#2C6ED5,#4A90E2);color:#fff;padding:20px;text-align:center;border-bottom-left-radius:20px;border-bottom-right-radius:20px;box-shadow:0 6px 20px rgba(0,0,0,0.12);}
.header h1{margin:0;font-size:26px;}
.timer{font-size:16px;margin-top:6px;}
.formasi-info{background:#e8f0fe;color:#1f3c88;font-weight:600;padding:10px 15px;border-radius:12px;margin:15px auto;text-align:center;max-width:900px;font-size:14px;}
.container{max-width:900px;margin:10px auto 30px auto;background:#fff;border-radius:20px;padding:25px 30px;box-shadow:0 12px 28px rgba(0,0,0,0.12);}
.soal-card{border:1px solid #ddd;border-radius:14px;padding:20px 25px;background:#fafafa; display:none;}
.soal-card.active{display:block;}
.soal-card h3{margin:0 0 15px;font-size:18px;}
.opsi label{display:block;margin-bottom:8px;cursor:pointer;font-size:15px;}
button#submitBtn{margin-top:20px;width:100%;padding:14px;font-size:16px;font-weight:600;background:linear-gradient(135deg,#2C6ED5,#1A4FA0);color:#fff;border:none;border-radius:14px;cursor:pointer;display:none;}
.nav-soal{display:flex;flex-wrap:wrap;gap:5px;margin-top:20px;justify-content:center;}
.nav-btn{padding:6px 10px;border:1px solid #ddd;border-radius:6px;cursor:pointer;transition:.3s;font-size:14px;}
.nav-btn.active{background:#2C6ED5;color:#fff;border-color:#2C6ED5;}
.nav-btn.answered{background:#d0e4ff;border-color:#2C6ED5;}
body, .soal-card {-webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;}
body::before {content: "RSUD Bedas Tegalluar - <?=htmlspecialchars($user['nama_lengkap']); ?>"; position: fixed; top: 40%; left: 50%; transform: translate(-50%, -50%) rotate(-25deg); font-size: 40px; color: rgba(0,0,0,0.08); z-index: 0; white-space: nowrap; pointer-events: none;}
</style>
</head>
<body>

<div class="header">
<h1>Selamat Datang, <?=htmlspecialchars($user['nama_lengkap']);?> 👋</h1>
<div class="timer">Sisa Waktu: <span id="time"></span></div>
</div>

<div class="formasi-info">
Formasi: <strong><?=htmlspecialchars($formasi_user)?></strong>
</div>

<div class="container">
<form id="ujianForm" method="post" action="submit_ujian.php">
<?php foreach($soal as $idx=>$s): ?>
<div class="soal-card <?= $idx===0?'active':'' ?>" data-index="<?=$idx?>">
<h3><?=htmlspecialchars($s['question']);?></h3>
<div class="opsi">
<?php foreach(['A','B','C','D'] as $opt): ?>
<label>
<input type="radio" name="jawaban[<?=$s['id']?>]" value="<?=$opt?>" <?= (isset($jawaban_tersimpan[$s['id']]) && $jawaban_tersimpan[$s['id']] === $opt)?'checked':''; ?>>
<?=$opt?>. <?=htmlspecialchars($s['option_'.strtolower($opt)]);?>
</label>
<?php endforeach; ?>
</div>
</div>
<?php endforeach; ?>

<button type="submit" id="submitBtn">Kirim Jawaban</button>
<div class="nav-soal" id="navSoal"></div>
</form>
</div>

<footer style="text-align:center; padding:15px 0; background:#f0f0f0; color:#555; font-size:12px; border-top:1px solid #ccc;">
&copy; <?=date('Y')?> RSUD Bedas Tegalluar. All Rights Reserved.
</footer>

<script>
const soalCards = document.querySelectorAll('.soal-card');
const navSoalDiv = document.getElementById('navSoal');
const submitBtn = document.getElementById('submitBtn');
let currentIndex = 0;

function renderNavSoal(){
    navSoalDiv.innerHTML = '';
    soalCards.forEach((card,i)=>{
        const div = document.createElement('div');
        div.classList.add('nav-btn');
        div.textContent = i+1;
        div.dataset.index=i;
        if(i===currentIndex) div.classList.add('active');
        if(card.querySelector('input[type="radio"]:checked')) div.classList.add('answered');
        div.addEventListener('click', ()=>{ currentIndex=i; showSoal(currentIndex); });
        navSoalDiv.appendChild(div);
    });
}

function showSoal(index){
    soalCards.forEach((card,i)=>{ card.classList.toggle('active', i===index); });
    renderNavSoal();
}

function checkAllAnswered(){
    let allAnswered = Array.from(soalCards).every(card=>card.querySelector('input[type="radio"]:checked')!==null);
    submitBtn.style.display = allAnswered ? 'block' : 'none';
}

// AJAX auto-save jawaban
function saveJawaban(jawaban){
    fetch('save_jawaban.php',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify(jawaban)
    }).catch(err=>console.error('Gagal auto-save:', err));
}

// Event change jawaban
soalCards.forEach((card,index)=>{
    card.querySelectorAll('input[type="radio"]').forEach(radio=>{
        radio.addEventListener('change', ()=>{
            checkAllAnswered();
            const jawaban={};
            document.querySelectorAll('input[type="radio"]:checked').forEach(i=>{
                const name=i.name.match(/\d+/)[0];
                jawaban[name]=i.value;
            });
            saveJawaban(jawaban);
            if(index<soalCards.length-1){ currentIndex=index+1; showSoal(currentIndex); }
        });
    });
});

let waktu = <?= $waktu ?>;
const timerEl = document.getElementById('time');
function updateTimer(){
    let m=Math.floor(waktu/60), s=waktu%60;
    timerEl.textContent=m.toString().padStart(2,'0')+":"+s.toString().padStart(2,'0');
    if(waktu<=0){
        clearInterval(timerInterval);
        alert("Waktu habis! Jawaban dikirim otomatis.");
        document.getElementById('ujianForm').submit();
    }
    // Update waktu server
    fetch('save_waktu.php',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({waktu:waktu})
    });
    waktu--;
}
let timerInterval = setInterval(updateTimer,1000);
updateTimer();
checkAllAnswered();
renderNavSoal();

// Anti copy & print
document.addEventListener('contextmenu', e => e.preventDefault());
document.addEventListener('keydown', e => {
    if ((e.ctrlKey || e.metaKey) && ['c','x','v','u','s','p','a'].includes(e.key.toLowerCase())) e.preventDefault();
});
document.addEventListener("keyup", function(e){
    if (e.key === "PrintScreen") {
        alert("Screenshot dinonaktifkan!");
        document.body.style.display = "none";
        setTimeout(()=>{ document.body.style.display="block"; }, 2000);
    }
});
</script>
</body>
</html>
