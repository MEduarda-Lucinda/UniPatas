<?php
session_start();
require_once '../config/conexao.php';

if(!isset($_SESSION['usuario_id'])) {
    header('Location: /unipatas/usuarios/usuarios_login.php');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

$sql = "SELECT * FROM animais WHERE id_usuario = ? ORDER BY doacao_concluida ASC, id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

include '../includes/header.php';
?>

<h1>Meus Animais</h1>

<div class="grid">
    <?php if($result->num_rows > 0): ?>
        <?php while($animal = $result->fetch_assoc()): ?>
        <div class="card">
            <div class="card-content">
                <?php if($animal['foto']): ?>
                <p><img src="/unipatas/<?php echo $animal['foto']; ?>" style="max-width: 100%; height: 150px; object-fit: cover; border-radius: 10px;"></p>
                <?php endif; ?>
    
                <h3><?php echo htmlspecialchars($animal['nome']); ?></h3>
                <p><strong>Categoria:</strong> <?php echo $animal['categoria'] == 'cao' ? 'Cao' : 'Gato'; ?></p>
                <p><strong>Genero:</strong> <?php echo $animal['genero'] == 'macho' ? 'Macho' : 'Femea'; ?></p>
                <p><strong>Idade:</strong> <?php echo $animal['idade'] . ' anos'; ?></p>
                
                <?php if($animal['doacao_concluida'] == 1): ?>
                    <p style="color: green; margin-top: 10px;"><strong>Doação concluída</strong> - Animal já recebeu ajuda</p>
                <?php else: ?>
                    <p style="color: orange; margin-top: 10px;"><strong>Aguardando doação</strong></p>
                <?php endif; ?>
                
                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    <a href="animais_editar.php?id=<?php echo $animal['id']; ?>" class="btn">Editar</a>
                    <?php if($animal['doacao_concluida'] == 0): ?>
                        <a href="animais_excluir.php?id=<?php echo $animal['id']; ?>" class="btn" style="background-color: #f44336;">Excluir</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Voce ainda nao cadastrou nenhum animal.</p>
        <p><a href="animais_incluir.php" class="btn">Cadastrar Animal</a></p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>