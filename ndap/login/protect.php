<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (!isset($_SESSION['id']) || !$_SESSION['id']) {

    die("Verificação de Segurança<a href=\"/site_ndap/ndap/ndap_site.php\"><h1>Entrar</h1></a></p>");
}
?>