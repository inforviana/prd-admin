<?php
//query lista movimentos horas
	$q_mov_horas="select mov_viatura.id_movviatura, mov_viatura.data, funcionario.nome_funcionario, viaturas.desc_viatura, viaturas.marca_viatura, viaturas.modelo_viatura, mov_viatura.horas_viatura
from mov_viatura
inner join funcionario on funcionario.id_funcionario = mov_viatura.id_funcionario
inner join viaturas on viaturas.id_viatura = mov_viatura.id_viatura
where mov_viatura.horas_viatura > 0
order by mov_viatura.data desc
limit 5";
	$r_mov_horas=mysql_query($q_mov_horas); //resultados da query
	$n_mov_horas=mysql_num_rows($r_mov_horas); //numero de linhas
	
	echo '
	<br>
	<a href="#"  id="a_mostrar_horas" class="botao_splash">Ultimos Registos Horas</a>
	<br>
	<div id="tbl_horas1" style="width:820px;display:none;border:1px coral solid;">
	<table id="hor-minimalist-b" summary="motd">
		<thead>
		<tr>
			<th colspan="5">Últimos registos</th>
		</tr>
		<tr>
			<th>Tipo</th>
			<th>Data e Hora</th>
			<th>Funcionario</th>
			<th>Descrição</th>
			<th>Marca</th>
			<th>Modelo</th>
			<th>Horas</th>
			<th colspan=3>Operações</th>
		</tr>
		</thead>
		<tbody>';
		
		for($i=0;$i<$n_mov_horas;$i++){ //obter linhas dos ultimos movimentos
			echo '<tr>
					<td>
						<img src="camiao.png" height="20" border=0>
					</td>
					<td>
						'.mysql_result($r_mov_horas,$i,'data').'
					</td>
					<td>
						'.mysql_result($r_mov_horas,$i,'nome_funcionario').'
					</td>
					<td>
						'.mysql_result($r_mov_horas,$i,'desc_viatura').'
					</td>
					<td>
						'.mysql_result($r_mov_horas,$i,'marca_viatura').'
					</td>
					<td>
						'.mysql_result($r_mov_horas,$i,'modelo_viatura').'
					</td>
					<td>
						'.intval(mysql_result($r_mov_horas,$i,'horas_viatura')/60).'H '.(mysql_result($r_mov_horas,$i,'horas_viatura')%60).'m
					</td>
					<td align="center">
						<a href="./index.php?pagina=editarhoras&id='.mysql_result($r_mov_horas,$i,'id_movviatura').'"><img src="editar.png" border=0></a>
					</td>
					<td align="center">
						<input type="image" onclick="apagar(\'/admin/index.php?func=apagar&tipo=horas&id='.mysql_result($r_mov_horas,$i,'id_movviatura').'\')" src="delete.gif">
					</td>								
			</tr>';
		}
		echo'
		</tbody>
	</table>
	</div>
	';
?>