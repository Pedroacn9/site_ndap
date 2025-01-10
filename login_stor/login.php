<html>
<head>
	<title></title>
</head>
<body>
<center>
<form method="post" name="form1">
<table border="0" cellpadding="1" cellspacing="1" style="width:50%;">
	<tbody>
		<tr>
			<td>Email:</td>
			<td><input name="email" type="email" value="<?php if(isset($_POST['email'])) echo($_POST['email'])?>"/></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input name="password" type="password" value=""/></td>
		</tr>
	</tbody>
</table>

<p><input name="login" type="submit" value="Login" /></p>

<p><a href="novo_registo.php">Novo Registo</a></p>

<p><a href="recupera_password.php">Recuperar Password</a></p>
</form>
<?php

session_start();
include 'funcoes.php';

if (isset($_GET['enviar'])){
	envia_email($_GET['enviar']);
	header('Location:login.php');
}

if(isset($_POST['login'])){
	$db = new mysqli("localhost","root","","users");
	$sql ="SELECT password,nome,confirma_password FROM utilizadores WHERE email='".$_POST['email']."'";
	$tabela=$db->query($sql);	
	if ($tabela->num_rows == 0) {
		echo("Esse utilizador não está registado!");
	}else{
		$linha = $tabela->fetch_assoc();
		if ($linha['confirma_password']==false){
			echo("Ainda não confirmou o email. Verifique o seu email 
			para confirmar o registo.
			Clique <a href='?enviar=".$_POST['email']."'>aqui</a> para reenviar o email.");
		}else{
			if (md5($_POST['password'])==$linha['password']){
				echo("Password CORRETA");	
				$_SESSION['nome']=$linha['nome'];
				
				header('Location:boas_vindas.php');
			}else{
				echo("Password INCORRETA");
				
			}
		}
	}
}

?>
</center>
</body>
</html>
