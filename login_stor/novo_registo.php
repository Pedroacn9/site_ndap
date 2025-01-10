<html>
<head>
	<title></title>
</head>
<body>
<center>
<form method="post" name="form1">
<p>&nbsp;</p>

<p><a href="login.php">Voltar</a></p>

<table border="0" cellpadding="1" cellspacing="1" style="width:50%;">
	<tbody>
		<tr>
			<td>Email:</td>
			<td><input name="email" type="email" /></td>
		</tr>
		<tr>
			<td>Nome:</td>
			<td><input name="nome" type="text" /></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input name="password" type="password" /></td>
		</tr>
		<tr>
			<td>Confirmar Password:</td>
			<td><input name="password2" type="password" /></td>
		</tr>
	</tbody>
</table>

<p><input name="gravar" type="submit" value="Registar" /></p>
</form>
</center>
</body>
</html>
<?php
include 'funcoes.php';

if(isset($_POST['gravar'])){
	if($_POST['password']!=$_POST['password2']){
		echo("<h2>Passwords são diferentes!</h2>");
	}else{
		$db = new mysqli("localhost","root","","users");
		$sql="SELECT * FROM utilizadores WHERE email='".$_POST['email']."'";
		$tabela = $db->query($sql);
		if ($tabela->num_rows > 0) {
			echo ("<h2>Já existe esse email</h2>");
		}else{		
			$sql="INSERT INTO utilizadores(email, nome, password, confirma_password) 
			VALUES ('". $_POST['email']."','". $_POST['nome']."','". md5($_POST['password'])."',false)";
			$db->query($sql);
			echo("<h2>Registo efetuado. Verifique o seu email para confirmar o registo!</h2>");
			envia_email($_POST['email'],1);
		}
	}	
}
?>


