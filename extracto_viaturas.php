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
	$q_extracto_viaturas = "select 
							    mov_viatura.id_viatura,
							    viaturas.desc_viatura,
							    sum(mov_viatura.horas_viatura) as 'total_horas',
							    sum((mov_viatura.horas_viatura/60) * mov_viatura.preco_viatura) as preco_total,
							    count(distinct date(mov_viatura.data)) as dias_trabalho
							from
							    mov_viatura
							        left join
							    viaturas ON viaturas.id_viatura = mov_viatura.id_viatura
							where
							    viaturas.acessorio = 0 and mov_viatura.horas_viatura > 0 and mov_viatura.data between '".$data_inicial."' and '".$data_final."'
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
				<th>Horas Contador</th>
				<th>Min</th>
				<th>Max</th>
			</thead>
			<tbody>';
	
	//array para mostrar na tabela os resultados
	for($i=0;$i<$tamanho_array;$i++)
	{
		echo '<tr>
				<td>'.$listagem[$i]["desc_viatura"].'</td>
				<td>'.$listagem[$i]["dias_trabalho"].'</td>
			    <td style="text-align:right;color:'.cor_horas($listagem[$i]["total_horas"]).'">'.round(($listagem[$i]["total_horas"]/60),2).' Horas</td>
			    <td>'.round($listagem[$i]["preco_total"],2).' Euros</td>';

						//obter valores do contador do combustivel
						//TODO: melhorar desempenho
			    		$q_valores_contador = "select 
												    mov_combustivel.id_viatura,
												    (max(mov_combustivel.kms_viatura) - min(mov_combustivel.kms_viatura)) as contador_viatura,
												    max(mov_combustivel.kms_viatura) as 'max_contador',
												    min(mov_combustivel.kms_viatura) as 'min_contador'
												from
												    mov_combustivel
												where
												    mov_combustivel.kms_viatura > 0 and mov_combustivel.id_viatura = ".$listagem[$i]["id_viatura"]." and mov_combustivel.valor_movimento > 0 and mov_combustivel.data between '".$data_inicial."' and '".$data_final."'";
			    		$r_valores_contador = mysql_query($q_valores_contador);
			    		$n_valores_contador = mysql_num_rows($r_valores_contador);
			    		
			    		$r_valor_minimo = mysql_query("select 
														    mov_combustivel.id_movcombustivel as id_movimento,
			    											mov_combustivel.kms_viatura as valor
														from
														    mov_combustivel
														where
														    mov_combustivel.kms_viatura > 0 and mov_combustivel.id_viatura = ".$listagem[$i]["id_viatura"]." and mov_combustivel.valor_movimento > 0 and mov_combustivel.data between '".$data_inicial."' and '".$data_final."'
														order by  mov_combustivel.kms_viatura asc");
			    		$n_valor_minimo = mysql_num_rows($r_valor_minimo);			    		
			    		 
			    if($n_valor_minimo > 0)
			    {
			    	echo '
				    <td>'.mysql_result($r_valores_contador, 0,'contador_viatura').'</td>
				   	<td><a href="./index.php?pagina=editarcomb&id='.mysql_result($r_valor_minimo, 0,'id_movimento').'">'.mysql_result($r_valores_contador, 0,'min_contador').'</td>
				    <td><a href="./index.php?pagina=editarcomb&id='.mysql_result($r_valor_minimo, ($n_valor_minimo-1),'id_movimento').'">'.mysql_result($r_valores_contador, 0,'max_contador').'</td>
				  </tr>
					';
			    }else{
			    	echo '<td></td>
			    			<td></td>
			    			<td></td>
			    			</tr>';
			    }
			    
	}
	
	echo '</tbody>
		</table>';
?>