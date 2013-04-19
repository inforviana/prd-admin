<?php

	if(isset($_GET['idviatura']))
	{
		//apagar preco obra
		if(isset($_GET['apagar']))
		{
			$idPrecoObra = $_GET['apagar'];
			mysql_query("delete from obras_precos where id_preco_obra = ".$idPrecoObra);
		}
		
		//inserir novo preco de obra
		if(isset($_POST['selectObra']) && isset($_POST['inputPreco']))
		{
			mysql_query("insert into obras_precos (id_obra,id_viatura,preco_obra) values (".$_POST['selectObra'].",".$_GET['idviatura'].",'".$_POST['inputPreco']."')");
		}
		
		
		//mostrar a listagem de obras
		$rDadosViatura = mysql_query("select * from viaturas where id_viatura = ".$_GET['idviatura']);
		
		echo '
				<h2>Precos Obras - Viatura : '.mysql_result($rDadosViatura, 0,'desc_viatura').'</h2><br>
				<table id="tabela_precos_obra">
						<thead>
							<th>Obra</th>
							<th>Preco Hora</th>
							<th></th>
						</thead>
						<tbody>
							<tr>
								<td>
									<form method="POST" action="./index.php?pagina=precosobra&idviatura='.$_GET['idviatura'].'">
									<select name="selectObra">
										';
										
										$qObras = "select * from obras";
										$rObras = mysql_query($qObras);
										$nObras = mysql_num_rows($rObras);
										
										for($i=0;$i<$nObras;$i++)
										{
											echo '<option value="'.mysql_result($rObras, $i,'id_obra').'">'.mysql_result($rObras,$i,'descricao_obra').'</option>';
										}
							
							
									echo '
									</select>
								</td>
								<td>
									<input style="text-align:center;" type="text" name="inputPreco">
								</td>
								<td>
									<button type="submit">OK</button>
								    </form>
							    </td>
						 </tr>
						';
									
						//obter os precos por obra ja definidos
						$rPrecosObra = mysql_query("select obras_precos.id_preco_obra ,obras.descricao_obra, obras_precos.preco_obra
													from obras_precos 
													left join obras on obras.id_obra = obras_precos.id_obra
													where id_viatura = ".$_GET['idviatura']);
						$nPrecosObra = mysql_num_rows($rPrecosObra);
									
						for($i=0;$i<$nPrecosObra;$i++)
						{
							echo '<tr>
										<td>'.mysql_result($rPrecosObra, $i,'descricao_obra').'</td>
									    <td style="text-align:center;">'.mysql_result($rPrecosObra, $i,'preco_obra').' Euros</td>
									    <td><a href="./index.php?pagina=precosobra&apagar='.mysql_result($rPrecosObra, $i,'id_preco_obra').'&idviatura='.$_GET['idviatura'].'">Apagar</a></td>
								  </tr>';
						}						
									
			echo '
					</tbody>
			</table>
			<br><br>';
	} 
?>