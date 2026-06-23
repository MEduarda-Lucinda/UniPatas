<?php
session_start();
require_once '../config/conexao.php';

if(!isset($_SESSION['usuario_id'])) {
    header('Location: /unipatas/usuarios/usuarios_login.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: animais_incluir.php');
    exit;
}

$nome = $_POST['nome'];
$categoria = $_POST['categoria'];
$genero = $_POST['genero'];
$idade = $_POST['idade'];
$peso = !empty($_POST['peso']) ? $_POST['peso'] : null;
$descricao = $_POST['descricao'];
$id_usuario = $_SESSION['usuario_id'];

$foto_nome = null;

// Processar upload da foto
if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $foto = $_FILES['foto'];
    $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
    $extensao = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
    $tamanho_maximo = 2 * 1024 * 1024; // 2MB
    
    if(in_array($extensao, $extensoes_permitidas)) {
        if($foto['size'] <= $tamanho_maximo) {
            $pasta_destino = '../imagens/';
            
            if(!file_exists($pasta_destino)) {
                mkdir($pasta_destino, 0777, true);
            }
            
            $foto_nome = time() . '_' . uniqid() . '.' . $extensao;
            $caminho_destino = $pasta_destino . $foto_nome;
            
            if(move_uploaded_file($foto['tmp_name'], $caminho_destino)) {
                $foto_nome = 'imagens/' . $foto_nome;
            } else {
                header('Location: animais_incluir.php?erro=upload');
                exit;
            }
        } else {
            header('Location: animais_incluir.php?erro=tamanho');
            exit;
        }
    } else {
        header('Location: animais_incluir.php?erro=extensao');
        exit;
    }
}

$sql = "INSERT INTO animais (nome, foto, categoria, genero, idade, peso, descricao, id_usuario) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssiisi", $nome, $foto_nome, $categoria, $genero, $idade, $peso, $descricao, $id_usuario);

if($stmt->execute()) {
    header('Location: meus_animais.php?sucesso=1');
    exit;
} else {
    header('Location: animais_incluir.php?erro=banco');
    exit;
}
?>