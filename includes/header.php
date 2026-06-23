<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniPatas - Castracao Solidaria</title>
    <link rel="stylesheet" href="/unipatas/css/estilo.css">
</head>
<body>

<header>
    <div class="container">
        <div class="logo">
            <a href="/unipatas/index.php">
            <span>UniPatas</span>
            <img src="/unipatas/imagens/patinha.png" alt="Patinha">
            </a>
        </div>
        <nav>
            <a href="/unipatas/index.php">Inicio</a>
            
            <?php if(isset($_SESSION['usuario_id'])): ?>
                <a href="/unipatas/usuarios/usuarios_listar.php">Meu Perfil</a>
                <a href="/unipatas/animais/animais_incluir.php">Cadastrar Animal</a>
                <a href="/unipatas/animais/meus_animais.php">Meus Animais</a>
                <a href="/unipatas/doacoes/minhas_doacoes.php">Minhas Doações</a>
                <a href="/unipatas/usuarios/usuarios_logout.php">Sair (<?php echo $_SESSION['usuario_nome']; ?>)</a>
            <?php else: ?>
                <a href="/unipatas/usuarios/usuarios_login.php">Entrar</a>
                <a href="/unipatas/usuarios/usuarios_incluir.php">Cadastrar</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="container">