<?php
	header("Content-type: image/jpeg");
	
	require('config.php'); //carregar variaveis globais
	//ligar á base de dados
	mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
	//seleccionar a tabela a utilizar
	mysql_select_db($DB_TABLE) or die('Erro de ligacaoo a base de dados!');
	
	$imgid=$_GET['idfuncionario'];
	
	$q="select img from funcionario where id_funcionario=".$imgid;
	
	$r=mysql_query($q);
	$img=mysql_result($r,0);
	
	if(strlen($img)<100)
	{
		$r=mysql_query("select img from config where attrib='img'");
		$img=mysql_result($r,0);
        }
	echo $img;
?>