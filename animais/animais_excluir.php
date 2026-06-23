<?php
session_start();
require_once '../config/conexao.php';

if(!isset($_SESSION['usuario_id'])) {
    header('Location: /unipatas/usuarios/usuarios_login.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id == 0) {
    header('Location: meus_animais.php');
    exit;
}

$sql = "SELECT * FROM animais WHERE id = ? AND id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $_SESSION['usuario_id']);
$stmt->execute();
$result = $stmt->get_result();
$animal = $result->fetch_assoc();

if(!$animal) {
    header('Location: meus_animais.php');
    exit;
}

if($animal['doacao_concluida'] == 1) {
    header('Location: meus_animais.php?erro=nao_pode_excluir');
    exit;
}

$erro = '';
$confirmar = isset($_GET['confirmar']) ? $_GET['confirmar'] : '';

if($confirmar == 'sim') {
    $sql_delete = "DELETE FROM animais WHERE id = ? AND id_usuario = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ii", $id, $_SESSION['usuario_id']);
    
    if($stmt_delete->execute()) {
        header('Location: meus_animais.php?excluido=1');
        exit;
    } else {
        $erro = "Erro ao excluir animal. Tente novamente.";
    }
}

include '../includes/header.php';
?>

<h1>Excluir Animal</h1>

<div class="form-container" style="margin: 0 auto;">
    
    <?php if($erro): ?>
        <div class="alert alert-error"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <div class="alert alert-error" style="background-color: #fff3cd; color: #856404; border-color: #ffeeba;">
        <strong>Atencao</strong> Esta acao e irreversivel.
    </div>
    
    <div class="detalhes-info">
        <p><strong>Animal:</strong> <?php echo htmlspecialchars($animal['nome']); ?></p>
        <p><strong>Categoria:</strong> <?php echo $animal['categoria'] == 'cao' ? 'Cao' : 'Gato'; ?></p>
    </div>
    
    <p style="margin: 20px 0; color: #f44336;">
        Tem certeza que deseja excluir este animal permanentemente?
    </p>
    
    <div style="display: flex; gap: 10px; justify-content: center;">
        <a href="?id=<?php echo $id; ?>&confirmar=sim" class="btn" style="background-color: #f44336;">Sim, excluir</a>
        <a href="meus_animais.php" class="btn" style="background-color: #666;">Cancelar</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>