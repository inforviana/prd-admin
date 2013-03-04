<?php 
	//verifica a data a utilizar
	if(isset($_COOKIE['data_i']))
	{
		$data_inicial = $_COOKIE['data_i'];
		$data_final = $_COOKIE['data_f'];
	}else{
		$data_inicial = '2013-01-01';
		$data_final = '2013-12-31';
	}
	
	
	function cor_horas($numero) //funcao para devolver a cor mediante um parametro
	{
		if($numero == 0)
		{
			$cor = "red";
		}else{
			$cor = "green";
		}
		
		return $cor;
	}
	
	
	//ordena os resultados
	if(isset($_GET['ordem']))
	{
		switch($_GET['ordem'])
		{
			case 'viatura':
				$ordem = "desc_viatura asc";
				break;
			case 'dias':
				$ordem = "dias_trabalho desc";
				break;
			case 'horas':
				$ordem = "total_horas desc";
				break;
			default:
				$ordem = "preco_total desc";
				break;
		}
	}else{
		$ordem = "total_horas desc";
	}
	
	
	//obter o resultado da query
	$q_extracto_viaturas = "select mov_viatura.id_viatura, viaturas.desc_viatura ,sum(mov_viatura.horas_viatura) as 'total_horas', sum((mov_viatura.horas_viatura/60)*mov_viatura.preco_viatura) as preco_total, count(distinct date(mov_viatura.data)) as dias_trabalho
							from mov_viatura
							left join viaturas on viaturas.id_viatura = mov_viatura.id_viatura
							where viaturas.acessorio = 0 and mov_viatura.horas_viatura > 0 and mov_viatura.`data` between '".$data_inicial."' and '".$data_final."'
							group by mov_viatura.id_viatura
							order by ".$ordem;
	
	$r_extracto_viaturas = mysql_query($q_extracto_viaturas); //query
	
	//declarar o array inicial
	$listagem = array();
	
	while($resultado = mysql_fetch_assoc($r_extracto_viaturas))
	{
		//preencher o primeiro array com a linha
		$linha = array("id_viatura"=>$resultado["id_viatura"],"desc_viatura"=>$resultado["desc_viatura"],"total_horas"=>$resultado["total_horas"],"preco_total"=>$resultado["preco_total"],"dias_trabalho"=>$resultado["dias_trabalho"]);
		$listagem[]=$linha; //guarda o array com a linha no array principal
	}
	
	//obter o tamanho do array
	$tamanho_array = count($listagem);
	
	
	echo '<h3>Extracto de Horas Faturadas por Viatura</h3><br>';
	
	//tabela em html
	echo '<table id="tabela_extracto_viaturas">
			<thead>
				<th><a href="./index.php?pagina=extractoviaturas&ordem=viatura">Viatura</a></th>
				<th><a href="./index.php?pagina=extractoviaturas&ordem=dias">Dias Trabalho</a></th>
				<th><a href="./index.php?pagina=extractoviaturas&ordem=horas">Total Horas facturadas</a></th>
				<th><a href="./index.php?pagina=extractoviaturas&ordem=facturacao">Faturacao Est.</a></th>
			</thead>
			<tbody>';
	
	//array para mostrar na tabela os resultados
	for($i=0;$i<$tamanho_array;$i++)
	{
		echo '<tr>
				<td>'.$listagem[$i]["desc_viatura"].'</td>
				<td>'.$listagem[$i]["dias_trabalho"].'</td>
			    <td style="text-align:right;color:'.cor_horas($listagem[$i]["total_horas"]).'">'.round(($listagem[$i]["total_horas"]/60),2).' Horas</td>
			    <td>'.round($listagem[$i]["preco_total"],2).' Euros</td>
			  </tr>
				';
	}
	
	echo '</tbody>
		</table>';
?>