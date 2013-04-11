<?php
	@$apagar=$_GET['apagar'];
	@$id=$_GET['id'];
	if($apagar==1){
		$q_apagar="DELETE FROM grupos_funcionario WHERE id_grupo=".$id;
		if (mysql_query($q_apagar)){
			$msg= '<font class="font_titulo"><img src="ok.gif">Grupo apagado com sucesso!</font>';
		}else{
			$msg='<font class="font_titulo_erro"><img src="erro.gif">Erro ao gravar as alterações!</font>';
		}
	}
	@$p_funcionarios=$_POST['procura'];
	$q_funcionarios="select * from grupos_funcionario where desc_grupo like '%".$p_funcionarios."%' order by desc_grupo asc"; //query para seleccionar todos os funcionarios
	$r_funcionarios=mysql_query($q_funcionarios);
	$n_funcionarios=mysql_num_rows($r_funcionarios);
	
	echo '<table width=700><tr><td>'.@$msg.'<br><b><img src="grupo.gif">Grupos de Funcionarios</b></td>';
	echo '<td align="right"><form method="POST" action="index.php?pagina=grupos"></td><td><input type="text" name="procura"><input type="image" src="lupa.gif" value="Procurar" alt="Procurar"></form></td></tr>
	</table><br><a href="index.php?pagina=editargrupos&novo=1"><img src="novo.gif" border=0><font class="font_novo">Adicionar Grupo</font></a>';
	
	echo '<table id="hor-minimalist-b" summary="motd"><tbody>';
	//inicio do loop de preenchimento da tabela
	for($i=0;$i<$n_funcionarios;$i++){
		echo '<tr>';
			echo '<td align="center"><a href="index.php?pagina=editargrupos&id='.mysql_result($r_funcionarios,$i,'id_grupo').'"><img src="details.gif" border=0></a></td>';
			echo '<td>'.mysql_result($r_funcionarios,$i,'desc_grupo').'</td>';
			echo '<td><a href="index.php?pagina=grupos&apagar=1&id='.mysql_result($r_funcionarios,$i,'id_grupo').'"><img src="delete.gif" border=0></td>';
		echo '</tr>';
	}
	echo '</tbody></table>';
?>