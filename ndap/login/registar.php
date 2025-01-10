<?php
// Incluindo os arquivos necessários
include('conexao.php');
include('confirmar_email.php'); // Função envia_email

session_start(); // Inicia a sessão

$erro = ''; // Mensagem de erro (caso ocorra algum erro)

if (isset($_POST['nome'], $_POST['email'], $_POST['password'], $_POST['confirma_password'])) {
    // Validação dos campos do formulário
    if (empty($_POST['nome'])) {
        $erro = "Preencha o seu nome";
    } elseif (empty($_POST['email'])) {
        $erro = "Preencha o seu e-mail";
    } elseif (empty($_POST['password'])) {
        $erro = "Preencha a sua senha";
    } elseif (empty($_POST['confirma_password'])) {
        $erro = "Preencha a confirmação de senha";
    } elseif ($_POST['password'] !== $_POST['confirma_password']) {
        $erro = "As senhas não coincidem";
    } else {
        // Dados recebidos e preparados para inserção no banco
        $nome = $mysqli->real_escape_string($_POST['nome']);
        $email = $mysqli->real_escape_string($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Criptografando a senha

        // Gerando token de confirmação e expirando em 1 hora
        $token_confirmacao = bin2hex(random_bytes(16));
        $token_expiracao = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Verificando se a foto de perfil foi enviada
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $foto_perfil = $_FILES['foto_perfil'];
            $caminho_foto = '/site_ndap/ndap/login/Foto de Perfil/' . basename($foto_perfil['name']);
            if (!move_uploaded_file($foto_perfil['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $caminho_foto)) {
                $erro = "Erro ao mover a foto de perfil";
            }
        } else {
            $caminho_foto = null; // Caso a foto não seja enviada
        }

        // Inserir os dados no banco de dados, se não houver erro
        if (empty($erro)) {
            $sql = "INSERT INTO registo_temp (nome, email, password, foto_perfil, token_confirmacao, token_expiracao) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param('ssssss', $nome, $email, $password, $caminho_foto, $token_confirmacao, $token_expiracao);
                if ($stmt->execute()) {
                    // Envia o e-mail de confirmação
                    envia_email($email, $nome, $token_confirmacao);
                    
                } else {
                    $erro = "Erro ao registrar: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $erro = "Erro ao preparar a inserção: " . $mysqli->error;
            }
        }
    }
}
?>

<!-- HTML do formulário de registro -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Registrar</title>
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css'>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
<div class="container">
    <div class="screen">
        <div class="screen__content">
            <form class="login" method="POST" enctype="multipart/form-data">
                <div class="login__field">
                    <i class="login__icon fas fa-user"></i>
                    <input type="text" class="login__input" name="nome" placeholder="Nome" required>
                </div>
                <div class="login__field">
                    <i class="login__icon fas fa-envelope"></i>
                    <input type="email" class="login__input" name="email" placeholder="Email" required>
                </div>
                <div class="login__field">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" class="login__input" name="password" placeholder="Senha" required>
                </div>
                <div class="login__field">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" class="login__input" name="confirma_password" placeholder="Confirmar Senha" required>
                </div>
                <div class="login__field">
                    <i class="login__icon fas fa-image"></i>
                    <input type="file" class="login__input" name="foto_perfil" accept="image/*">
                    <p style="font-size: 12px;">Se quiser Foto de Perfil:</p>
                </div>
                <button class="button login__submit" type="submit">
                    <span class="button__text">Registrar</span>
                    <i class="button__icon fas fa-chevron-right"></i>
                </button>
                <?php if (!empty($erro)): ?>
                    <p style="color: red;"><?php echo $erro; ?></p>
                <?php endif; ?>             
            </form>
            <div class="social-login">
                <h3>Já tem uma conta?</h3>
                <a href="/site_ndap/ndap/login/index.php">
                    <h6>Login Aqui</h6>
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
