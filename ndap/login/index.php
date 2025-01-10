<?php
session_start();
include('conexao.php');

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'], $_POST['password'])) {
        $login = trim($_POST['login']);
        $password = trim($_POST['password']);

        if (empty($login)) {
            $erro = "Preencha seu e-mail ou nome de utilizador";
        } elseif (empty($password)) {
            $erro = "Preencha sua password";
        } else {
            $login = $mysqli->real_escape_string($login);

            // Verifica se o login é um email ou nome de utilizador
            if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
                $campo = 'email';
            } else {
                $campo = 'nome';
            }

            $sql = "SELECT * FROM utilizador WHERE $campo = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('s', $login);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $utilizador = $result->fetch_assoc();

                // Verifica a password usando password_verify
                if (password_verify($password, $utilizador['password'])) {
                    $_SESSION['id'] = $utilizador['id'];
                    $_SESSION['nome'] = $utilizador['nome'];
                    $_SESSION['email'] = $utilizador['email'];
                    
                    // Busca a foto de perfil do usuário e armazena na sessão
                    $_SESSION['foto_perfil'] = $utilizador['foto_perfil'];

                    header("Location:painel.php");
                    exit();
                } else {
                    $erro = "Password incorreta.";
                }
            } else {
                $erro = "Utilizador não encontrado.";
            }

            $stmt->close();
        }
    }
}
?>
    
<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css'>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
<div class="container">
    <div class="screen">
        <div class="screen__content">
            <form class="login" method="POST">
                <div class="login__field">
                    <i class="login__icon fas fa-user"></i>
                    <input type="text" class="login__input" name="login" placeholder="Nome de Utilizador / Email" required>
                </div>
                <div class="login__field">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" class="login__input" name="password" placeholder="password" required>
                </div>
                <button class="button login__submit" type="submit">
                    <span class="button__text">Login Now</span>
                    <i class="button__icon fas fa-chevron-right"></i>
                </button>
                <?php if (!empty($erro)):?>
                    <p style="color: red;"><?php echo $erro;?></p>
                <?php endif;?>             
            </form>
            <div class="social-login">
                <h3>Não tem conta?</h3>
                <a href="/site_ndap/ndap/login/registar.php">
                    <h6>Registe-se aqui</h6>
                </a>
            </div>
            <div class="social-recuperar">
                <h3>Não Sabe Sua Password?</h3>
                <a href="/site_ndap/ndap/login/recupera_password.php">
                    <h6>Recuperar Password</h6>
                </a>
            </div>
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
