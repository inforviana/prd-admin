<?php
@$id_funcionario=$_GET['idfuncionario'];
@$id_viatura=$_GET['idviatura'];
@$procura=$_GET['procura'];
@$texto_pesquisa = $_POST['inp_pesquisa'];

//formatar datas
if(isset($_POST['data_inicio'])&&(strlen($_POST['data_inicio'])>1))
{
    $di_nf = explode("/",$_POST['data_inicio']);
    $df_nf = explode("/",$_POST['data_fim']);
    
    $di = $di_nf[2]."-".$di_nf[1]."-".$di_nf[0];
    $df = $df_nf[2]."-".$df_nf[1]."-".$df_nf[0]; 
}



if($id_funcionario>0){
	//obter detalhes do funcionario
	$q_dados="select * from funcionario where id_funcionario=".$id_funcionario;
	$r_dados=mysql_query($q_dados);
	$n_dados=mysql_num_rows($r_dados);
	echo '<b>Nome :: </b>'.mysql_result($r_dados,0,'nome_funcionario').'<br><b>Grupo :: </b>'.mysql_result($r_dados,0,'grupo_funcionario');
	echo '<br><br><br>
	<form method=POST action="index.php?procura=1&pagina=listagemavarias&idfuncionario='.$id_funcionario.'">';

	//teste codigo insercao data
	echo "
	<script>
		$(function() {
			$( '#datepicker_inicio' ).datepicker(\"option\",\"dateFormat\",\"yyyy-mm-dd\");
			$( '#datepicker_fim' ).datepicker(\"option\",\"dateFormat\",\"yyyy-mm-dd\");
		});
	</script>";
	echo '
    <input type="text" placeholder="texto a pesquisar" name="inp_pesquisa" style="width:400px; text-align:center; font-size: 24px;"> 
    <br>
Data Inicio: <input  name="data_inicio" size=7 id="datepicker_inicio" type="text"> -> 
Data Fim: <input  name="data_fim" size=7 id="datepicker_fim" type="text"><br>';

echo '
<button type="submit" value="Filtrar">Filtrar</button>
</form>';//selecao da data
	
	echo '<a id=d="hor-minimalist-b" href="index.php?pagina=listagemavarias&idfuncionario='.$id_funcionario.'">Todas as avarias</a>';	
	$condicao="where mov_avarias.id_funcionario=".$id_funcionario;
}else{
	//obter detalhes da viatura
	$q_dados="select * from viaturas where id_viatura=".$id_viatura;
	$r_dados=mysql_query($q_dados);
	$n_dados=mysql_num_rows($r_dados);
	
        /* BUGFIX CATEGORIA */
        $q_cat_vi="select * from categorias_viatura where id_categoria=".mysql_result($r_dados,0,'tipo_viatura');
        $r_cat_vi=mysql_query($q_cat_vi);
        /* FIM BUGFIX CATEGORIA */
        
        /* detalhes viatura */
	echo '
	<table><tr>
	<td><img class="img_viatura" src="imagem.php?idviatura='.mysql_result($r_dados,0,'id_viatura').'"></td>
	<td><b>
	Viatura :: </b>'.mysql_result($r_dados,0,'desc_viatura').'<br><b>
	Tipo :: </b>'.mysql_result($r_cat_vi,0,'categoria').'
	</tr></table>';
	echo '<br><br><br><form method=POST action="index.php?procura=1&pagina=listagemavarias&idviatura='.$id_viatura.'">';
            
	//seleccao data
	echo "
	<script>
		$(function() {
			$( '#datepicker_inicio' ).datepicker();
			$( '#datepicker_fim' ).datepicker();
		});
	</script>";
	echo '
    <input type="text" placeholder="texto a pesquisar" name="inp_pesquisa" style="width:400px; text-align:center; font-size: 24px;"> 
    <br>
Data Inicio: <input  name="data_inicio" size=7 id="datepicker_inicio" type="text"> -> 
Data Fim: <input  name="data_fim" size=7 id="datepicker_fim" type="text"><br>';

echo '
<button type="submit" value="Filtrar">Filtrar</button>
</form>';
	echo '<a id=d="hor-minimalist-b" href="index.php?pagina=listagemavarias&idviatura='.$id_viatura.'">Todas as avarias</a>';	
	$condicao="where mov_avarias.id_viatura=".$id_viatura;
}



//ultimos registos do funcionario
	if($procura==1&&(strlen($_POST['data_inicio'])>1)){ //procura com data
		$q_mov_avarias="select mov_avarias.id_funcionario, mov_avarias.id_viatura, mov_avarias.id_avaria, date(mov_avarias.data) as 'dia', time(mov_avarias.data) as 'horas', mov_avarias.data, viaturas.desc_viatura, mov_avarias.categoria,mov_avarias.desc_avaria,mov_avarias.preco,mov_avarias.estado,mov_avarias.horas as 'tempo',funcionario.nome_funcionario
						from mov_avarias
						inner join viaturas on viaturas.id_viatura = mov_avarias.id_viatura
						inner join funcionario on funcionario.id_funcionario=mov_avarias.id_funcionario
						".$condicao."
						 and mov_avarias.desc_avaria like '%".$texto_pesquisa."%' and date(mov_avarias.data) >= '".@$di."' and date(mov_avarias.data) <= '".@$df."'
						order by date(mov_avarias.data) desc";	

	//totais custo e horas
		$q_totais_avarias="SELECT sum(mov_avarias.preco) as 'custo', (sum(mov_avarias.horas)/60) as 'horas', (sum(mov_avarias.horas)%60) as 'minutos'
					   FROM mov_avarias
					   ".$condicao."
					   and date(mov_avarias.data) >= '".@$di."' and date(mov_avarias.data) <= '".@$df."'";	
	}else{ //procura sem data
		$q_mov_avarias="select mov_avarias.id_funcionario, mov_avarias.id_viatura, mov_avarias.id_avaria, date(mov_avarias.data) as 'dia', time(mov_avarias.data) as 'horas', mov_avarias.data, viaturas.desc_viatura, mov_avarias.categoria,mov_avarias.desc_avaria,mov_avarias.preco,mov_avarias.estado,mov_avarias.horas as 'tempo',funcionario.nome_funcionario
						from mov_avarias
						inner join viaturas on viaturas.id_viatura = mov_avarias.id_viatura
						inner join funcionario on funcionario.id_funcionario=mov_avarias.id_funcionario
						".$condicao." and mov_avarias.desc_avaria like '%".$texto_pesquisa."%'
						order by date(mov_avarias.data) desc";	

		//totais custo e horas
		$q_totais_avarias="SELECT sum(mov_avarias.preco) as 'custo', (sum(mov_avarias.horas)/60) as 'horas', (sum(mov_avarias.horas)%60) as 'minutos'
					   FROM mov_avarias
					   ".$condicao;
					   					
	}
	$r_mov_avarias=mysql_query($q_mov_avarias); //resultados da query
	$n_mov_avarias=mysql_num_rows($r_mov_avarias); //numero de linhas
	
	$r_totais=mysql_query($q_totais_avarias);
	
	if(isset($di)){
		$entre_datas=" (entre $di e $df)";
	}else{
		$entre_datas="";
	}
	echo '<br><br>
	<div class="div_totais_avarias">
	Detalhes '.$entre_datas.'<hr>
	<b>Total Custo Material: </b>'.mysql_result($r_totais,0,0).' Euros
	<br><b>Total Horas Oficina: </b>'.intval(mysql_result($r_totais,0,1)).'H '.mysql_result($r_totais,0,2).'m
	</div>';
	
	//desenhar tabelas com os registos	
		echo '
		<table id="hor-minimalist-b" summary="motd">
		<thead>
		<tr>
			<th colspan="8">Avarias Registadas</th>
		</tr>
		<tr>
			<th></th>
			<th>Data e Hora</th>
			<th>Colaborador</th>
			<th>Descri��o</th>
			<th>Categoria</th>
			<th>Descri��o</th>
			<th>Custo</th>
			<th>Concluida</th>
			<th>Tempo Gasto</th>
			<th colspan=3>Opera��es</th>
		</tr>
		</thead>
		<tbody>';
		
		$data_rep="";
		for($i=0;$i<$n_mov_avarias;$i++){ //obter linhas dos movimentos
			if(mysql_result($r_mov_avarias,$i,'dia')!=$data_rep){
				echo '<tr><td style="color:white;background-color:#404040 ;" colspan=11><img src="./images/calendar.png"><font style="font-size:14;">'.mysql_result($r_mov_avarias,$i,'dia').'</font></td></tr>';
				$data_rep=mysql_result($r_mov_avarias,$i,'dia');
			}
			echo '<tr>
					<td>
						<img src="oficina.png" height="20" border=0>
					</td>
					<td>
						'.mysql_result($r_mov_avarias,$i,'horas').'
					</td>
					<td>
						<a href="index.php?pagina=listagemavarias&idfuncionario='.mysql_result($r_mov_avarias,$i,'id_funcionario').'">'.mysql_result($r_mov_avarias,$i,'nome_funcionario').'</a>
					</td>					
					<td>
						<a href="index.php?pagina=listagemavarias&idviatura='.mysql_result($r_mov_avarias,$i,'id_viatura').'">'.mysql_result($r_mov_avarias,$i,'desc_viatura').'</a>
					</td>
					<td>
						'.mysql_result($r_mov_avarias,$i,'categoria').'
					</td>
					<td>
						'.mysql_result($r_mov_avarias,$i,'desc_avaria').'
					</td>
					<td>
						'.mysql_result($r_mov_avarias,$i,'preco').' Eur
					</td>
					<td>
						'.mysql_result($r_mov_avarias,$i,'estado').'
					</td>
					<td>
						'.intval(mysql_result($r_mov_avarias,$i,'tempo')/60).'H '.intval(mysql_result($r_mov_avarias,$i,'tempo')%60).'M
					</td>				
					<td align="center">
						<a href="./index.php?pagina=editaravarias&id='.mysql_result($r_mov_avarias,$i,'id_avaria').'"><img src="editar.png" border=0></a>
					</td>
					<td align="center">
                                                                                ';
                                                                                 
                        
                                                                                /* parametros a passar para a pagina a seguir a funcao de apagar */
                                                                                if(isset($_GET['idviatura']))
                                                                                {
                                                                                    $filtro='idviatura='.$_GET['idviatura'];
                                                                                }
                                                                                
                                                                                if(isset($_GET['idfuncionario']))
                                                                                {
                                                                                    $filtro='idfuncionario='.$_GET['idfuncionario'];
                                                                                }
                                                                                 
                                                                                echo '
						<input type="image" onclick="apagar(\'./index.php?pagina=listagemavarias&'.$filtro.'&func=apagar&tipo=avarias&id='.mysql_result($r_mov_avarias,$i,'id_avaria').'\')" src="delete.gif">
					</td>								
			</tr>';
		}
		echo'
		</tbody>
	</table>
	';
?>