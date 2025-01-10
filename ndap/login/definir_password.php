<?php
include('conexao.php');
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8">
  <title>Redefinir Password</title>
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css'>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
<div class="container">
    <div class="screen">
        <div class="screen__content">
            <form class="login" method="POST" action="">
                <h2 class="login__title">Redefinir Password</h2>
                <div class="login__field">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" class="login__input" name="password" placeholder="Nova Password" required>
                </div>
                <div class="login__field">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" class="login__input" name="password2" placeholder="Confirmar Password" required>
                </div>
                <button class="button login__submit" name="login" type="submit">
                    <span class="button__text">Redefinir</span>
                    <i class="button__icon fas fa-chevron-right"></i>
                </button>
            </form>
            <div class="social-login">
                <a href="index.php">
                    <h6>Voltar para o Login</h6>
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
// Lógica de redefinição de senha
if (isset($_POST['login'])) {
    // Verifica se as senhas são iguais
    if ($_POST['password'] != $_POST['password2']) {
        echo "<h2 style='color: red;'>As senhas não coincidem</h2>";
    } else {
        // Conexão com o banco
        if (!isset($_GET['token']) || empty($_GET['token'])) {
            die("<h2 style='color: red;'>Token não fornecido. Verifique o link enviado para o seu e-mail.</h2>");
        }

        $token = $_GET['token'];

        // Verifica se o token é válido
        $stmt = $mysqli->prepare("SELECT * FROM utilizador WHERE token_redefinicao = ? AND token_expiracao > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Atualiza a senha
            $stmt_update = $mysqli->prepare("UPDATE utilizador SET password = ?, token_redefinicao = NULL, token_expiracao = NULL WHERE token_redefinicao = ?");
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt_update->bind_param("ss", $hashed_password, $token);
            $stmt_update->execute();

            if ($stmt_update->affected_rows > 0) {
                echo "<h2 style='color: green;'>Senha alterada com sucesso!</h2>";
                header("Location: index.php");
                exit();
            } else {
                echo "<h2 style='color: red;'>Erro ao atualizar a senha. Tente novamente.</h2>";
            }
        } else {
            echo "<h2 style='color: red;'>Token inválido ou expirado. Solicite uma nova redefinição de senha.</h2>";
        }

        $stmt->close();
        $mysqli->close();
    }
}
?>
</body>
</html>
