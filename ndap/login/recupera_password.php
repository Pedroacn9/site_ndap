<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8">
  <title>Recuperar Password</title>
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
                    <i class="login__icon fas fa-envelope"></i>
                    <input type="email" class="login__input" name="email" placeholder="Digite seu E-mail" required>
                </div>
                <button class="button login__submit" type="submit">
                    <span class="button__text">Enviar Link de Recuperação</span>
                    <i class="button__icon fas fa-chevron-right"></i>
                </button>
            </form>
            <div class="social-login">
                <h3>Voltar ao Login?</h3>
                <a href="index.php">
                    <h6>Clique aqui</h6>
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


<?php
// Inclua o arquivo onde a função envia_email está definida
require_once 'funcoes.php';

// Conexão com o banco de dados
$db = new mysqli("localhost", "root", "", "ndap");

// Verificar se houve erro na conexão
if ($db->connect_error) {
    die("Erro de conexão com o banco de dados: " . $db->connect_error);
}

// Chamando a função envia_email
if (isset($_POST['email'])) {
    envia_email($_POST['email']);
}
?>

</center>
</body>
</html>
