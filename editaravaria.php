<?php
	//editar avarias inseridas pelos funcionarios
	//
	@$id=$_GET['id']; //variaveis globais
	@$idv=$_GET['idv'];
	@$guardar=$_GET['guardar'];
	
	//variaveis especificas
	@$desc_avaria=$_POST['desc_avaria'];
	@$preco=$_POST['preco'];
	@$estado=$_POST['estado'];
	@$horas=$_POST['horas'];
	$funcionario=$_POST['funcionario'];
	$viatura=$_POST['viatura'];	
	
	//actualizar os valores da avaria
	if ($guardar==1){
		$q_guardar="UPDATE mov_avarias SET id_funcionario=".$funcionario." ,id_viatura=".$viatura.",desc_avaria='".$desc_avaria."',preco=".$preco.",estado='".$estado."',horas=".$horas." WHERE id_avaria=".$id;
		if(mysql_query($q_guardar)){
			$msg= 'Alterações salvas com sucesso!';;
		}else{
			$msg='Erro ao gravar as alterações!\n'.$q_guardar;
		}
		echo '
		<script type="text/javascript">
			alert("'.$msg.'");
			window.location="index.php?pagina=listagemavarias&idviatura='.$idv.'";
		</script>
		';
	}
	
	if($guardar!=1){
	//ler os valores
	$q_ler="select mov_avarias.id_viatura as 'idv', mov_avarias.id_funcionario as 'idf', mov_avarias.id_avaria, viaturas.id_viatura,viaturas.desc_viatura, funcionario.nome_funcionario, mov_avarias.data, mov_avarias.categoria,mov_avarias.desc_avaria, mov_avarias.preco, mov_avarias.estado, mov_avarias.horas
		from mov_avarias 
		inner join funcionario on funcionario.id_funcionario=mov_avarias.id_funcionario
		inner join viaturas on viaturas.id_viatura = mov_avarias.id_viatura
		where id_avaria=".$id;
	$r_ler=mysql_query($q_ler);
	$n_ler=mysql_num_rows($r_ler);
	
	$q_f="select * from funcionario order by nome_funcionario";
	$r_f=mysql_query($q_f);
	$n_f=mysql_num_rows($r_f);
	
	$q_v = "select * from viaturas order by desc_viatura";
	$r_v = mysql_query($q_v);
	$n_v = mysql_num_rows($r_v); 
	
	
	//preencher os campos na pagina
	echo '<table id="hor-minimalist-b" summary="motd"><thead><th>EDITAR AVARIA</th></thead><tbody>
	<form method="POST" action="index.php?pagina=editaravarias&id='.$id.'&idv='.mysql_result($r_ler, 0,'viaturas.id_viatura').'&guardar=1">
	<tr><td><b>Funcionario :: </b>
		<select name="funcionario">';
					for($i=0;$i<$n_f;$i++){
						//verifica se é o funcionario do registo
						if((mysql_result($r_ler,0,'idf'))==mysql_result($r_f,$i,'id_funcionario')){
							$selected='selected="selected"';
						}else{
							$selected="";
						}
						echo '<option value="'.mysql_result($r_f,$i,'id_funcionario').'" '.$selected.'>'.mysql_result($r_f,$i,'nome_funcionario').'</option>';
					}
	
	//combo das viaturas
	echo'		</select>
	<br><br><b>Viatura :: </b>
	<select name="viatura">';
					for($i=0;$i<$n_v;$i++){
						//verifica se é a viatura do registo
						if((mysql_result($r_ler,0,'idv'))==mysql_result($r_v,$i,'id_viatura')){
							$selected='selected="selected"';
						}else{
							$selected="";
						}
						echo '<option value="'.mysql_result($r_v,$i,'id_viatura').'" '.$selected.'>'.mysql_result($r_v,$i,'desc_viatura').' - '.mysql_result($r_v,$i,'marca_viatura').' '.mysql_result($r_v,$i,'modelo_viatura').'</option>';
					}
	echo'		</select>
	<br><br><b>Data :: </b>'.mysql_result($r_ler, 0,'data').'
	<br><br><b>Tipo de Avaria :: </b>'.mysql_result($r_ler, 0,'categoria').'</td></tr>
	<tr><td>
	Descrição da Avaria :: <input type="text" size=40 name="desc_avaria" value="'.mysql_result($r_ler,0,'desc_avaria').'"><br><br>
	| Custo :: EUR <input type="text" size=6 name="preco" value="'.mysql_result($r_ler,0,'preco').'"> | 
	Duração :: <input type="text" size=4 name="horas" value="'.mysql_result($r_ler,0,'horas').'">Minutos | 
	Concluida :: <input type="text" size=6 name="estado" value="'.mysql_result($r_ler,0,'estado').'">
		<br><br>';
	echo '</td></tr><tr><td align="right">'.@$msg.'<br><button type="submit">Guardar Alterações</button></form></td></tr></tbody></table>';
	}
?>