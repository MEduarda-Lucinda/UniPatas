<?php
session_start();
require_once '../config/conexao.php';

// Verificar se está logado
if(!isset($_SESSION['usuario_id'])) {
    header('Location: usuarios_login.php');
    exit;
}

$id = $_SESSION['usuario_id'];

$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

include '../includes/header.php';
?>

<h1>Meu Perfil</h1>

<div class="form-container" style="margin: 0 auto;">
    <div class="detalhes-info">
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>
        <p><strong>CPF:</strong> <?php echo htmlspecialchars($usuario['cpf']); ?></p>
        <p><strong>Endereço:</strong> <?php echo htmlspecialchars($usuario['endereco']); ?></p>
        <p><strong>Telefone:</strong> <?php echo htmlspecialchars($usuario['telefone']); ?></p>
        <p><strong>E-mail:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
    </div>
    
    <div style="display: flex; gap: 10px; justify-content: center; margin-top: 30px; flex-wrap: wrap;">
        <a href="usuarios_editar.php" class="btn"> Editar Perfil</a>
        <a href="usuarios_excluir.php" class="btn" style="background-color: #f44336;"> Excluir Conta</a>
        <a href="../index.php" class="btn" style="background-color: #666;">← Voltar</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>