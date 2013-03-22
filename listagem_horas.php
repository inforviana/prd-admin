<?php 

//verifica se esta definida a procura
if(isset($_GET['procura']))
	$procura=$_GET['procura'];

//verifica se e para ver detalhes do funcionario ou da viatura
if (isset($_GET['idfuncionario'])){
	
	$id_funcionario=$_GET['idfuncionario'];
	//obter detalhes do funcionario
	$q_dados="select * from funcionario where id_funcionario=".$id_funcionario;
	$r_dados=mysql_query($q_dados);
	$n_dados=mysql_num_rows($r_dados);
	echo '<b>Nome: </b>'.mysql_result($r_dados,0,'nome_funcionario').'<br><b>Grupo: </b>'.mysql_result($r_dados,0,'grupo_funcionario');
	echo '<br><br>';
	$condicao="where mov_viatura.id_funcionario=".$id_funcionario;
		
}elseif(isset($_GET['idviatura'])){
	
	$id_viatura=$_GET['idviatura'];
	//obter detalhes da viatura
	$q_dados="select * from viaturas where id_viatura=".$id_viatura;
	$r_dados=mysql_query($q_dados);
	$n_dados=mysql_num_rows($r_dados);
	
	echo '
		<table><tr>
		<td><img width=200 src="imagem.php?idviatura='.mysql_result($r_dados,0,'id_viatura').'"></td>
		<td><b>
		Viatura :: </b>'.mysql_result($r_dados,0,'desc_viatura').'<br><b>
		Tipo :: </b>'.mysql_result($r_dados,0,'tipo_viatura').'
		</tr></table>';
		
	echo '<br><br>';
    
	$condicao="where mov_viatura.id_viatura=".$id_viatura;
}



//ultimos registos do funcionario
		$q_mov_horas="select time(mov_viatura.data) as 'horas',date(mov_viatura.data) as 'dia', mov_viatura.id_funcionario,mov_viatura.id_viatura, mov_viatura.id_movviatura, mov_viatura.data, funcionario.nome_funcionario, viaturas.desc_viatura, viaturas.marca_viatura, viaturas.modelo_viatura, mov_viatura.horas_viatura, obras.descricao_obra
			from mov_viatura
			inner join funcionario on funcionario.id_funcionario = mov_viatura.id_funcionario
			inner join viaturas on viaturas.id_viatura = mov_viatura.id_viatura
			left join obras on obras.id_obra = mov_viatura.id_obra
			".$condicao." and mov_viatura.horas_viatura > 0
			and date(mov_viatura.data) >= '".$data_i."' and date(mov_viatura.data) <= '".$data_f."'
			order by date(mov_viatura.data) desc";	
 
		$q_totais="select (sum(horas_viatura)/60) as 'horas', (sum(horas_viatura)%60) as 'minutos' 
			from mov_viatura
			inner join funcionario on funcionario.id_funcionario = mov_viatura.id_funcionario
			inner join viaturas on viaturas.id_viatura = mov_viatura.id_viatura
			".$condicao." and mov_viatura.horas_viatura > 0 and viaturas.acessorio = 0
			and date(mov_viatura.data) >= '".$data_i."' and date(mov_viatura.data) <= '".$data_f."'
			order by date(mov_viatura.data) desc";	
		
	$r_mov_horas = mysql_query($q_mov_horas); //resultados da query
	$n_mov_horas = mysql_num_rows($r_mov_horas); //numero de linhas
	
	
	$r_totais_horas = mysql_query($q_totais);
	
	//tabela com os totais de horas e faturado
	echo '<table id="tabela_totais_horas">
			<tr>
				<td style="background-color: light-gray;border-bottom: 2px solid black;">Total Tempo Trabalhado :: </td>
				<td style="background-color: #FF9933;border-bottom: 2px solid black;"><font style="color:black;font-size:22px;">'.round(mysql_result($r_totais_horas, 0,0),0).'H'.round(mysql_result($r_totais_horas,0,'minutos'),0).'m</font></td>
			</tr>
		  </table>';

	//desenhar tabelas com os registos	
		echo '<table id="hor-minimalist-b" summary="motd">
		<thead>
		<tr>
			<th colspan="5">Horas Registadas</th>
		</tr>
		<tr>
			<th>Tipo</th>
			<th>Data e Hora</th>
			<th>Colaborador</th>
			<th>Viatura</th>
			<th>Obra</th>
			<th>Horas</th>
			<th colspan=3>Operacoes</th>
		</tr>
		</thead>
		<tbody>';
		
		$dia_horas="";
		for($i=0;$i<$n_mov_horas;$i++){ //obter linhas dos ultimos movimentos
			if(mysql_result($r_mov_horas,$i,'dia')!=$dia_horas){
				echo '<tr><td style="color:white;background-color:#404040 ;" colspan=8><img src="./images/calendar.png"><font style="font-size:14;">'.mysql_result($r_mov_horas,$i,'dia').'</font></td></tr>';
				$dia_horas=mysql_result($r_mov_horas,$i,'dia');
			}
			echo '<tr>
					<td>
						<img src="camiao.png" height="20" border=0>
					</td>
					<td>
						'.mysql_result($r_mov_horas,$i,'horas').'
					</td>
					
					<td>
						<a href="./index.php?pagina=listagemhoras&idfuncionario='.mysql_result($r_mov_horas,$i,'id_funcionario').'">'.mysql_result($r_mov_horas,$i,'nome_funcionario').'</a>
					</td>
					<td>
						<a href="./index.php?pagina=listagemhoras&idviatura='.mysql_result($r_mov_horas,$i,'id_viatura').'">'.mysql_result($r_mov_horas,$i,'desc_viatura').'</a>
					</td>
					<td style="text-align:center;">
						'.mysql_result($r_mov_horas,$i,'descricao_obra').'		
					</td>
					<td>
						'.intval(mysql_result($r_mov_horas,$i,'horas_viatura')/60).'H '.(mysql_result($r_mov_horas,$i,'horas_viatura')%60).'M
					</td>
					<td align="center">
						<a href="./index.php?pagina=editarhoras&id='.mysql_result($r_mov_horas,$i,'id_movviatura').'"><img src="editar.png" border=0></a>
					</td>
					<td align="center">
						<input type="image" onclick="apagar(\'./index.php?pagina=listagemhoras&idfuncionario='.$id_funcionario.'&func=apagar&tipo=horas&id='.mysql_result($r_mov_horas,$i,'id_movviatura').'\')" src="delete.gif">
					</td>								
			</tr>';
		}
		echo'
		</tbody>
	</table>
	';
?>