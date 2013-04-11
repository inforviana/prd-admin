<?php
	$id=$_GET['id'];
	@$guardar=$_GET['guardar'];
	
	if(isset($_GET['idv']))
	{
		$idv=$_GET['idv'];
	}
		
	
	if($guardar==1){
		$valor=$_POST['valor'];
		$kms=$_POST['kms'];
		$novo=$_GET['novo'];
		$data_n=$_POST['data'];
		$funcionario=$_POST['funcionario'];
		$viatura=$_POST['viatura'];
		
		
		if(isset($novo)&&$novo!=1){
			$q_guardar="UPDATE mov_combustivel SET id_viatura=".$viatura." ,id_funcionario=".$funcionario." ,data='".$data_n."', kms_viatura=".$kms." ,valor_movimento='".$valor."' where id_movcombustivel=".$id;
		}

		if(mysql_query($q_guardar)){
			$msg = 'Alterações salvas com sucesso!';
		}else{
			$msg = 'Erro ao gravar as alterações!\n'.$q_guardar;
		}
		echo '
		<script type="text/javascript">
			alert("'.$msg.'");
			window.location="index.php?pagina=listagemcombustivel&idviatura='.$idv.'";
		</script>
		';
	}

	//querys
	$q_mc="select * from mov_combustivel where id_movcombustivel=".$id;
	$r_mc=mysql_query($q_mc);
	$n_mc=mysql_num_rows($r_mc);
	
	$q_f="select * from funcionario order by nome_funcionario";
	$r_f=mysql_query($q_f);
	$n_f=mysql_num_rows($r_f);
	
	$q_v = "select * from viaturas order by desc_viatura";
	$r_v = mysql_query($q_v);
	$n_v = mysql_num_rows($r_v);
	
	
	$idViatura = mysql_result($r_mc,0,'id_viatura');
	
	//querys para o historico do contador
	$qContagensAnteriores = "select 
							    data, kms_viatura, valor_movimento
							from
							    mov_combustivel
							where
							    id_movcombustivel < ".$id." and id_viatura = ".$idViatura." and valor_movimento > 0
							order by
							    id_movcombustivel desc
							LIMIT 2;";
	$rContagensAnteriores = mysql_query($qContagensAnteriores);
	$nContagensAnteriores = mysql_num_rows($rContagensAnteriores);
	
	$qContagensPosteriores = "select 
								    data, kms_viatura, valor_movimento
								from
								    mov_combustivel
								where
								    id_movcombustivel > ".$id." and id_viatura = ".$idViatura." and valor_movimento > 0
								order by
								    id_movcombustivel asc
								LIMIT 2;";
	$rContagensPosteriores = mysql_query($qContagensPosteriores);	
	$nContagensPosteriores = mysql_num_rows($rContagensPosteriores);
	
	
	
	//desenhar a pagina
	echo '<table id="hor-minimalist-b" summary="motd"><thead><th>EDITAR MOVIMENTO DE COMBUSTIVEL '.@$id.'</th></thead><tbody><tr></tr><tr><td><form method="POST" action="index.php?pagina=editarcomb&id='.$id.'&guardar=1&idv='.mysql_result($r_mc,0,'id_viatura').'&novo='.@$novo.'">
	Funcionario: <select name="funcionario">';
					for($i=0;$i<$n_f;$i++){
						//verifica se é o funcionario do registo
						if((mysql_result($r_mc,0,'id_funcionario'))==mysql_result($r_f,$i,'id_funcionario')){
							$selected='selected="selected"';
						}else{
							$selected="";
						}
						echo '<option value="'.mysql_result($r_f,$i,'id_funcionario').'" '.$selected.'>'.mysql_result($r_f,$i,'nome_funcionario').'</option>';
					}
	
	//combo das viaturas
	echo'		</select><br><br>
		Viatura: <select name="viatura">';
					for($i=0;$i<$n_v;$i++){
						//verifica se é a viatura do registo
						if((mysql_result($r_mc,0,'id_viatura'))==mysql_result($r_v,$i,'id_viatura')){
							$selected='selected="selected"';
						}else{
							$selected="";
						}
						echo '<option value="'.mysql_result($r_v,$i,'id_viatura').'" '.$selected.'>'.mysql_result($r_v,$i,'desc_viatura').' - '.mysql_result($r_v,$i,'marca_viatura').' '.mysql_result($r_v,$i,'modelo_viatura').'</option>';
					}
	echo'		</select><br><br>	
	Data: <input type="text" size=20 name="data" value="'.mysql_result($r_mc,0,'data').'"><br><br>';
	
		//apresentar contagens do contador anteriores
		for($i=0;$i<$nContagensAnteriores;$i++)
		{
			echo '(-) '.mysql_result($rContagensAnteriores,$i,'data').' - '.mysql_result($rContagensAnteriores,$i,'valor_movimento').' Litros - Contador: '.mysql_result($rContagensAnteriores,$i,'kms_viatura').' H/KM<br>';
		}
		
		//valor do contador da viatura
		echo 'Horas/Kilometros: <input type="text" style="text-align:center" size=10 name="kms" value="'.mysql_result($r_mc,0,'kms_viatura').'"><br>';	
		
		//apresentar contagens do contador posteriores
		for($i=0;$i<$nContagensPosteriores;$i++)
		{
			echo '(+) '.mysql_result($rContagensPosteriores,$i,'data').' - '.mysql_result($rContagensPosteriores,$i,'valor_movimento').' Litros - Contador: '.mysql_result($rContagensPosteriores,$i,'kms_viatura').' H/KM<br>';
		}

	//litros do movimento
	echo '<br><br>
			Litros:<input type="text" size=3 name="valor" value="'.mysql_result($r_mc,0,'valor_movimento').'">';
	
	
	echo '	<br><br>';
	echo '</td></tr><tr><td align="right">'.@$msg.'<br><button type="submit">Guardar Alterações</button></form></td></tr></tbody></table>';
?>