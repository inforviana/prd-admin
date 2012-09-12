<?php

	//obter os valores a gravar
	if($_GET['gravar']==1)
	{
		$q_opcoes="select * from config";
		$r_opcoes=mysql_query($q_opcoes);
		$n_opcoes=mysql_num_rows($r_opcoes);
		
		for($i=0;$i<$n_opcoes;$i++){
			//vai a base de dados para saber quais os POST a receber 
			$q_upd="update config set value='".$_POST[mysql_result($r_opcoes,$i,'attrib')]."' where attrib='".mysql_result($r_opcoes,$i,'attrib')."'";
			mysql_query($q_upd);
		}
	}


	//obter os valores de configuracao
	$q_opcoes="select * from config";
	$r_opcoes=mysql_query($q_opcoes);
	$n_opcoes=mysql_num_rows($r_opcoes);
	
	echo '
	<h1>Parametros Gerais</h1>
	<form action="index.php?pagina=opcoes&gravar=1" method="POST">
	<table>
	';
	
	//preencher a pagina com os valores de configuracao
	for($i=0;$i<$n_opcoes;$i++)
	{
		echo '<tr><td><label>'.mysql_result($r_opcoes,$i,'attrib').'</label></td><td><input name="'.mysql_result($r_opcoes,$i,'attrib').'" type="text" value="'.mysql_result($r_opcoes,$i,'value').'"></td></tr>';
	}
	
	echo '
	</table>
	<button type="submit">Gravar Alteracoes</button>
	</form>
	';
?>