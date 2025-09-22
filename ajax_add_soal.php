<?php
require 'db.php';
$response = ['success'=>false,'message'=>''];

if($_SERVER['REQUEST_METHOD']=='POST'){
    $question = $_POST['question'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];
    $formasi = $_POST['formasi'];

    // Hitung jumlah soal per formasi
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM questions WHERE formasi=?");
    $stmt->execute([$formasi]);
    $count = $stmt->fetchColumn();

    if($count>=50){
        $response['message']='Formasi sudah maksimal 50 soal';
    } else {
        $stmt = $pdo->prepare("INSERT INTO questions (question, option_a, option_b, option_c, option_d, correct_option, formasi) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$question,$option_a,$option_b,$option_c,$option_d,$correct_option,$formasi]);
        $response['success']=true;
        $response['message']='Soal berhasil ditambahkan';
    }
}

echo json_encode($response);
