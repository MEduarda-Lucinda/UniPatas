<?php
session_start();
require_once '../config/conexao.php';
include '../includes/header.php';

$sql = "SELECT a.*, u.nome as tutor_nome 
        FROM animais a 
        JOIN usuarios u ON a.id_usuario = u.id 
        WHERE a.doacao_concluida = 0
        ORDER BY a.id DESC";
$result = $conn->query($sql);
?>

<h1>Animais Disponiveis</h1>
<p>Conheca os animais que precisam de castracao solidaria.</p>

<div class="grid">
    <?php if($result && $result->num_rows > 0): ?>
        <?php while($animal = $result->fetch_assoc()): ?>
        <div class="card">
            <div class="card-content">
                <?php if($animal['foto']): ?>
                    <p><img src="/unipatas/<?php echo $animal['foto']; ?>" style="width: 100%; height: 180px; object-fit: cover; border-radius: 10px; margin-bottom: 15px;"></p>
                <?php else: ?>
                    <p><img src="/unipatas/imagens/sem-foto.jpg" style="width: 100%; height: 180px; object-fit: cover; border-radius: 10px; margin-bottom: 15px; background-color: #ddd;"></p>
                <?php endif; ?>
                
                <h3><?php echo htmlspecialchars($animal['nome']); ?></h3>
                <p><strong>Categoria:</strong> <?php echo $animal['categoria'] == 'cao' ? 'Cao' : 'Gato'; ?></p>
                <p><strong>Genero:</strong> <?php echo $animal['genero'] == 'macho' ? 'Macho' : 'Femea'; ?></p>
                <p><strong>Idade:</strong> <?php echo $animal['idade'] . ' anos'; ?></p>
                <p><strong>Tutor:</strong> <?php echo htmlspecialchars($animal['tutor_nome']); ?></p>
                <a href="animais_detalhes.php?id=<?php echo $animal['id']; ?>" class="btn">Ver detalhes</a>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Nenhum animal disponivel no momento.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>