<?php
session_start();
require_once '../config/conexao.php';

if(!isset($_SESSION['usuario_id'])) {
    header('Location: /unipatas/usuarios/usuarios_login.php');
    exit;
}

$id_doador = $_SESSION['usuario_id'];

$sql = "SELECT d.*, a.nome as animal_nome, a.categoria, a.genero, a.idade, a.foto, u.nome as tutor_nome 
        FROM doacoes d
        JOIN animais a ON d.id_animal = a.id
        JOIN usuarios u ON a.id_usuario = u.id
        WHERE d.id_doador = ?
        ORDER BY d.id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_doador);
$stmt->execute();
$result = $stmt->get_result();

include '../includes/header.php';
?>

<h1>Minhas Doações</h1>

<div class="grid">
    <?php if($result->num_rows > 0): ?>
        <?php while($doacao = $result->fetch_assoc()): ?>
        <div class="card">
            <div class="card-content">
                <?php if($doacao['foto']): ?>
                <p><img src="/unipatas/<?php echo $doacao['foto']; ?>" style="max-width: 100%; height: 150px; object-fit: cover; border-radius: 10px;"></p>
                <?php endif; ?>
    
                <h3><?php echo htmlspecialchars($doacao['animal_nome']); ?></h3>
                <p><strong>Categoria:</strong> <?php echo $doacao['categoria'] == 'cao' ? 'Cao' : 'Gato'; ?></p>
                <p><strong>Genero:</strong> <?php echo $doacao['genero'] == 'macho' ? 'Macho' : 'Femea'; ?></p>
                <p><strong>Idade:</strong> <?php echo $doacao['idade'] . ' anos'; ?></p>
                <p><strong>Tutor:</strong> <?php echo htmlspecialchars($doacao['tutor_nome']); ?></p>
                <p><strong>Valor doado:</strong> R$ <?php echo number_format($doacao['valor'], 2, ',', '.'); ?></p>
                
                <p style="color: green; margin-top: 10px;"><strong>Doação concluída</strong> - Animal já recebeu ajuda</p>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Voce ainda nao fez nenhuma doação.</p>
        <p><a href="/unipatas/index.php" class="btn">Ver animais que precisam de ajuda</a></p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>