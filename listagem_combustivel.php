<?php
@$id_funcionario=$_GET['idfuncionario'];
@$id_viatura=$_GET['idviatura'];
@$procura=$_GET['procura'];
@$di=$_POST['data_inicio'];
@$df=$_POST['data_fim'];


//definir a font a usar
echo '<font style="font-family:Arial, Helvetica, sans-serif;font-size:14px;">';
	if ($id_funcionario>0){
			//obter detalhes do funcionario-----------------------------------------------------
		$q_dados="select * from funcionario where id_funcionario=".$id_funcionario;
		$r_dados=mysql_query($q_dados);
		$n_dados=mysql_num_rows($r_dados);
		echo '<b>Nome: </b>'.mysql_result($r_dados,0,'nome_funcionario').'<br><b>Grupo: </b>'.mysql_result($r_dados,0,'grupo_funcionario');
		echo '<br><br><br><form method=POST action="index.php?procura=1&pagina=listagemcombustivel&idfuncionario='.$id_funcionario.'">';

			//codigo insercao data
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
		</form>'; //botao filtrar
		echo '<a id="hor-minimalist-b" href="index.php?pagina=listagemcombustivel&idfuncionario='.$id_funcionario.'">Ver todos os movimentos</a>'; //ver todos os movimentos
		$condicao="where mov_combustivel.id_funcionario=".$id_funcionario;
	}else{
			//obter detalhes da viatura--------------------------------------------------------
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
		
		echo '<br><br><br><form method=POST action="index.php?procura=1&pagina=listagemcombustivel&idviatura='.$id_viatura.'">
		';

			//teste codigo insercao data
			echo "
			<script>
				$(function() {
					$( '#datepicker_inicio' ).datepicker();
					$( '#datepicker_fim' ).datepicker();
				});
			</script>";
			echo '
		Data Inicio: <input  name="data_inicio" size=10 id="datepicker_inicio" type="text"> -> 
		Data Fim: <input  name="data_fim" size=10 id="datepicker_fim" type="text"><br>';

		echo '
		<button type="submit" value="Filtrar">Filtrar</button>
		</form></font>';
		echo '<a id="hor-minimalist-b" href="index.php?pagina=listagemcombustivel&idviatura='.$id_viatura.'">Ver todos os movimentos</a>';
		$condicao="where mov_combustivel.id_viatura=".$id_viatura;
	}

		//calcular totais dos kms/horas e litros-------------------------------------
		if($procura==1){
			//query com data
		$q_soma_combustivel="select mov_combustivel.id_movcombustivel, mov_combustivel.data, (max(mov_combustivel.kms_viatura)-min(mov_combustivel.kms_viatura)) as 'dif', sum(mov_combustivel.valor_movimento) as 'soma_litros', viaturas.desc_viatura, funcionario.nome_funcionario
			from mov_combustivel
			inner join viaturas on viaturas.id_viatura = mov_combustivel.id_viatura
			inner join funcionario on funcionario.id_funcionario=mov_combustivel.id_funcionario
			".$condicao."
			and mov_combustivel.valor_movimento > 0 and mov_combustivel.kms_viatura > 0
			and date(mov_combustivel.data) >= '".@$di."' and date(mov_combustivel.data) <= '".@$df."'
			order by date(mov_combustivel.data) desc";		
		$q_ultimo_combustivel="select valor_movimento from mov_combustivel ".$condicao." and date(mov_combustivel.data) >= '".@$di."' and date(mov_combustivel.data) <= '".@$df."' and valor_movimento > 0 order by date(data) asc limit 1 ";
		}else{
		//query sem data
		$q_ultimo_combustivel="select valor_movimento from mov_combustivel ".$condicao." and valor_movimento > 0 order by date(data) asc limit 1 ";
		$q_soma_combustivel="select mov_combustivel.id_movcombustivel, mov_combustivel.data, (max(mov_combustivel.kms_viatura)-min(mov_combustivel.kms_viatura)) as 'dif', sum(mov_combustivel.valor_movimento) as 'soma_litros', viaturas.desc_viatura, funcionario.nome_funcionario
			from mov_combustivel
			inner join viaturas on viaturas.id_viatura = mov_combustivel.id_viatura
			inner join funcionario on funcionario.id_funcionario=mov_combustivel.id_funcionario
			".$condicao."
			and mov_combustivel.valor_movimento > 0 and mov_combustivel.kms_viatura > 0
			order by date(mov_combustivel.data) desc";		
		}
		$r_soma_combustivel=mysql_query($q_soma_combustivel);
		$n_soma_combustivel=mysql_num_rows($r_soma_combustivel);
		//--------------------------------------------------------------------------
		//---------
		/*obter ultima leitura do combustivel*/
		$r_ultimo_combustivel=mysql_query($q_ultimo_combustivel);
		@$ultimos_litros=mysql_result($r_ultimo_combustivel,0,0);
		//----------
		
		$media_100=@round(((((mysql_result($r_soma_combustivel, 0,'soma_litros')-$ultimos_litros)*100)/mysql_result($r_soma_combustivel, 0,'dif'))), 2);
		
		//verifica que tipo de valor é
		if($media_100>70){
			$media_ver=$media_100/100;
			$uni='L\Hora';
		}else{
			$media_ver=$media_100;
			$uni='L\100KM';
		}
		
		//echo $q_ultimo_combustivel;
		echo'
		<table id="hor-minimalist-b" summary="motd">
		<th colspan=8><td></td></th>
		<tr>
			<td colspan=4 align="right">Totais:</td>
			<td align="center">'.mysql_result($r_soma_combustivel, 0,'dif').' H/Kms</td>
			<td align="center"> Total Litros :: '.mysql_result($r_soma_combustivel, 0,'soma_litros').' L</td>
			<td colspan=2></td>
		</tr>
		<tr>		
			<td align="right" colspan=6>Consumo Estimado :: <b>'.$media_ver.'</b> '.$uni.'</td>
			<td colspan=2></td>	
		</tr>
		</table>';



