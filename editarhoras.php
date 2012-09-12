<?php
	@$id=$_GET['id'];
	@$idf=$_GET['idf'];
	@$guardar=$_GET['guardar'];
	
	
	
	if($guardar==1){
		$horas=$_POST['horas'];
		$minutos=$_POST['minutos'];
		$data=$_POST['data'];
		$funcionario=$_POST['funcionario'];
		$viatura=$_POST['viatura'];
		
		if(@$novo!=1){
			$q_guardar="UPDATE mov_viatura SET id_viatura=".$viatura.", id_funcionario = ".$funcionario.", data='".$data."', horas_viatura=".(($horas*60)+$minutos)." where id_movviatura=".$id;
		}else{
			$q_guardar="INSERT INTO grupos_funcionario (desc_grupo) VALUES ('".$valor."')"; 
		}
		if(mysql_query($q_guardar)){
			$msg= 'Alterações salvas com sucesso!';
		}else{
			$msg='Erro ao gravar as alterações!';
		}
					echo '
			<script type="text/javascript">
				alert("'.$msg.'");
				window.location="index.php?pagina=listagemhoras&idfuncionario='.$idf.'";
			</script>
			';
	}
	
	//querys
	$q_mv="select * from mov_viatura where id_movviatura=".$id;
	$r_mv=mysql_query($q_mv);
	$n_mv=mysql_num_rows($r_mv);
	
	$q_f="select * from funcionario order by nome_funcionario";
	$r_f=mysql_query($q_f);
	$n_f=mysql_num_rows($r_f);
	
	$q_v = "select * from viaturas order by desc_viatura";
	$r_v = mysql_query($q_v);
	$n_v = mysql_num_rows($r_v);
	
	//combo dos funcionarios
	echo '<table id="hor-minimalist-b" summary="motd"><thead><th>EDITAR REGISTO DE HORAS '.@$id.'</th></thead><tbody><tr></tr><tr><td><form method="POST" action="index.php?pagina=editarhoras&id='.$id.'&guardar=1&idf='.mysql_result($r_mv, 0,'id_funcionario').'&novo='.@$novo.'">
	Funcionario: <select name="funcionario">';
					for($i=0;$i<$n_f;$i++){
						//verifica se é o funcionario do registo
						if((mysql_result($r_mv,0,'id_funcionario'))==mysql_result($r_f,$i,'id_funcionario')){
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
						if((mysql_result($r_mv,0,'id_viatura'))==mysql_result($r_v,$i,'id_viatura')){
							$selected='selected="selected"';
						}else{
							$selected="";
						}
						echo '<option value="'.mysql_result($r_v,$i,'id_viatura').'" '.$selected.'>'.mysql_result($r_v,$i,'desc_viatura').' - '.mysql_result($r_v,$i,'marca_viatura').' '.mysql_result($r_v,$i,'modelo_viatura').'</option>';
					}
	echo'		</select><br><br>
	Data: <input type="text" size=20 name="data" value="'.mysql_result($r_mv,0,'data').'"><br>
	Horas: <input type="text" size=5 name="horas" value="'.intval(mysql_result($r_mv,0,'horas_viatura')/60).'">
	Minutos: <input type="text" size=5 name="minutos" value="'.(mysql_result($r_mv,0,'horas_viatura')%60).'">
		<br><br>';
	echo '</td></tr><tr><td align="right">'.@$msg.'<br><button type="submit">Guardar Alterações</button></form></td></tr></tbody></table>';
?>