<?php 

	//operacoes com as obras
	
	//criar nova obra
	if(isset($_POST['inputObra']))
	{
		mysql_query("insert into obras (descricao_obra) values ('".$_POST['inputObra']."')");
	}
	
	//apagar uma obra
	if(isset($_GET['apagar']))
	{
		$idObra = $_GET['apagar'];
		
		//verificar se existem movimentos com esta obra
		$r_obrasExistentes = mysql_query("select count(id_movviatura) from mov_viatura where id_obra = ".$idObra);
		
		if(mysql_result($r_obrasExistentes, 0, 0) == 0)
		{
			//apagar a obra
			mysql_query("delete from obras where id_obra = ".$idObra);
		}
	}
	
	


	echo '<br><table id="tabela_obras">
				<thead>
					<th>Nome Obra</th>
					<th>Operacoes</th>
			 	</thead>
			    <tbody>
					';
	
	//mostrar as obras existentes
	$rObras = mysql_query("select * from obras");
	$nObras = mysql_num_rows($rObras);
	
	for($i=0;$i<$nObras;$i++)
	{
		echo '<tr>
					<td>'.mysql_result($rObras, $i,'descricao_obra').'</td>
				 	<td><a href="./index.php?pagina=obras&apagar='.mysql_result($rObras, $i,'id_obra').'">Apagar</a></td>
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
		  </table>
		<br><br>';
?>