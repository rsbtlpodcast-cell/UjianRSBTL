<?php
session_start();
require 'db.php';

// Daftar formasi
$formasi_list = ['Dokter','Radiologi','Rekam Medis','Perawat','ATLM'];
$selected_formasi = $_GET['formasi'] ?? $formasi_list[0];

// Fungsi hitung total soal per formasi
function getTotalSoal($pdo,$formasi){
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM questions WHERE formasi=?");
    $stmt->execute([$formasi]);
    return $stmt->fetchColumn();
}

// =========================
// AJAX Tambah Soal Manual
// =========================
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['ajax_add'])){
    $question = trim($_POST['question'] ?? '');
    $option_a = trim($_POST['option_a'] ?? '');
    $option_b = trim($_POST['option_b'] ?? '');
    $option_c = trim($_POST['option_c'] ?? '');
    $option_d = trim($_POST['option_d'] ?? '');
    $correct_option = strtoupper(trim($_POST['correct_option'] ?? ''));
    $formasi = trim($_POST['formasi'] ?? '');

    if(!$question || !$option_a || !$option_b || !$option_c || !$option_d || !$correct_option || !$formasi){
        echo json_encode(['status'=>'error','msg'=>'Semua field wajib diisi.']); exit;
    }

    if(getTotalSoal($pdo,$formasi)>=50){
        echo json_encode(['status'=>'error','msg'=>'Jumlah soal sudah mencapai 50.']); exit;
    }

    $stmt = $pdo->prepare("INSERT INTO questions (question, option_a, option_b, option_c, option_d, correct_option, formasi) VALUES (?,?,?,?,?,?,?)");
    if($stmt->execute([$question,$option_a,$option_b,$option_c,$option_d,$correct_option,$formasi])){
        $last_soal = [
            'question'=>$question,
            'option_a'=>$option_a,
            'option_b'=>$option_b,
            'option_c'=>$option_c,
            'option_d'=>$option_d,
            'correct_option'=>$correct_option,
            'formasi'=>$formasi
        ];
        echo json_encode([
            'status'=>'success',
            'last_soal'=>$last_soal,
            'total_soal'=>getTotalSoal($pdo,$formasi)
        ]);
        exit;
    } else {
        echo json_encode(['status'=>'error','msg'=>'Gagal menambahkan soal ke database.']); exit;
    }
}

// =========================
// Upload File TXT
// =========================
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_FILES['file_soal'])){
    $formasi = $_POST['formasi'] ?? $selected_formasi;
    $file = $_FILES['file_soal']['tmp_name'];
    $content = file_get_contents($file);
    $content = str_replace("\r","",$content);
    $content = "\n".$content;

    // Pisahkan berdasarkan nomor soal
    $blocks = preg_split("/\n\d+\.\s*/",$content);
    $soal_array = [];

    foreach($blocks as $block){
        $block = trim($block);
        if(!$block) continue;

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
            elseif(preg_match('/^Jawaban\s*[:]\s*(\w)/i',$line,$m)) $correct_option=strtoupper($m[1]);
            else $soal_text .= ($soal_text?" ":"").$line;
        }

        if($soal_text && count(array_filter($options))===4 && $correct_option){
            $soal_array[] = [
                'question'=>$soal_text,
                'option_a'=>$options['A'],
                'option_b'=>$options['B'],
                'option_c'=>$options['C'],
                'option_d'=>$options['D'],
                'correct_option'=>$correct_option
            ];
        }
    }

    // Cek total soal max 50
    $current_total = getTotalSoal($pdo,$formasi);
    if($current_total + count($soal_array) > 50){
        $_SESSION['upload_msg'] = "Jumlah soal melebihi 50. Saat ini: $current_total, File berisi: ".count($soal_array);
        header("Location: ".$_SERVER['PHP_SELF']."?formasi=".urlencode($formasi));
        exit;
    }

    $added = 0;
    foreach($soal_array as $s){
        $stmt = $pdo->prepare("INSERT INTO questions (question, option_a, option_b, option_c, option_d, correct_option, formasi) VALUES (?,?,?,?,?,?,?)");
        if($stmt->execute([$s['question'],$s['option_a'],$s['option_b'],$s['option_c'],$s['option_d'],$s['correct_option'],$formasi])) $added++;
    }

    $_SESSION['upload_msg'] = "Berhasil menambahkan $added soal untuk formasi $formasi";
    header("Location: ".$_SERVER['PHP_SELF']."?formasi=".urlencode($formasi));
    exit;
}
?>
