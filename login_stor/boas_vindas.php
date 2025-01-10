<?php
session_start();
if (isset($_SESSION['nome'])){		
	echo("Olá:".$_SESSION['nome']);
}else{
	header("Location:login.php");
}



?>
<a href="editar_perfil.php">Editar Perfil</a>