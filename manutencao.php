<?php

//limpeza de valores NULL
function limparNull()
{
	mysql_query("UPDATE funcionario SET preco_hora_normal = 0 WHERE preco_hora_normal IS NULL");
	mysql_query("UPDATE funcionario SET preco_hora_extra = 0 WHERE preco_hora_extra IS NULL");
	mysql_query("UPDATE funcionario SET preco_sabado = 0 WHERE preco_sabado IS NULL");
	mysql_query("UPDATE viaturas SET preco_hora = 0 WHERE preco_hora IS NULL;");
}

limparNull();
?>