<?php
	header("Content-type: image/jpeg");
	
	require('config.php'); //carregar variaveis globais
	//ligar á base de dados
	mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
	//seleccionar a tabela a utilizar
	mysql_select_db($DB_TABLE) or die('Erro de ligação á base de dados!');
	
	$imgid=$_GET['idviatura'];
	
	$q="select img from viaturas where id_viatura=".$imgid;
	
	$r=mysql_query($q);
	
	echo mysql_result($r,0);
?>