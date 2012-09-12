<?php
//query lista movimentos combustivel
	$q_mov_combustivel="select mov_combustivel.id_movcombustivel, mov_combustivel.data, funcionario.nome_funcionario, viaturas.desc_viatura, viaturas.marca_viatura, viaturas.modelo_viatura, mov_combustivel.valor_movimento
from mov_combustivel
inner join funcionario on funcionario.id_funcionario = mov_combustivel.id_funcionario
inner join viaturas on viaturas.id_viatura = mov_combustivel.id_viatura
where mov_combustivel.valor_movimento > 0
order by mov_combustivel.data desc
limit 5";
	$r_mov_combustivel=mysql_query($q_mov_combustivel);
	$n_mov_combustivel=mysql_num_rows($r_mov_combustivel);
	
	
		//TABELA DO COMBUSTIVEL (ULTIMOS 5 MOVIMENTOS)
	echo '
	<a href="#" id="a_mostrar_comb" class="botao_splash">Ultimos Registos Combustivel</a>
	<div id="tbl_comb1" style="width:820px;display:none;border:1px coral solid;">
	<table id="hor-minimalist-b" summary="motd">
		<thead>
		<tr>
			<th>Tipo</th>
			<th>Data e Hora</th>
			<th>Funcionario</th>
			<th>Descrição</th>
			<th>Marca</th>
			<th>Modelo</th>
			<th>Litros</th>
			<th colspan=3>Operações</th>
		</tr>
		</thead>
		<tbody>';
		for($i=0;$i<$n_mov_combustivel;$i++){ //obter linhas dos ultimos movimentos
			echo '<tr>
					<td>
						<img src="gasoleo.png" height="20" border=0>
					</td>
					<td>
						'.mysql_result($r_mov_combustivel,$i,'data').'
					</td>
					<td>
						'.mysql_result($r_mov_combustivel,$i,'nome_funcionario').'
					</td>
					<td>
						'.mysql_result($r_mov_combustivel,$i,'desc_viatura').'
					</td>
					<td>
						'.mysql_result($r_mov_combustivel,$i,'marca_viatura').'
					</td>
					<td>
						'.mysql_result($r_mov_combustivel,$i,'modelo_viatura').'
					</td>
					<td>
						'.mysql_result($r_mov_combustivel,$i,'valor_movimento').' L
					</td>
					<td align="center">
						<a href="./index.php?pagina=editarcomb&id='.mysql_result($r_mov_combustivel,$i,'id_movcombustivel').'"><img src="editar.png" border=0></a>
					</td>
					<td align="center">
						<input type="image" onclick="apagar(\'/admin/index.php?func=apagar&tipo=comb&id='.mysql_result($r_mov_combustivel,$i,'id_movcombustivel').'\')" src="delete.gif">
					</td>	
														
			</tr>';
		}
		echo'
		</tbody>
	</table>
	</div>
	';	
?>