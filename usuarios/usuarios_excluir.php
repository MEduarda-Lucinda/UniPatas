<?php
session_start();
require_once '../config/conexao.php';

// Verificar se está logado
if(!isset($_SESSION['usuario_id'])) {
    header('Location: usuarios_login.php');
    exit;
}

$id = $_SESSION['usuario_id'];

// Buscar dados do usuário para confirmar
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if(!$usuario) {
    header('Location: ../index.php');
    exit;
}

$erro = '';
$confirmar = isset($_POST['confirmar']);

if($_SERVER['REQUEST_METHOD'] == 'POST' && $confirmar) {
    // Verificar se o usuário tem animais cadastrados
    $sql_check = "SELECT COUNT(*) as total FROM animais WHERE id_usuario = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $total_animais = $result_check->fetch_assoc()['total'];
    
    if($total_animais > 0) {
        $erro = "Você não pode excluir sua conta pois possui $total_animais animal(is) cadastrado(s). Primeiro remova seus animais.";
    } else {
        // Verificar se o usuário fez doações
        $sql_check2 = "SELECT COUNT(*) as total FROM doacoes WHERE id_doador = ?";
        $stmt_check2 = $conn->prepare($sql_check2);
        $stmt_check2->bind_param("i", $id);
        $stmt_check2->execute();
        $result_check2 = $stmt_check2->get_result();
        $total_doacoes = $result_check2->fetch_assoc()['total'];
        
        if($total_doacoes > 0) {
            $erro = "Você não pode excluir sua conta pois realizou $total_doacoes doação(ões).";
        } else {
            // Excluir usuário
            $sql_delete = "DELETE FROM usuarios WHERE id = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("i", $id);
            
            if($stmt_delete->execute()) {
                session_destroy();
                header('Location: ../index.php?excluido=sucesso');
                exit;
            } else {
                $erro = "Erro ao excluir conta. Tente novamente.";
            }
        }
    }
}

include '../includes/header.php';
?>

<h1>Excluir Minha Conta</h1>

<div class="form-container" style="margin: 0 auto;">
    
    <?php if($erro): ?>
        <div class="alert alert-error"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <div class="alert alert-error" style="background-color: #fff3cd; color: #856404; border-color: #ffeeba;">
        <strong>⚠️ Atenção!</strong> Esta ação é irreversível.
    </div>
    
    <div class="detalhes-info">
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>
        <p><strong>E-mail:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
    </div>
    
    <p style="margin: 20px 0; color: #f44336;">
        Tem certeza que deseja excluir sua conta permanentemente?
    </p>
    
    <form method="POST">
        <input type="hidden" name="confirmar" value="1">
        
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="btn btn-danger" style="background-color: #f44336;">✅ Sim, excluir minha conta</button>
            <a href="usuarios_listar.php" class="btn" style="background-color: #666;">Cancelar</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>