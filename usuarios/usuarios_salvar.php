<?php
session_start();
require_once '../config/conexao.php';

// Verificar se veio do formulário
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: usuarios_incluir.php');
    exit;
}

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$endereco = $_POST['endereco'];
$telefone = $_POST['telefone'] ?? '';
$email = $_POST['email'];
$senha = $_POST['senha'];
$confirmar_senha = $_POST['confirmar_senha'];

// Validações
$erro = '';

if($senha != $confirmar_senha) {
    $erro = 'As senhas não coincidem!';
}

if(strlen($senha) < 6) {
    $erro = 'A senha deve ter no mínimo 6 caracteres!';
}

// Verificar se e-mail já existe
$sql_check = "SELECT id FROM usuarios WHERE email = ? OR cpf = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ss", $email, $cpf);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if($result_check->num_rows > 0) {
    $erro = 'E-mail ou CPF já cadastrado!';
}

// Se houver erro, volta com mensagem
if($erro) {
    header('Location: usuarios_incluir.php?erro=' . urlencode($erro));
    exit;
}

// Cadastrar
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nome, cpf, endereco, telefone, email, senha) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $nome, $cpf, $endereco, $telefone, $email, $senha_hash);

if($stmt->execute()) {
    // Sucesso - redireciona para login
    header('Location: usuarios_login.php?cadastro=sucesso');
    exit;
} else {
    header('Location: usuarios_incluir.php?erro=Erro ao cadastrar. Tente novamente.');
    exit;
}
?>