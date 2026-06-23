<?php
session_start();
require_once '../config/conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id == 0) {
    header('Location: animais_listar.php');
    exit;
}

$sql = "SELECT a.*, u.nome as tutor_nome, u.id as tutor_id 
        FROM animais a 
        JOIN usuarios u ON a.id_usuario = u.id 
        WHERE a.id = ? AND a.doacao_concluida = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$animal = $result->fetch_assoc();

if(!$animal) {
    header('Location: animais_listar.php');
    exit;
}

$mensagem = '';
$tipo_mensagem = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['usuario_id'])) {
    $id_doador = $_SESSION['usuario_id'];
    $valor = 150.00;
    
    $conn->begin_transaction();
    
    try {
        $check = $conn->prepare("SELECT * FROM doacoes WHERE id_animal = ? AND id_doador = ?");
        $check->bind_param("ii", $id, $id_doador);
        $check->execute();
        $result_check = $check->get_result();
        
        if($result_check->num_rows > 0) {
            throw new Exception('Voce ja contribuiu para este animal.');
        }
        
        $check_animal = $conn->prepare("SELECT doacao_concluida FROM animais WHERE id = ? FOR UPDATE");
        $check_animal->bind_param("i", $id);
        $check_animal->execute();
        $result_animal = $check_animal->get_result();
        $animal_status = $result_animal->fetch_assoc();
        
        if($animal_status['doacao_concluida'] == 1) {
            throw new Exception('Este animal ja recebeu doacao e nao esta mais disponivel.');
        }
        
        $insert = $conn->prepare("INSERT INTO doacoes (id_animal, id_doador, valor) VALUES (?, ?, ?)");
        $insert->bind_param("iid", $id, $id_doador, $valor);
        $insert->execute();
        
        $update = $conn->prepare("UPDATE animais SET doacao_concluida = 1 WHERE id = ?");
        $update->bind_param("i", $id);
        $update->execute();
        
        $conn->commit();
        
        header('Location: /unipatas/index.php?doacao=sucesso');
        exit;
        
    } catch(Exception $e) {
        $conn->rollback();
        $mensagem = $e->getMessage();
        $tipo_mensagem = 'error';
    }
}

include '../includes/header.php';
?>

<div class="form-container">
    <h1><?php echo htmlspecialchars($animal['nome']); ?></h1>
    
    <?php if($mensagem): ?>
        <div class="alert alert-<?php echo $tipo_mensagem; ?>"><?php echo $mensagem; ?></div>
    <?php endif; ?>
    
    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        
        <div style="flex: 1; min-width: 200px; text-align: center;">
            <?php if($animal['foto']): ?>
                <img src="/unipatas/<?php echo $animal['foto']; ?>" style="width: 100%; max-width: 300px; border-radius: 10px;">
            <?php else: ?>
                <div style="width: 100%; max-width: 300px; height: 200px; background-color: #ddd; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #666; margin: 0 auto;">
                    Sem foto
                </div>
            <?php endif; ?>
        </div>
        
        <div style="flex: 2;">
            <div class="detalhes-info">
                <p><strong>Categoria:</strong> <?php echo $animal['categoria'] == 'cao' ? 'Cao' : 'Gato'; ?></p>
                <p><strong>Genero:</strong> <?php echo $animal['genero'] == 'macho' ? 'Macho' : 'Femea'; ?></p>
                <p><strong>Idade:</strong> <?php echo $animal['idade'] . ' anos'; ?></p>
                <?php if($animal['peso']): ?>
                    <p><strong>Peso:</strong> <?php echo $animal['peso'] . ' kg'; ?></p>
                <?php endif; ?>
                <p><strong>Tutor:</strong> <?php echo htmlspecialchars($animal['tutor_nome']); ?></p>
                <p><strong>Descricao:</strong></p>
                <p><?php echo nl2br(htmlspecialchars($animal['descricao'])); ?></p>
            </div>
        </div>
        
    </div>
    
    <?php if(isset($_SESSION['usuario_id'])): ?>
        <?php if($_SESSION['usuario_id'] != $animal['tutor_id']): ?>
            <form method="POST" style="margin-top: 30px;">
                <button type="submit" class="btn" style="width: 100%;">Quero ajudar com R$ 150,00</button>
            </form>
        <?php else: ?>
            <div class="alert alert-info" style="text-align: center;">
                Este e seu animal. Aguarde um doador.
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div style="text-align: center; margin-top: 30px;">
            <a href="/unipatas/usuarios/usuarios_login.php" class="btn">Faca login para ajudar</a>
        </div>
    <?php endif; ?>
    
    <div style="margin-top: 30px; text-align: center;">
        <a href="animais_listar.php" class="btn" style="background-color: #666;">Voltar para lista</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>