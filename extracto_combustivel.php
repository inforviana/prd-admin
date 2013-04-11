		<?php
			require("config.php");
			mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
			mysql_select_db($DB_TABLE) or die('Erro de ligação á base de dados!');
			$q_relatorio="select date(mov_combustivel.data) as 'Data', mov_combustivel.kms_viatura as 'Horas/Kms', sum(mov_combustivel.valor_movimento) as 'Total Litros' from mov_combustivel where id_viatura=27 group by mov_combustivel.data"; //query
			$titulo="Extracto por Viatura"; //titulo do extracto
			$r_relatorio=mysql_query($q_relatorio); //query
			$n_relatorio=mysql_numrows($r_relatorio); //numero de resultados
			$f_relatorio=mysql_num_fields($r_relatorio); //numero de colunas
		?>
<html>
	<head>
		<title><?php echo $titulo;?></title>
		<link rel="stylesheet" type="text/css" href="stylesheet_relatorios.css"> <!-- stylesheet CSS a carregar -->
	</head>
	<body>
	<center>
		<table id="hor-minimalist-b" summary="motd">
			<thead>
				<tr>
					<th scope="col" colspan="<?php echo $f_relatorio-1;?>"><?php echo $titulo;?></th>
				</tr>
				<tr>
					<?php //nomes das colunas 
						for($j=0;$j<$f_relatorio;$j++){
							echo '<th align="center">'.mysql_field_name($r_relatorio,$j).'</th>';
						}
					?>
				</tr>
			</thead>
			<tbody>
				<?php //dados
				$max=0;
				$min=999999;
				$hk=0; //horas ou kilometros da viatura
					for($i=0;$i<$n_relatorio;$i++){
						echo '<tr>';

							for($h=0;$h<$f_relatorio;$h++){
								echo '<td align="center">'.mysql_result($r_relatorio,$i,$h).'</td>';
								if($h==1){ //coluna das h/kms, maior e menor
									if(mysql_result($r_relatorio,$i,$h)>$max){ //maior
										$max=mysql_result($r_relatorio,$i,$h);
									}
									if(mysql_result($r_relatorio,$i,$h)<$min){ //menor
										if(mysql_result($r_relatorio,$i,$h)>0){$min=mysql_result($r_relatorio,$i,$h);}
									}	
								}
								if($h==2){ //somar litros combustivel
									if($n_relatorio>1){
										if($i<($n_relatorio-1)){
											$hk+=mysql_result($r_relatorio,$i,$h);
										}										
									} else {
										$hk+=mysql_result($r_relatorio,$i,$h);
									}
								}
							}
						echo '</tr>';
					}
					
					echo '<tr><th scope="col" colspan="'.$f_relatorio.'"></th></tr><tr>'; //somas e diferenças dos valores
						for($j=0;$j<$f_relatorio;$j++){
							echo "<td>";
								if($j==1){
									if($n_relatorio>1){
										$variacao=$max-$min;		
									} else {
										$variacao=$min;
									}
									
									if($variacao==999999){
										$variacao=0;
									}
									echo "<b>Mov. H/KM:</b> ".$variacao;
								}
								if($j==2){
									echo "<b>Total Litros:</b> ".$hk;
								}
							echo "</td>";
						}
					echo '</tr>';
					if($hk>0){
					echo '<tr><th scope="col" colspan="'.$f_relatorio.'"></th></tr><tr>'; //medias de consumo

							echo '<td colspan="'.$f_relatorio.'" align="right">';
							$media_consumo=$hk/$variacao;
							echo '<b>Consumo Hora/Km:</b> '.number_format($media_consumo,2).'L';
							echo "</td>";
					echo '</tr>';		
							echo '<td colspan="'.$f_relatorio.'" align="right">';
							$cons_100=$media_consumo*100;
							echo '<b>Consumo aos 100:</b> '.number_format($cons_100,2).'L';
							echo "</td>";
					echo '</tr>';				
					}
				?>
			</tbody>
		</table>
	</center>
	</body>
</html>