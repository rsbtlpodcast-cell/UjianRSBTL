<?php
require 'db.php';
ini_set('display_errors',1);
error_reporting(E_ALL);

// Formasi default
$selected_formasi = $_GET['formasi'] ?? 'Dokter';
$formasi_list = ['Dokter','Radiologi','Rekam Medis','Perawat','ATLM'];

// Fungsi hitung total soal
function getTotalSoal($pdo, $formasi){
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM questions WHERE formasi=?");
    $stmt->execute([$formasi]);
    return $stmt->fetchColumn();
}

// AJAX request tambah soal satu per satu
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['ajax_add'])){
    header('Content-Type: application/json');
    $response = ['status'=>'error','msg'=>''];

    $formasi = $_POST['formasi'] ?? '';
    $question = trim($_POST['question'] ?? '');
    $option_a = trim($_POST['option_a'] ?? '');
    $option_b = trim($_POST['option_b'] ?? '');
    $option_c = trim($_POST['option_c'] ?? '');
    $option_d = trim($_POST['option_d'] ?? '');
    $correct_option = $_POST['correct_option'] ?? '';

    if(!$formasi || !$question || !$option_a || !$option_b || !$option_c || !$option_d || !$correct_option){
        $response['msg'] = 'Semua kolom wajib diisi';
        echo json_encode($response);
        exit;
    }

    if(!in_array($correct_option,['A','B','C','D'])){
        $response['msg'] = 'Jawaban benar tidak valid';
        echo json_encode($response);
        exit;
    }

    $total_soal = getTotalSoal($pdo,$formasi);
    if($total_soal >=50){
        $response['msg'] = 'Jumlah soal sudah maksimal (50)';
        echo json_encode($response);
        exit;
    }

    try{
        $stmt = $pdo->prepare("INSERT INTO questions (question, option_a, option_b, option_c, option_d, correct_option, formasi) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$question,$option_a,$option_b,$option_c,$option_d,$correct_option,$formasi]);
        $last_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT * FROM questions WHERE id=?");
        $stmt->execute([$last_id]);
        $last_soal = $stmt->fetch(PDO::FETCH_ASSOC);

        $response['status']='success';
        $response['last_soal']=$last_soal;
        $response['total_soal']=getTotalSoal($pdo,$formasi);

    }catch(PDOException $e){
        $response['msg']='Terjadi kesalahan server: '.$e->getMessage();
    }

    echo json_encode($response);
    exit;
}

// AJAX request upload file massal multi-baris
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['ajax_file'])){
    header('Content-Type: application/json');
    $response = ['status'=>'error','msg'=>''];

    $formasi = $_POST['formasi'] ?? '';
    if(!$formasi){
        $response['msg']='Pilih formasi terlebih dahulu';
        echo json_encode($response);
        exit;
    }

    if(!isset($_FILES['soal_file']) || $_FILES['soal_file']['error']!=0){
        $response['msg']='File tidak valid';
        echo json_encode($response);
        exit;
    }

    $file_content = file_get_contents($_FILES['soal_file']['tmp_name']);
    $file_content = str_replace("\r","",$file_content);

    // Pisahkan soal berdasarkan nomor (1., 2., dst.)
    $blocks = preg_split("/\n\d+\.\s*/",$file_content);
    $total_inserted = 0;

    $pdo->beginTransaction();
    try{
        foreach($blocks as $block){
            $block = trim($block);
            if(!$block) continue;
            if(getTotalSoal($pdo,$formasi)>=50) break;

            $lines = preg_split("/\n/",$block);
            $soal_text = '';
            $options = ['A'=>'','B'=>'','C'=>'','D'=>''];
            $correct_option = '';

            foreach($lines as $line){
                $line = trim($line);
                if(!$line) continue;
                if(preg_match('/^[Aa]\.\s*(.+)$/',$line,$m)) $options['A']=$m[1];
                elseif(preg_match('/^[Bb]\.\s*(.+)$/',$line,$m)) $options['B']=$m[1];
                elseif(preg_match('/^[Cc]\.\s*(.+)$/',$line,$m)) $options['C']=$m[1];
                elseif(preg_match('/^[Dd]\.\s*(.+)$/',$line,$m)) $options['D']=$m[1];
                elseif(preg_match('/^Jawaban\s*[:]\s*(\w)/i',$line,$m)) $correct_option = strtoupper($m[1]);
                else $soal_text .= ($soal_text?" ":"").$line;
            }

            if($soal_text && count(array_filter($options))===4 && $correct_option){
                $stmt = $pdo->prepare("INSERT INTO questions (question, option_a, option_b, option_c, option_d, correct_option, formasi) VALUES (?,?,?,?,?,?,?)");
                if($stmt->execute([$soal_text,$options['A'],$options['B'],$options['C'],$options['D'],$correct_option,$formasi])) $total_inserted++;
            }
        }
        $pdo->commit();
        $response['status']='success';
        $response['msg']="Berhasil menambahkan $total_inserted soal untuk formasi $formasi";
        $response['total_soal']=getTotalSoal($pdo,$formasi);

    }catch(PDOException $e){
        $pdo->rollBack();
        $response['msg']='Terjadi kesalahan server: '.$e->getMessage();
    }

    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin Upload Soal Interaktif</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body{font-family:'Poppins',sans-serif;background:#f0f2f5;margin:0;padding:30px;color:#333;}
h2{text-align:center;margin-bottom:20px;color:#1f3c88;}
.container{max-width:700px;margin:0 auto;background:#fff;padding:25px 30px;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.1);}
.info{background:#e8f0fe;color:#1f3c88;padding:12px 15px;border-radius:8px;margin-bottom:20px;font-weight:600;}
form label{display:block;margin-bottom:5px;font-weight:600;color:#1f3c88;}
form textarea, form input[type="text"], form select{width:100%;padding:12px;margin-bottom:15px;border-radius:8px;border:1px solid #ccc;font-size:1rem;transition:0.3s;}
form textarea:focus,form input:focus,form select:focus{border-color:#1f3c88;box-shadow:0 0 8px rgba(31,60,136,0.2);outline:none;}
form button{background:#1f3c88;color:#fff;border:none;padding:12px 20px;border-radius:8px;font-weight:600;cursor:pointer;transition:0.3s;width:100%;}
form button:hover{background:#4a90e2;}
form button:disabled{background:#ccc;cursor:not-allowed;}
select.formasi-selector{margin-bottom:20px;}
.preview{background:#fff9e6;padding:15px 20px;border-left:5px solid #ffdd59;border-radius:8px;margin-top:20px;}
.preview h4{margin:0 0 8px 0;color:#e67e22;}
.back-btn{display:inline-block;margin-top:20px;padding:10px 15px;background:#888;color:#fff;border-radius:8px;text-decoration:none;transition:0.3s;}
.back-btn:hover{background:#555;}
.error{background:#ffe6e6;color:#c0392b;padding:12px 15px;border-radius:8px;margin-bottom:15px;font-weight:600;display:none;}
.upload-box{background:#eafbea;border:1px dashed #2ecc71;padding:15px;border-radius:8px;margin-bottom:20px;}
.upload-box h3{margin-top:0;color:#27ae60;}
</style>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>

<h2>Admin Upload Soal Interaktif (Maks 50)</h2>
<div class="container">

    <!-- Pilih formasi -->
    <form id="formasiForm" method="get">
        <label>Pilih Formasi</label>
        <select name="formasi" class="formasi-selector" onchange="this.form.submit()">
            <?php foreach($formasi_list as $f){
                $sel = $selected_formasi==$f?'selected':'';
                echo "<option value=\"$f\" $sel>$f</option>";
            }?>
        </select>
    </form>

    <div class="info" id="infoBox">
        Total soal untuk <strong><?=htmlspecialchars($selected_formasi)?></strong>: <strong id="totalSoal"><?=getTotalSoal($pdo,$selected_formasi)?></strong>/50
    </div>

    <div class="error" id="errorBox"></div>

    <!-- Upload manual -->
    <form id="soalForm">
        <input type="hidden" name="formasi" value="<?=htmlspecialchars($selected_formasi)?>">
        <input type="hidden" name="ajax_add" value="1">

        <label>Soal</label>
        <textarea name="question" placeholder="Masukkan isi soal" required></textarea>
        <label>Pilihan A</label><input type="text" name="option_a" required>
        <label>Pilihan B</label><input type="text" name="option_b" required>
        <label>Pilihan C</label><input type="text" name="option_c" required>
        <label>Pilihan D</label><input type="text" name="option_d" required>
        <label>Jawaban Benar</label>
        <select name="correct_option" required>
            <option value="">--Pilih Jawaban--</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
        </select>

        <button type="submit" id="submitBtn">Tambah Soal</button>
    </form>

    <div class="preview" id="previewBox" style="display:none;">
        <h4>Soal Terbaru:</h4>
        <p id="previewSoal"></p>
    </div>

    <!-- Upload massal -->
    <div class="upload-box">
        <h3>Upload Soal Massal (.txt)</h3>
        <input type="file" id="fileUpload" accept=".txt">
        <button id="uploadBtn">Upload File</button>
    </div>

    <a class="back-btn" href="admin_dashboard.php">← Kembali ke Halaman Admin</a>

</div>

<script>
const form = document.getElementById('soalForm');
const errorBox = document.getElementById('errorBox');
const previewBox = document.getElementById('previewBox');
const previewSoal = document.getElementById('previewSoal');
const totalSoalElem = document.getElementById('totalSoal');
const submitBtn = document.getElementById('submitBtn');
const formasiVal = "<?=htmlspecialchars($selected_formasi)?>";

// Tambah soal manual
form.addEventListener('submit', function(e){
    e.preventDefault();
    const data = new FormData(form);
    submitBtn.disabled = true;
    submitBtn.textContent = 'Menambahkan...';

    fetch(window.location.href, {method:'POST', body:data})
    .then(res=>res.json())
    .then(resp=>{
        submitBtn.disabled=false;
        submitBtn.textContent='Tambah Soal';
        if(resp.status==='error'){
            errorBox.innerHTML=resp.msg;
            errorBox.style.display='block';
        }else{
            errorBox.style.display='none';
            const escapeHTML = str => str.replace(/</g,'&lt;').replace(/>/g,'&gt;');
            previewSoal.innerHTML = `<strong>Soal:</strong> ${escapeHTML(resp.last_soal.question)}<br>
            A: ${escapeHTML(resp.last_soal.option_a)} | B: ${escapeHTML(resp.last_soal.option_b)}<br>
            C: ${escapeHTML(resp.last_soal.option_c)} | D: ${escapeHTML(resp.last_soal.option_d)}<br>
            <strong>Jawaban:</strong> ${resp.last_soal.correct_option} | <strong>Formasi:</strong> ${escapeHTML(resp.last_soal.formasi)}`;
            previewBox.style.display='block';
            totalSoalElem.innerText = resp.total_soal;
            if(resp.total_soal>=50) submitBtn.disabled=true;
            form.reset();
            form.querySelector('textarea').focus();
        }
    }).catch(err=>{
        console.error(err);
        submitBtn.disabled=false;
        submitBtn.textContent='Tambah Soal';
        errorBox.innerHTML='Terjadi kesalahan saat mengirim data.';
        errorBox.style.display='block';
    });
});

// Upload massal multi-baris
$('#uploadBtn').click(function(e){
    e.preventDefault();
    const file = $('#fileUpload')[0].files[0];
    if(!file){ alert('Pilih file .txt terlebih dahulu'); return; }

    const formData = new FormData();
    formData.append('soal_file', file);
    formData.append('ajax_file',1);
    formData.append('formasi',formasiVal);

    $.ajax({
        url: window.location.href,
        type:'POST',
        data: formData,
        processData:false,
        contentType:false,
        dataType:'json',
        success:function(resp){
            if(resp.status==='error'){
                alert(resp.msg);
            }else{
                alert(resp.msg);
                totalSoalElem.innerText = resp.total_soal;
            }
        },
        error:function(){ alert('Terjadi kesalahan server'); }
    });
});
</script>
</body>
</html>
