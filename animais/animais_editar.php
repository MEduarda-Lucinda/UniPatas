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
    header('Location: meus_animais.php?erro=nao_pode_editar');
    exit;
}

$erro = '';
$sucesso = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $genero = $_POST['genero'];
    $idade = $_POST['idade'];
    $peso = !empty($_POST['peso']) ? $_POST['peso'] : null;
    $descricao = $_POST['descricao'];
    
    $foto_nome = $animal['foto'];
    
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto'];
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $extensao = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        $tamanho_maximo = 2 * 1024 * 1024;
        
        if(in_array($extensao, $extensoes_permitidas) && $foto['size'] <= $tamanho_maximo) {
            $pasta_destino = '../imagens/';
            
            if(!file_exists($pasta_destino)) {
                mkdir($pasta_destino, 0777, true);
            }
            
            $novo_nome = time() . '_' . uniqid() . '.' . $extensao;
            $caminho_destino = $pasta_destino . $novo_nome;
            
            if(move_uploaded_file($foto['tmp_name'], $caminho_destino)) {
                $foto_nome = 'imagens/' . $novo_nome;
                
                if($animal['foto'] && file_exists('../' . $animal['foto'])) {
                    unlink('../' . $animal['foto']);
                }
            }
        }
    }
    
    $sql_update = "UPDATE animais SET nome = ?, foto = ?, categoria = ?, genero = ?, idade = ?, peso = ?, descricao = ? WHERE id = ? AND id_usuario = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssiisii", $nome, $foto_nome, $categoria, $genero, $idade, $peso, $descricao, $id, $_SESSION['usuario_id']);
    
    if($stmt_update->execute()) {
        $sucesso = 'Animal atualizado com sucesso.';
        
        $sql_refresh = "SELECT * FROM animais WHERE id = ?";
        $stmt_refresh = $conn->prepare($sql_refresh);
        $stmt_refresh->bind_param("i", $id);
        $stmt_refresh->execute();
        $result_refresh = $stmt_refresh->get_result();
        $animal = $result_refresh->fetch_assoc();
    } else {
        $erro = 'Erro ao atualizar. Tente novamente.';
    }
}

include '../includes/header.php';
?>

<h1>Editar Animal</h1>

<div class="form-container" style="margin: 0 auto;">
    
    <?php if($erro): ?>
        <div class="alert alert-error"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <?php if($sucesso): ?>
        <div class="alert alert-success"><?php echo $sucesso; ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nome do animal</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($animal['nome']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Categoria</label>
            <select name="categoria" required>
                <option value="cao" <?php echo $animal['categoria'] == 'cao' ? 'selected' : ''; ?>>Cao</option>
                <option value="gato" <?php echo $animal['categoria'] == 'gato' ? 'selected' : ''; ?>>Gato</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Genero</label>
            <select name="genero" required>
                <option value="macho" <?php echo $animal['genero'] == 'macho' ? 'selected' : ''; ?>>Macho</option>
                <option value="femea" <?php echo $animal['genero'] == 'femea' ? 'selected' : ''; ?>>Femea</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Idade (anos)</label>
            <input type="number" name="idade" value="<?php echo $animal['idade']; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Peso (kg)</label>
            <input type="number" step="0.01" name="peso" value="<?php echo $animal['peso']; ?>">
        </div>
        
        <div class="form-group">
            <label>Descricao</label>
            <textarea name="descricao" rows="5"><?php echo htmlspecialchars($animal['descricao']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Foto atual</label>
            <?php if($animal['foto']): ?>
                <p><img src="/unipatas/<?php echo $animal['foto']; ?>" style="max-width: 150px; max-height: 150px;"></p>
            <?php else: ?>
                <p>Nenhuma foto cadastrada.</p>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Nova foto (opcional)</label>
            <input type="file" name="foto" accept="image/*">
            <small style="color: #666;">Deixe em branco para manter a foto atual.</small>
        </div>
        
        <button type="submit" class="btn">Salvar Alteracoes</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        <a href="meus_animais.php">Voltar para meus animais</a>
    </p>
</div>

<?php include '../includes/footer.php'; ?>