<?php
include('protect.php');
include('conexao.php');

// Buscando a foto de perfil do usuário logado
$id = $_SESSION['id'];
$sql = "SELECT foto_perfil FROM utilizador WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$utilizador = $result->fetch_assoc();
$foto_perfil = $utilizador['foto_perfil'];

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Painel</title>
</head>
<body>
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <form class="login" method="POST">
                    Bem vindo ao Painel, <?php echo htmlspecialchars($_SESSION['nome']); ?>.<br>
                    <!-- Exibir a imagem de perfil -->
                    <?php if (!empty($foto_perfil)): ?>
                        <img src="/ndap/login/Foto de Perfil/<?php echo htmlspecialchars(basename($foto_perfil)); ?>" class="profile-image" alt="Foto de Perfil">
                    <?php else: ?>
                        <p>Sem foto de perfil.</p>
                    <?php endif; ?>

                    <a class="button login__submit" href="/site_ndap/ndap/ndap_all.html">
                        <span class="button__text" href="/site_ndap/ndap/ndap_all.html">Entrar no Site</span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </a>

                    <a class="button login__submit" href="logout.php">
                        <span class="button__text" href="logout.php">Sair</span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </a>
                </form>
            </div>
            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape4"></span>
                <span class="screen__background__shape screen__background__shape3"></span>       
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>      
        </div>
    </div>
</body>
</html>
