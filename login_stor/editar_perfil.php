<?php
session_start();
if (isset($_SESSION['nome'])){		
	echo("Olá:".$_SESSION['nome']);
}else{
	header("Location:login.php");
}
?>
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
			<td>Nome:<input name="id_user" type="hidden" /></td>
			<td><input name="nome" type="text" /></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><input name="email" type="text" /></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input name="password" type="password" /></td>
		</tr>
		<tr>
			<td>Confirmar Password:</td>
			<td><input name="confirma_password" type="password" /></td>
		</tr>
	</tbody>
</table>

<p><input name="submit" type="submit" value="Gravar" /></p>
</form>
</center>

<p>&nbsp;</p>

<p>&nbsp;</p>
</body>
</html>
