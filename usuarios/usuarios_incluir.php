<?php
session_start();

// Se já estiver logado, redireciona
if(isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$erro = '';
?>

<?php include '../includes/header_simples.php'; ?>

<div class="form-container">
    <h2>Cadastro de Usuário</h2>
    
    <?php if($erro): ?>
        <div class="alert alert-error"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <form action="usuarios_salvar.php" method="POST">
        <div class="form-group">
            <label>Nome completo *</label>
            <input type="text" name="nome" required>
        </div>
        
        <div class="form-group">
            <label>CPF *</label>
            <input type="text" name="cpf" required placeholder="000.000.000-00">
        </div>
        
        <div class="form-group">
            <label>Endereço *</label>
            <input type="text" name="endereco" required>
        </div>
        
        <div class="form-group">
            <label>Telefone</label>
            <input type="text" name="telefone" placeholder="(00) 00000-0000">
        </div>
        
        <div class="form-group">
            <label>E-mail *</label>
            <input type="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label>Senha *</label>
            <input type="password" name="senha" required>
        </div>
        
        <div class="form-group">
            <label>Confirmar senha *</label>
            <input type="password" name="confirmar_senha" required>
        </div>
        
        <button type="submit" class="btn">Cadastrar</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        Já tem conta? <a href="usuarios_login.php">Faça login</a>
    </p>
</div>

<?php include '../includes/footer.php'; ?>