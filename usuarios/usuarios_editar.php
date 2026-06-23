<?php
session_start();
require_once '../config/conexao.php';

// Verificar se está logado
if(!isset($_SESSION['usuario_id'])) {
    header('Location: usuarios_login.php');
    exit;
}

$id = $_SESSION['usuario_id'];
$erro = '';
$sucesso = '';

// Buscar dados do usuário
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

// Processar alteração
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    // Verificar se e-mail já existe para outro usuário
    $sql_check = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("si", $email, $id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if($result_check->num_rows > 0) {
        $erro = 'Este e-mail já está em uso por outro usuário!';
    } else {
        // Atualizar dados
        if(!empty($senha)) {
            // Se informou nova senha, atualiza tudo
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql_update = "UPDATE usuarios SET nome = ?, endereco = ?, telefone = ?, email = ?, senha = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("sssssi", $nome, $endereco, $telefone, $email, $senha_hash, $id);
        } else {
            // Mantém a senha atual
            $sql_update = "UPDATE usuarios SET nome = ?, endereco = ?, telefone = ?, email = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ssssi", $nome, $endereco, $telefone, $email, $id);
        }
        
        if($stmt_update->execute()) {
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['usuario_email'] = $email;
            $sucesso = 'Dados atualizados com sucesso!';
            
            // Recarregar dados do usuário
            $usuario['nome'] = $nome;
            $usuario['endereco'] = $endereco;
            $usuario['telefone'] = $telefone;
            $usuario['email'] = $email;
        } else {
            $erro = 'Erro ao atualizar. Tente novamente.';
        }
    }
}

include '../includes/header.php';
?>

<h1>Editar Meu Perfil</h1>

<div class="form-container" style="margin: 0 auto;">
    
    <?php if($erro): ?>
        <div class="alert alert-error"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <?php if($sucesso): ?>
        <div class="alert alert-success"><?php echo $sucesso; ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Nome completo *</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>CPF</label>
            <input type="text" value="<?php echo htmlspecialchars($usuario['cpf']); ?>" disabled>
            <small style="color:#666;">CPF não pode ser alterado</small>
        </div>
        
        <div class="form-group">
            <label>Endereço *</label>
            <input type="text" name="endereco" value="<?php echo htmlspecialchars($usuario['endereco']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Telefone</label>
            <input type="text" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone']); ?>">
        </div>
        
        <div class="form-group">
            <label>E-mail *</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Nova senha</label>
            <input type="password" name="senha" placeholder="Deixe em branco para manter a atual">
        </div>
        
        <button type="submit" class="btn">Salvar Alterações</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        <a href="usuarios_listar.php">← Voltar para meu perfil</a>
    </p>
</div>

<?php include '../includes/footer.php'; ?>