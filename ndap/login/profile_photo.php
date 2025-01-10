<?php
include('conexao.php'); // Inclua o arquivo de conexão com o banco de dados

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    exit; // Se não estiver logado, simplesmente encerra o script
}

// Busca a foto de perfil do usuário logado
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

// Se houver foto de perfil, exibe-a
if (!empty($foto_perfil)) {
    // Define o caminho completo para a imagem
    $caminho_imagem = "/ndap/login/Foto de Perfil/" . basename($foto_perfil);
    // Exibe a tag de imagem com a classe profile-image
    echo '<img src="' . htmlspecialchars($caminho_imagem) . '" class="profile-image" alt="Foto de Perfil">';
} else {
    // Caso não tenha foto de perfil, exibe um ícone padrão ou mensagem
    echo '<span class="profile-placeholder">Sem foto</span>';
}
?>