//ultimos registos do funcionario
	if($procura==1){
		//query com data
		$q_mov_combustivel="select mov_combustivel.id_viatura, mov_combustivel.id_funcionario, mov_combustivel.id_movcombustivel, time(mov_combustivel.data) as 'horas', date(mov_combustivel.data) as 'dia', mov_combustivel.data, mov_combustivel.kms_viatura, mov_combustivel.valor_movimento, viaturas.desc_viatura, funcionario.nome_funcionario
from mov_combustivel
inner join viaturas on viaturas.id_viatura = mov_combustivel.id_viatura
inner join funcionario on funcionario.id_funcionario=mov_combustivel.id_funcionario
".$condicao."
and mov_combustivel.valor_movimento > 0
and date(mov_combustivel.data) >= '".@$di."' and date(mov_combustivel.data) <= '".@$df."'
order by mov_combustivel.data desc";		
	}else{
		//query sem data
		$q_mov_combustivel="select mov_combustivel.id_viatura, mov_combustivel.id_funcionario, mov_combustivel.id_movcombustivel, time(mov_combustivel.data) as 'horas', date(mov_combustivel.data) as 'dia', mov_combustivel.data, mov_combustivel.kms_viatura, mov_combustivel.valor_movimento, viaturas.desc_viatura, funcionario.nome_funcionario
from mov_combustivel
inner join viaturas on viaturas.id_viatura = mov_combustivel.id_viatura
inner join funcionario on funcionario.id_funcionario=mov_combustivel.id_funcionario
".$condicao."
and mov_combustivel.valor_movimento > 0
order by mov_combustivel.kms_viatura desc";		
	}
		
	$r_mov_combustivel=mysql_query($q_mov_combustivel); //resultados da query
	$n_mov_combustivel=mysql_num_rows($r_mov_combustivel); //numero de linhas
	
	//desenhar tabelas com os registos	
		echo '<table id="hor-minimalist-b" summary="motd">
		<thead>
		<tr>
			<th colspan="5">Combustivel utilizado</th>
		</tr>
		<tr>
			<th></th>
			<th>Data e Hora</th>
			<th>Colaborador</th>
			<th>Viatura</th>
			<th>Horas / Kms</th>
			<th>Med. Diaria</th>
			<th>Litros</th>
			<th colspan=3>Operações</th>
		</tr>
		</thead>
		<tbody>';
		$dia_rel='';
		if($n_mov_combustivel>0){
		for($i=0;$i<$n_mov_combustivel;$i++){ //obter linhas dos movimentos
			if(mysql_result($r_mov_combustivel,$i,'dia')!=$dia_rel){ //cabecalho das datas
				echo '<tr><td style="color:white;background-color:#404040 ;" colspan=9><img src="./images/calendar.png"><font style="font-size:14;">'.mysql_result($r_mov_combustivel,$i,'dia').'</font></td></tr>';
				$dia_rel=mysql_result($r_mov_combustivel,$i,'dia');
			}
			
			//verificar qual a media diaria
				if($i<($n_mov_combustivel-1)){
					@$media_diaria=round(intval(mysql_result($r_mov_combustivel,$i,'valor_movimento'))/(intval(mysql_result($r_mov_combustivel,$i,'kms_viatura')-mysql_result($r_mov_combustivel,$i+1,'kms_viatura')))*100,2);
					@$media_anterior=round(intval(mysql_result($r_mov_combustivel,$i+1,'valor_movimento'))/(intval(mysql_result($r_mov_combustivel,$i+1,'kms_viatura')-mysql_result($r_mov_combustivel,$i+2,'kms_viatura')))*100,2);
					@$var_media=round((($media_diaria*100)/$media_100)-100,2);
				}else{
					$media_diaria=0;
				}
				
				//imagem da media do combustivel
				if($media_100>$media_diaria){
						$imgvar="./images/dn.gif";
					}else{
						$imgvar="./images/up.gif";
					}
				
			//que tipo de media é
				if($media_diaria>100){
					$media_diaria=$media_diaria/100;
					$ext="L/H";
				}else{
					$ext="L/100";
				}
				
				//media do combustivel
				if($var_media>20){
					$warn='<img src="./images/warning.jpeg" border=0>';
				}else{
					$warn="";
				}
                                
                                                                        if(isset($_GET['idfuncionario']))
                                                                        {
                                                                            $redir_apagar="idfuncionario=".$_GET['idfuncionario'];
                                                                        }else{
                                                                            $redir_apagar ="idviatura=".mysql_result($r_mov_combustivel,$i,'id_viatura');
                                                                        }
                                                                           
				
				echo '<tr>
						<td>
							<img src="gasoleo.png" height="20" border=0>
						</td>
						<td>
							'.mysql_result($r_mov_combustivel,$i,'horas').'
						</td>
						<td>
							<a href="index.php?pagina=listagemcombustivel&idfuncionario='.mysql_result($r_mov_combustivel,$i,'id_funcionario').'">'.mysql_result($r_mov_combustivel,$i,'nome_funcionario').'</a>
						</td>					
						<td>
							<a href="index.php?pagina=listagemcombustivel&idviatura='.mysql_result($r_mov_combustivel,$i,'id_viatura').'">'.mysql_result($r_mov_combustivel,$i,'desc_viatura').'</a>
						</td>
						<td align="center">
							'.mysql_result($r_mov_combustivel,$i,'kms_viatura').'
						</td>
							<td align="center">
							'.$media_diaria.' '.$ext.' <br><img src="'.$imgvar.'">'.abs($var_media).'% '.$warn.'
						</td>
						<td align="center">
							'.mysql_result($r_mov_combustivel,$i,'valor_movimento').' L
						</td>			
						<td align="center">
							<a href="./index.php?pagina=editarcomb&id='.mysql_result($r_mov_combustivel,$i,'id_movcombustivel').'"><img src="editar.png" border=0></a>
						</td>
						<td align="center">
							<input type="image" onclick="apagar(\'./index.php?pagina=listagemcombustivel&'.$redir_apagar.'&func=apagar&tipo=comb&id='.mysql_result($r_mov_combustivel,$i,'id_movcombustivel').'\')" src="delete.gif">
						</td>								
				</tr>';
		
		}
		}
		echo '
		</tbody>
	</table>';	
?>