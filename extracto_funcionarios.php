<?php
/*
 * EXTRACTO DE HORAS DOS FUNCIONARIOS
 */
	
	//mysql_connect($DB_HOST,$DB_USER,$DB_PASS); //ligar a base de dados
	//mysql_select_db($DB_TABLE) or die('Erro de ligacao a base de dados!');
	
	if(isset($_COOKIE['data_i']))
	{
		$data_inicial = $_COOKIE['data_i'];
		$data_final = $_COOKIE['data_f'];
	}else{
		$data_inicial = '2013-01-01';
		$data_final = '2013-12-31';
	}

	function horas_avaria($id_funcionario,$di,$df,$dias_trab) //obter as horas das avarias de cada funcionario
	{
			$q_horas_avarias = "select mov_avarias.id_funcionario, (sum(mov_avarias.horas)/60) as horas_avaria 
								from mov_avarias
								where mov_avarias.id_funcionario = ".$id_funcionario." and mov_avarias.`data` between '".$di."' and '".$df."'
								group by mov_avarias.id_funcionario";
			$r_horas_avarias = mysql_query($q_horas_avarias);
			$n_horas_avarias = mysql_num_rows($r_horas_avarias);
			
			if($n_horas_avarias > 0)
			{
				$horas_com_avarias = mysql_result($r_horas_avarias,0,'horas_avaria');
					
				if($horas_com_avarias > 0)
				{
					return '<font style="color: green">+'.round(($horas_com_avarias/$dias_trab),2).'</font>';
				}else{
					return 0;
				}
			}else{
				return 0;
			}
	}

	

	
	$q_total_funcionarios = "select mov_viatura.id_funcionario as id_funcionario, funcionario.nome_funcionario, (sum(mov_viatura.horas_viatura)/60) as horas,  count(distinct date(mov_viatura.`data`)) as dias_trabalho
								from mov_viatura
			  					left join viaturas on viaturas.id_viatura = mov_viatura.id_viatura
								left join funcionario on funcionario.id_funcionario = mov_viatura.id_funcionario
								where viaturas.acessorio = 0 and mov_viatura.`data` between '".$data_inicial."' and '".$data_final."'
								group by mov_viatura.id_funcionario
								order by dias_trabalho desc";
	
	$r_total_funcionarios = mysql_query($q_total_funcionarios); //obter resultados da query
	
	
	echo '<h3>Extracto Horario de Funcionarios</h3><br>';
	
	echo '
			<table>
			<thead >
				<th style="border-bottom:solid 2px black;">Nome</th>
				<th style="border-bottom:solid 2px black;">Dias Trabalhados</th>
				<th style="border-bottom:solid 2px black;">Horas Diarias</th>
				<th style="border-bottom:solid 2px black;">Horas Extra</th>
				<th style="border-bottom:solid 2px black;">Horas Avarias</th>
			</thead>
			<tbody>
			';
 	
	while($resultado = mysql_fetch_assoc($r_total_funcionarios)) //varre todos os resultados da query
		{
			$media_horas_trabalhadas = ($resultado["horas"]/$resultado["dias_trabalho"]);
			$media_horas_extra = ($media_horas_trabalhadas-8);
			
			
			
			if($media_horas_extra > 0)
			{
				$media_extra = '<font style="color: green;"><b>+'.round($media_horas_extra,2).'</b></font>';
			}else{
				$media_extra = 0;
			}
			
			echo '<tr>
					<td>'.$resultado["nome_funcionario"].'</td>
					<td style="text-align:center;">'.$resultado["dias_trabalho"].'</td>
					<td style="text-align:center;">'.round($media_horas_trabalhadas,2).' H </td>
					<td style="text-align:center;">'.$media_extra.' H </td>
					<td style="text-align:center;">'.horas_avaria($resultado['id_funcionario'],$data_inicial,$data_final,$resultado["dias_trabalho"]).' H </td>
				 </tr>';
		}
		
	echo '</tbody>
		</table>';
?>