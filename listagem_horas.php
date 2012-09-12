<?php 
@$id_funcionario=$_GET['idfuncionario'];
 @$id_viatura=$_GET['idviatura'];
@$procura=$_GET['procura'];
@$di=$_POST['data_inicio'];
@$df=$_POST['data_fim'];

if ($id_funcionario>0){
	//obter detalhes do funcionario
	$q_dados="select * from funcionario where id_funcionario=".$id_funcionario;
	$r_dados=mysql_query($q_dados);
	$n_dados=mysql_num_rows($r_dados);
	echo '<b>Nome: </b>'.mysql_result($r_dados,0,'nome_funcionario').'<br><b>Grupo: </b>'.mysql_result($r_dados,0,'grupo_funcionario');
	echo '<br><br><br><form method=POST action="index.php?procura=1&pagina=listagemhoras&idfuncionario='.$id_funcionario.'">';

	//teste codigo insercao data
	echo "
	<script>
		$(function() {
			$( '#datepicker_inicio' ).datepicker();
			$( '#datepicker_fim' ).datepicker();
		});
	</script>";
	echo '
Data Inicio: <input  name="data_inicio" size=7 id="datepicker_inicio" type="text"> -> 
Data Fim: <input  name="data_fim" size=7 id="datepicker_fim" type="text"><br>';

echo '
<button type="submit" value="Filtrar">Filtrar</button>
</form>';
	echo '<a id="hor-minimalist-b" href="index.php?pagina=listagemhoras&idfuncionario='.$id_funcionario.'">Ver todos os registos</a>';
	$condicao="where mov_viatura.id_funcionario=".$id_funcionario;	
}else{
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
		
	echo '<br><br><br><form method=POST action="index.php?procura=1&pagina=listagemhoras&idviatura='.$id_viatura.'">';

	//teste codigo insercao data
	echo "
	<script>
		$(function() {
			$( '#datepicker_inicio' ).datepicker();
			$( '#datepicker_fim' ).datepicker();
		});
	</script>";
	echo '
Data Inicio: <input  name="data_inicio" size=7 id="datepicker_inicio" type="text"> -> 
Data Fim: <input  name="data_fim" size=7 id="datepicker_fim" type="text"><br>';

echo '
<button type="submit" value="Filtrar">Filtrar</button>
</form>';
	echo '<a id="hor-minimalist-b" href="index.php?pagina=listagemhoras&idviatura='.$id_viatura.'">Ver todos os registos</a>';	
	$condicao="where mov_viatura.id_viatura=".$id_viatura;
}



//ultimos registos do funcionario
	if($procura==1){
		$q_mov_horas="select time(mov_viatura.data) as 'horas',date(mov_viatura.data) as 'dia', mov_viatura.id_funcionario,mov_viatura.id_viatura, mov_viatura.id_movviatura, mov_viatura.data, funcionario.nome_funcionario, viaturas.desc_viatura, viaturas.marca_viatura, viaturas.modelo_viatura, mov_viatura.horas_viatura
			from mov_viatura
			inner join funcionario on funcionario.id_funcionario = mov_viatura.id_funcionario
			inner join viaturas on viaturas.id_viatura = mov_viatura.id_viatura
			".$condicao." and mov_viatura.horas_viatura > 0
			and date(mov_viatura.data) >= '".@$di."' and date(mov_viatura.data) <= '".@$df."'
			order by date(mov_viatura.data) desc";	
 
		$q_totais="select (sum(horas_viatura)/60) as 'horas', (sum(horas_viatura)%60) as 'minutos' 
			from mov_viatura
			inner join funcionario on funcionario.id_funcionario = mov_viatura.id_funcionario
			inner join viaturas on viaturas.id_viatura = mov_viatura.id_viatura
			".$condicao." and mov_viatura.horas_viatura > 0
			and date(mov_viatura.data) >= '".@$di."' and date(mov_viatura.data) <= '".@$df."'
			order by date(mov_viatura.data) desc";	
	}else{
		$q_mov_horas="select  time(mov_viatura.data) as 'horas',date(mov_viatura.data) as 'dia', mov_viatura.id_funcionario, mov_viatura.id_viatura, mov_viatura.id_movviatura, mov_viatura.data, funcionario.nome_funcionario, viaturas.desc_viatura, viaturas.marca_viatura, viaturas.modelo_viatura, mov_viatura.horas_viatura
			from mov_viatura
			inner join funcionario on funcionario.id_funcionario = mov_viatura.id_funcionario
			inner join viaturas on viaturas.id_viatura = mov_viatura.id_viatura
			".$condicao." and mov_viatura.horas_viatura > 0
			order by date(mov_viatura.data) desc";		
			
		$q_totais="select (sum(horas_viatura)/60) as 'horas', (sum(horas_viatura)%60) as 'minutos' 
			from mov_viatura
			inner join funcionario on funcionario.id_funcionario = mov_viatura.id_funcionario
			inner join viaturas on viaturas.id_viatura = mov_viatura.id_viatura
			".$condicao." and mov_viatura.horas_viatura > 0
			order by date(mov_viatura.data) desc";	
	}
		
	$r_mov_horas=mysql_query($q_mov_horas); //resultados da query
	$n_mov_horas=mysql_num_rows($r_mov_horas); //numero de linhas

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
			<th>Horas</th>
			<th colspan=3>Operações</th>
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
						<a href="index.php?pagina=listagemhoras&idfuncionario='.mysql_result($r_mov_horas,$i,'id_funcionario').'">'.mysql_result($r_mov_horas,$i,'nome_funcionario').'</a>
					</td>
					<td>
						<a href="index.php?pagina=listagemhoras&idviatura='.mysql_result($r_mov_horas,$i,'id_viatura').'">'.mysql_result($r_mov_horas,$i,'desc_viatura').'</a>
					</td>
					<td>
						'.intval(mysql_result($r_mov_horas,$i,'horas_viatura')/60).'H '.(mysql_result($r_mov_horas,$i,'horas_viatura')%60).'M
					<td align="center">
						<a href="./index.php?pagina=editarhoras&id='.mysql_result($r_mov_horas,$i,'id_movviatura').'"><img src="editar.png" border=0></a>
					</td>
					<td align="center">
						<input type="image" onclick="apagar(\'/admin/index.php?pagina=listagemhoras&idfuncionario='.$id_funcionario.'&func=apagar&tipo=horas&id='.mysql_result($r_mov_horas,$i,'id_movviatura').'\')" src="delete.gif">
					</td>								
			</tr>';
		}
		echo'
		</tbody>
	</table>
	';
?>