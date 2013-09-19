<?php 

	//operacoes com as obras
	
	//criar nova obra
	if(isset($_POST['inputObra']))
	{
		mysql_query("insert into obras (descricao_obra, activo, preco) values ('".$_POST['inputObra']."',1,'0')");
	}

	//alterar preco da obra
	if(isset($_POST['preconovo']))
	{
		mysql_query("UPDATE obras SET preco = '".$_POST['precoObra']."' WHERE id_obra=".$_POST['preconovo']);
	}
	
	//apagar uma obra
	if(isset($_GET['apagar']))
	{
		$idObra = $_GET['apagar'];
		
		//verificar se existem movimentos com esta obra
		$r_obrasExistentes = mysql_query("select activo from obras where id_obra = ".$idObra);
		
		if(mysql_result($r_obrasExistentes, 0, 0) == 0)
		{
			//apagar a obra
			mysql_query("UPDATE obras SET activo = 1 WHERE id_obra = ".$idObra);
		}else{
			mysql_query("UPDATE obras SET activo = 0 WHERE id_obra = ".$idObra);
		}
	}
	
	


	echo '<br>
			<h2>Obras</h2><br>
			<table id="tabela_obras">
				<thead>
					<th>Nome Obra</th>
					<th>Preco</th>
					<th>Operacoes</th>
			 	</thead>
			    <tbody>
					';
	
	//mostrar as obras existentes
	$rObras = mysql_query("select * from obras order by descricao_obra");
	$nObras = mysql_num_rows($rObras);


	
	for($i=0;$i<$nObras;$i++)
	{
		if(mysql_result($rObras, $i,'activo') == 1)
		{
			$cor = "green";
			$texto = "Desactivar";
		}else{
			$cor = "red";
			$texto = "Activar";
		}

		echo '<tr>
					<td><font style="color:'.$cor.'" >'.mysql_result($rObras, $i,'descricao_obra').'</font></td>
					<td><form method="POST" action="./index.php?pagina=obras&preconovo='.mysql_result($rObras, $i,'id_obra').'">
						<input type="text" name="precoObra" value="'.mysql_result($rObras,$i,'preco').'">
						<input type="submit" value="OK"> 
					</form></td>
				 	<td><a style="color:'.$cor.';" href="./index.php?pagina=obras&apagar='.mysql_result($rObras, $i,'id_obra').'">'.$texto.'</a></td>
			  </tr>';
	}
	
	//mostra form para inserir nova obra
	echo '<tr>
			<td>
				<form method="POST" action="./index.php?pagina=obras">
				<input type="text" name="inputObra" id="inputObra">
			</td>
			<td>
				<button type="submit">Adicionar</button>
				</form>
			</td>
		</tr>
			    </tbody>
			<th></th>
			<th></th>
		  </table>
		<br><br>';
?>