<?php
			require("config.php");
			mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
			mysql_select_db($DB_TABLE) or die('Erro de ligação á base de dados!');
			$titulo=$_GET['titulo']; //titulo do extracto
			
			//querys
			$q_relatorio="SELECT funcionario.id_funcionario, funcionario.nome_funcionario AS 'Funcionario', SUM(mov_viatura.horas_viatura) AS 'Horas Facturadas', sum(mov_viatura.transporte) AS 'Horas Deslocação'FROM mov_viatura JOIN funcionario ON mov_viatura.id_funcionario = funcionario.id_funcionario JOIN viaturas ON viaturas.id_viatura=mov_viatura.id_viatura WHERE viaturas.tipo_viatura != 'Acessórios' GROUP BY mov_viatura.id_funcionario ORDER BY funcionario.nome_funcionario";	
			$q_avarias="SELECT mov_avarias.id_funcionario, SUM(mov_avarias.horas) FROM mov_avarias GROUP BY mov_avarias.id_funcionario";	
			
			
			//procedimentos mysql
			$r_relatorio=mysql_query($q_relatorio); //query
			$n_relatorio=mysql_numrows($r_relatorio); //numero de resultados
			$f_relatorio=mysql_num_fields($r_relatorio); //numero de resultados das horas facturadas
			
			$r_avarias=mysql_query($q_avarias); //query das avarias
			$n_avarias=mysql_numrows($r_avarias); //numeros de resultados das avarias
			
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
						for($j=1;$j<5;$j++){
							if($j<4){
								echo '<th align="center">'.mysql_field_name($r_relatorio,$j).'</th>'; //colunas da primeira query
							}else{
								echo '<th align="center">Horas em Avarias</th>';
								echo '<th align="center">Horas Extra</th>';								
							}
						}
					?>
				</tr>
			</thead>
			<tbody>
				<?php /* LINHAS DE DADOS */ 
					for($i=0;$i<$n_relatorio;$i++){
						echo '<tr>';
								for($j=0;$j<4;$j++){ //colunas
									/* CALCULAR HORAS NORMAIS */
										if($j==0){ //NOME DO FUNCIONARIO
											echo '<td align="center">'.mysql_result($r_relatorio,$i,1).'</td>';
										}
										if($j==1){ //HORAS FACTURADAS
											$horas_trabalho=intval(mysql_result($r_relatorio,$i,2)/60);
											if($horas_trabalho==0){
												$horas_trabalho='00';
											}
											$minutos_trabalho=intval(mysql_result($r_relatorio,$i,2)%60);
											if($minutos_trabalho==0){
												$minutos_trabalho='00';
											}
											echo '<td align="center">'.$horas_trabalho.':'.$minutos_trabalho.'</td>';											
										} elseif($j==2){ 
											// HORAS DESLOCAÇÃO
											$horas_deslocacao=intval(mysql_result($r_relatorio,$i,3)/60);
											$minutos_deslocacao=intval(mysql_result($r_relatorio,$i,3)%60);
											if($horas_deslocacao==0){
												$horas_deslocacao='00';
											}
											if($minutos_deslocacao==0){
												$minutos_deslocacao='00';
											}
											echo '<td align="center">'.$horas_deslocacao.':'.$minutos_deslocacao.'</td>';
										}elseif($j==3){ // HORAS AVARIAS
											for($k=0;$k<$n_avarias;$k++){ //por cada avaria ... 
												if(mysql_result($r_avarias,$k,0)==mysql_result($r_relatorio,$i,0)){ //verifica a que funcionario corresponde ...
													$horas_avaria=intval(mysql_result($r_avarias, $k,1)/60);
													$minutos_avaria=intval(mysql_result($r_avarias, $k,1)%60);
													if ($horas_avaria==0){
														$horas_avaria='00';
													}
													if($minutos_avaria==0){
														$minutos_avaria='00';
													}
													echo '<td align="center">'.$horas_avaria.':'.$minutos_avaria.'</td>'; //e escreve o resultado :)
												}
											}
										}
								}
						echo '</tr>';
					}
				?>
			</tbody>
		</table>
	</center>
	</body>
</html>