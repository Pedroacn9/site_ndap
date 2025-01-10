<?php
include('conexao.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function envia_email($destino) {
    global $mysqli; // Usa a conexão definida no conexao.php

    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Mailer = "smtp";
        $mail->SMTPDebug  = SMTP::DEBUG_OFF; // Defina como DEBUG_OFF em produção
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;
        $mail->Host       = "smtp.gmail.com";
        $mail->Username   = "ppetersoft@gmail.com";
        $mail->Password   = "glmf cmbd yknf dtba";

        $mail->isHTML(true);
        $mail->addAddress($destino, "Utilizador");
        $mail->setFrom("ppetersoft@gmail.com", "NDAP");
        $mail->Subject = "Recuperacao de Senha";

        // Gera o token e define expiração
        $token = bin2hex(random_bytes(16));
        $expiracao = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Atualiza no banco de dados
        $stmt = $mysqli->prepare("UPDATE utilizador SET token_redefinicao = ?, token_expiracao = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiracao, $destino);

        if ($stmt->execute()) {
            $content = "<b>Clique no <a href='http://localhost/site_ndap/ndap/login/definir_password.php?token=$token'>Redefinir Senha</a> para redefinir sua senha. Este link expira em 1 hora.</b>";
        } else {
            throw new Exception("Erro ao atualizar o banco de dados.");
        }

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
    } catch (Exception $e) {
        echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
    }
}
?>
