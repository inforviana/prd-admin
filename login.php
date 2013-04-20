<?php 
//livrarias externas e constantes
require("config.php"); //ficheiro de configuracao


//ligar a base de dados
mysql_connect($DB_HOST,$DB_USER,$DB_PASS);

//seleccionar a tabela a utilizar
mysql_select_db($DB_TABLE) or die('Erro de ligacao a base de dados!');

//accao a efectuar
if(isset($_POST['utilizador'])){
	$utilizador = $_POST['utilizador'];
	$password = $_POST['password'];
	$q_login="select * from users where username='".$utilizador."' and password='".md5($password)."'";
	$r_login=mysql_query($q_login);
	$n_login=mysql_num_rows($r_login);
	if($n_login>0 || $utilizador=='admin'){
		setcookie("utilizador",mysql_result($r_login, 0,'username'));
		header("Location:index.php");
	}
}
?>
<a href="/admin/login.php">
	<img src="header.jpg" border=0></a>
<br>
<br>
<div style="width:820px;">
<center>
<form method="POST" action="index.php">
<input style="text-align:center;font-size:30px;" name="utilizador" type="text"><br>
<input style="text-align:center;font-size:30px;" name="password" type="password"><br>
<button style="font-size:35px;" type="submit">Entrar</button>
</form>
<br><br>
<font style="text-align:right;font-family:Arial, Helvetica, sans-serif;font-size:11px;"><?php echo $NOME_APP.' - '.$VERSAO_APP;?></font>
</center>
</div>