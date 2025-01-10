<?php
$utilizador = 'root';
$password = '';
$database = 'ndap';
$host = 'localhost';

$mysqli = new mysqli($host, $utilizador, $password, $database);

if ($mysqli->connect_error) {
    die("Falha ao conectar à base de dados: ". $mysqli->connect_error);
}
?>