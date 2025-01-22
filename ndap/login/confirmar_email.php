<?php
include('conexao.php'); // Certifique-se de que a variável $mysqli está definida aqui.

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function envia_email($destino, $nome) {
    global $mysqli;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Mailer = "smtp";
        $mail->SMTPDebug  = SMTP::DEBUG_OFF; // Em produção use DEBUG_OFF
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;
        $mail->Host       = "smtp.gmail.com";
        $mail->Username   = "ppetersoft@gmail.com";
        $mail->Password   = "glmf cmbd yknf dtba";

        $mail->isHTML(true);
        $mail->addAddress($destino, $nome);
        $mail->setFrom("ppetersoft@gmail.com", "NDAP");
        $mail->Subject = "Confirmação de E-mail";

        // Gera o token e define expiração
        $token = bin2hex(random_bytes(16)); // Gera o token
        $expiracao = date("Y-m-d H:i:s", strtotime("+1 hour")); // Define expiração em 1 hora

        // Atualiza ou insere o token na base de dados
        $stmt = $mysqli->prepare("UPDATE registo_temp SET token_confirmacao = ?, token_expiracao = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiracao, $destino);

        if ($stmt->execute()) {
            // Cria o link de confirmação usando o token gerado
            $link_confirmacao = "http://localhost/site_ndap/ndap/login/confirmar_conta.php?token=$token";
            $content = "<b>Clique no <a href='$link_confirmacao'>link</a> para confirmar o seu e-mail. Este link expira em 1 hora.</b>";
            $mail->Body = $content;

            // Configurações de segurança do TLS
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->send();
            echo "E-mail enviado com sucesso.";
        } else {
            throw new Exception("Erro ao atualizar o token na base de dados.");
        }
    } catch (Exception $e) {
        echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
    }
}

?>
