<?php
include('conexao.php');  // Inclua a conexão com o banco de dados

if (isset($_GET['token'])) {
    $token_confirmacao = $_GET['token'];  // Recupera o token da URL

    // Query para verificar o token e garantir que ele ainda é válido
    $sql = "SELECT * FROM registo_temp WHERE token_confirmacao = ? AND token_expiracao > NOW()";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('s', $token_confirmacao);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Token válido encontrado
            $utilizador = $result->fetch_assoc();

            $nome = $utilizador['nome'];
            $email = $utilizador['email'];
            $password = $utilizador['password'];
            $foto_perfil = $utilizador['foto_perfil'];

            // Move os dados para a tabela 'utilizadores'
            $sql_insert = "INSERT INTO utilizador (nome, email, password, foto_perfil) VALUES (?, ?, ?, ?)";
            if ($stmt_insert = $mysqli->prepare($sql_insert)) {
                $stmt_insert->bind_param('ssss', $nome, $email, $password, $foto_perfil);
                if ($stmt_insert->execute()) {
                    // Após mover os dados, remove o registro temporário
                    $sql_delete = "DELETE FROM registo_temp WHERE token_confirmacao = ?";
                    if ($stmt_delete = $mysqli->prepare($sql_delete)) {
                        $stmt_delete->bind_param('s', $token_confirmacao);
                        $stmt_delete->execute();
                        echo "Sua conta foi confirmada com sucesso!";
                    } else {
                        echo "Erro ao excluir o token de confirmação.";
                    }
                } else {
                    echo "Erro ao confirmar a conta.";
                }
                $stmt_insert->close();
            }
        } else {
            // Token não encontrado ou expirado
            echo "Token não encontrado ou já utilizado. Verifique o link de confirmação.";
        }
        $stmt->close();
    } else {
        echo "Erro ao verificar o token.";
    }
} else {
    echo "Token não fornecido.";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Confirmar Conta</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
<div class="container">
    <div class="screen">
        <div class="screen__content">
            <?php if (!empty($erro)): ?>
                <p style="color: red;"><?php echo $erro; ?></p>
            <?php elseif (!empty($sucesso)): ?>
                <p style="color: green;"><?php echo $sucesso; ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Redireciona para o index.php após 5 segundos
setTimeout(function() {
    window.location.href = "index.php";
}, 0000);
</script>
</body>
</html>
