<?php
session_start();
require_once '../config/conexao.php';
$erro = '';
if(isset($_GET['erro'])) {
    switch($_GET['erro']) {
        case 'upload':
            $erro = 'Erro ao fazer upload da imagem. Tente novamente.';
            break;
        case 'tamanho':
            $erro = 'A imagem excede o tamanho maximo de 2MB.';
            break;
        case 'extensao':
            $erro = 'Formato de imagem nao permitido. Use JPG, PNG ou GIF.';
            break;
        case 'banco':
            $erro = 'Erro ao cadastrar. Tente novamente.';
            break;
    }
}

if(!isset($_SESSION['usuario_id'])) {
    header('Location: /unipatas/usuarios/usuarios_login.php');
    exit;
}

include '../includes/header.php';
?>

<h1><center>Cadastrar Animal</center></h1>

<div class="form-container" style="margin: 0 auto;">
    <?php if($erro): ?>
    <div class="alert alert-error"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <form action="animais_salvar.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nome do animal</label>
            <input type="text" name="nome" required>
        </div>
        
        <div class="form-group">
            <label>Categoria</label>
            <select name="categoria" required>
                <option value="cao">Cao</option>
                <option value="gato">Gato</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Gênero</label>
            <select name="genero" required>
                <option value="macho">Macho</option>
                <option value="femea">Fêmea</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Idade (anos)</label>
            <input type="number" name="idade" required>
        </div>
        
        <div class="form-group">
            <label>Peso (kg)</label>
            <input type="number" step="0.01" name="peso">
        </div>
        
        <div class="form-group">
            <label>Descrição</label>
            <textarea name="descricao" rows="5"></textarea>
        </div>
        
        <div class="form-group">
            <label>Foto do animal</label>
            <input type="file" name="foto" accept="image/*">
            <small style="color: #666;">Formatos permitidos: JPG, PNG, GIF. Maximo 2MB.</small>
        </div>
        
        <button type="submit" class="btn">Cadastrar Animal</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>