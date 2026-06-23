<?php
session_start();

// Se já estiver logado, vai para o início
if(isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$erro = '';

// Processar login
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../config/conexao.php';
    
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        if(password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            header('Location: ../index.php');
            exit;
        } else {
            $erro = 'Senha incorreta!';
        }
    } else {
        $erro = 'E-mail não cadastrado!';
    }
}
?>

<?php include '../includes/header_simples.php'; ?>

<div class="form-container">
    <h2>Entrar no UniPatas</h2>
    
    <?php if($erro): ?>
        <div class="alert alert-error"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="senha" required>
        </div>
        
        <button type="submit" class="btn">Entrar</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        Não tem conta? <a href="usuarios_incluir.php">Cadastre-se</a>
    </p>
</div>

<?php include '../includes/footer.php'; ?>