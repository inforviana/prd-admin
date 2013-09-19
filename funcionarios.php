<?php
	if(isset($_GET['apagar'])) $apagar=$_GET['apagar'];
	if(isset($_GET['id'])) $id=$_GET['id'];

	if(isset($_GET['apagar']) && $apagar==1){
		$q_apagar="DELETE FROM funcionario WHERE id_funcionario=".$id;
		if (mysql_query($q_apagar)){
			$msg= '<font class="font_titulo"><img src="ok.gif">Funcionario apagado com sucesso!</font>';
		}else{
			$msg='<font class="font_titulo_erro"><img src="erro.gif">Erro ao gravar as alterações!</font>';
		}
	}

	//se houver algum texto a pesquisar
	if(isset($_POST['procura']))
	{
		$p_funcionarios=$_POST['procura'];
	}else{
		$p_funcionarios = "";
	}

	//se activo estiver definido procura todos os estados
	if(isset($_POST['activo']))
	{
		if($_POST['activo']==1)
		{
			$pactivo = " activo >= 1 and "; //apenas os activos
		}else{
			$pactivo = "";
		}
	}else{
		$pactivo = " activo = 1 and "; //apenas os activos
	}

	$q_funcionarios="select * from funcionario where ".$pactivo." nome_funcionario like '%".$p_funcionarios."%' order by nome_funcionario asc"; //query para seleccionar todos os funcionarios
	$r_funcionarios=mysql_query($q_funcionarios);
	$n_funcionarios=mysql_num_rows($r_funcionarios);
	
	echo '<table width=700><tr><td>'.@$msg.'<br><b><img src="funcionario.gif">Funcionarios</b></td>';
	echo '<td align="right"><form method="POST" action="index.php?pagina=funcionarios"></td><td><input type="text" name="procura"><input type="image" src="lupa.gif" value="Procurar" alt="Procurar"></td><td>
			<select name="activo">
				<option value=1 >Activos</option>
				<option value=0 >Todos</option>
			</select>
			</form>
		</td>
	</tr>
	</table><br><a href="index.php?pagina=editarfuncionario&novo=1"><img src="novo.gif" border=0><font class="font_novo">Adicionar Funcionario</font></a>';
	
	echo '<table id="hor-minimalist-b" summary="motd"><tbody>';
	//inicio do loop de preenchimento da tabela de funcionarios
	for($i=0;$i<$n_funcionarios;$i++){
		//cor conforme o estado do funcionario
		switch(mysql_result($r_funcionarios, $i,'activo'))
		{
			case 1:
				$cor = ' style="color:green;" ';
				$estado = "Activo";
				break;
			case 2:
				$cor = ' style="color:orange;" ';
				$estado = "Baixa";
			case 3:
				$cor = ' style = "color:blue" ';
				$estado = "Ferias";
			default:
				$cor = ' style="color:red; "';
				$estado = "Desactivado";
				break;
		}

		echo '<tr>';
			echo '<td align="center"><a '.$cor.' href="index.php?pagina=editarfuncionario&id='.mysql_result($r_funcionarios,$i,'id_funcionario').'" class="botao_detalhes">Detalhes</a></td>';
			echo '<td>'.mysql_result($r_funcionarios,$i,'nome_funcionario').'</td>';
			echo '<td>'.mysql_result($r_funcionarios,$i,'grupo_funcionario').'</td>';
			echo '<td>'.$estado.'</td>';
			echo '<td><a href="index.php?pagina=listagemcombustivel&idfuncionario='.mysql_result($r_funcionarios,$i,'id_funcionario').'"><img height=16 src="gasoleo.png" border=0></a></td>';
			echo '<td><a href="index.php?pagina=listagemavarias&idfuncionario='.mysql_result($r_funcionarios,$i,'id_funcionario').'"><img src="avarias.gif" border=0></a></td>';
			echo '<td><a href="index.php?pagina=listagemhoras&idfuncionario='.mysql_result($r_funcionarios,$i,'id_funcionario').'"><img src="grafico.gif" border=0></a></td>';
			//echo '<td><input type="image" onclick="apagar(\'index.php?pagina=funcionarios&apagar=1&id='.mysql_result($r_funcionarios,$i,'id_funcionario').'\')" src="delete.gif"></td>';
		echo '</tr>';
	}
	echo '</tbody></table>';
?>