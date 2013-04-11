<?php

if(isset($_GET['eu'])) /* eliminar utilizacao */
	{
		mysql_query("delete from utilizacao where id_utilizacao=".$_GET['eu']);
	}

if(isset($_GET['et'])) /* eliminar tubo */
	{
		$q_pt="select * from utilizacao where id_tubo=".$_GET['et'];
		$r_pt=mysql_query($q_pt);
		$n_pt=mysql_num_rows($r_pt);
		
		if($n_pt>0)
		{
			echo '<script>alert(\'Nao é possivel eliminar tubo!\nEm utilizacao numa viatura.\')</script>';
		}else{
			$tubo=$_GET['et'];
			mysql_query("delete from tubos where referencia_tubo=".$tubo);
			mysql_query("delete from componentes_tubos where ref_tubo=".$tubo);
		}
	}
	
if(isset($_GET['ec'])) /* eliminar componente */ 
	{
		$comp=$_GET['ec'];
		mysql_query("delete from componentes_tubos where id_componentes_tubos=".$comp);
	}
	
if(isset($_GET['ac'])) /* adicionar componente */ 
	{
		mysql_query("INSERT INTO componentes_tubos (ref_tubo, id_tipo_componente_tubo, componente, qtd_componente) VALUES (".$_POST['rt'].",".$_POST['tc'].",'".$_POST['desc_c']."',".$_POST['qtd_c'].")");
	}

	
if(isset($_POST['ordem']))
	{
            $ordem=$_POST['ordem'];
            switch($ordem)
            {
                case 'utilizacao':
                    $q_tubos="SELECT tubos.referencia_tubo, tubos.id_tubo, cmp.datau
                                FROM tubos
                                LEFT JOIN(
                                SELECT MAX(DATA) as 'datau', utilizacao.id_tubo
                                FROM utilizacao
                                GROUP BY id_tubo
                                ORDER BY DATA DESC
                                ) as cmp on cmp.id_tubo=tubos.referencia_tubo
                                order by cmp.datau desc";
                    break;
                    
                    
                case 'referencia':
                    $q_tubos="select * from tubos order by referencia_tubo desc"; //seleccionar todos os tubos por ordem numerica
                    break; 
                
                case 'criacao':
                    $q_tubos="select * from tubos order by id_tubo desc";
                    break;
            }
            	
	}else{
		$q_tubos="SELECT tubos.referencia_tubo, tubos.id_tubo, cmp.datau
                                FROM tubos
                                LEFT JOIN(
                                SELECT MAX(DATA) as 'datau', utilizacao.id_tubo
                                FROM utilizacao
                                GROUP BY id_tubo
                                ORDER BY DATA DESC
                                ) as cmp on cmp.id_tubo=tubos.referencia_tubo
                                order by cmp.datau desc"; //seleccionar todos os tubos
	}
$r_tubos=mysql_query($q_tubos);
$n_tubos=mysql_num_rows($r_tubos);

echo '
	<div id="div_tubos" class="div_tubos">
		<h1>LISTAGEM DE TUBOS</h1>
		<hr>
	<form action="index.php?pagina=tubos" method="POST">
            <label>Ordenar </label>
                <select name="ordem" onchange="javascript:this.form.submit();">
                    <option value=""></option>
                    <option value="utilizacao">Ultima Utilizacao</option>
                    <option value="referencia">Referencia</option>
                    <option value="criacao">Data de Criação</option>
                </select>
        </form><br>
	</div>
	<div id="div_tubos" class="div_tubos">
		<table class="tab_tubos">
			<thead>
				<th>Num. Tubo</th>
				<th>Componentes</th>
				<th>Operacoes</th>
			</thead>
			<tbody>
				';
				
				for($i=0;$i<$n_tubos;$i++)
				{
					echo '<tr>
							<td><h1>'.mysql_result($r_tubos,$i,'referencia_tubo').'</h1></td>
							<td>';
								$q_c="SELECT componentes_tubos.ref_tubo, componentes_tubos.qtd_componente, tipos_componentes_tubos.tipo_componente, componentes_tubos.componente, componentes_tubos.id_componentes_tubos
										FROM componentes_tubos
										JOIN tipos_componentes_tubos ON tipos_componentes_tubos.id_tipo_componente = componentes_tubos.id_tipo_componente_tubo
										WHERE componentes_tubos.ref_tubo=".mysql_result($r_tubos,$i,'referencia_tubo')." 
                                                                                ORDER BY componentes_tubos.id_componentes_tubos";
								$r_c=mysql_query($q_c);
								$n_c=mysql_num_rows($r_c);
								
								for($j=0;$j<$n_c;$j++)
								{
									echo mysql_result($r_c,$j,'qtd_componente').' X '.mysql_result($r_c,$j,'tipo_componente').' '.mysql_result($r_c,$j,'componente').' <a href="index.php?pagina=tubos&ec='.mysql_result($r_c,$j,'id_componentes_tubos').'"><img height=14 border=0 src="delete.gif"></a><br>';
								}
							
							/* butoes */
							echo '</td>
							<td>
								<button onclick="window.location=\'index.php?pagina=tubos&u='.mysql_result($r_tubos,$i,'referencia_tubo').'\'"><label class="but_tub">Utilizacoes</label></button>
								<button onclick="ab_editar_tubo('.mysql_result($r_tubos,$i,'referencia_tubo').')"><label class="but_tub">Novo Comp.</label></button>
								<button  onclick="eliminar(\'index.php?pagina=tubos&et='.mysql_result($r_tubos,$i,'referencia_tubo').'\',\'Deseja eliminar o tubo?\')"><label class="but_tub">Eliminar</label></button>
							</td>
					</tr>
					';
				}
			echo '
			</tbody>
		</table>
	</div>
	
	<!-- dialogo para editar tubo -->
	<div id="div_editar_tubo" title="Adicionar Componente Tubo" class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable ui-resizable">
		<form id="form_tc" action="index.php?pagina=tubos&ac=1" method="POST">
		<center><label>Ref. </label><input id="rt" type="text" name="rt" readonly="readonly" class="input_ref_tubo"></center><br>
			<label>Tipo Componente</label> ';
						$q_c="select * from tipos_componentes_tubos order by tipo_componente";
						$r_c=mysql_query($q_c);
						$n_c=mysql_num_rows($r_c);
						
						echo '<select name="tc">';
						for($i=0;$i<$n_c;$i++)
						{
							echo '<option value="'.mysql_result($r_c,$i,'id_tipo_componente').'" '.$selected.'>'.mysql_result($r_c,$i,'tipo_componente').'</option>';
						}
						echo '</select>';

			echo '<br>
			<label>Quantidade</label>
			<select name="qtd_c">
				';
					for($h=1;$h<=6;$h++)
					{
						echo '<option value="'.$h.'">'.$h.'</option>';
					}
				echo' 
			</select>
			<br><br>
			<label>Descricao</label><br>
			<input type="text" name="desc_c" class="input_desc_comp">
			<center><br>
				<button type="submit">Guardar</button>
			</center>
		</form>
   </div>
   
   <!-- dialogo das utilizacoes dos tubos -->
	<div id="div_utilizacoes" title="Utilizacoes do Tubo '.$_GET['u'].'" class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable ui-resizable">
		<table class="tab_u_t">
			<thead>
				<th>Data</th>
				<th>Viatura</th>
				<th>Funcionario</th>
				<th>Preco</th>
				<th></th>
			</thead>
			<tbody>
	';
		if(isset($_GET['u'])) /* utilzacao a verificar */ 
			{
				$q_ut="SELECT date(utilizacao.`data`), utilizacao.preco, viaturas.desc_viatura, funcionario.nome_funcionario, utilizacao.id_utilizacao
						FROM utilizacao
						JOIN viaturas ON viaturas.id_viatura = utilizacao.id_viatura
						JOIN funcionario ON funcionario.id_funcionario = utilizacao.id_funcionario
						where utilizacao.id_tubo = ".$_GET['u'];
				$r_ut=mysql_query($q_ut);
				$n_ut=mysql_num_rows($r_ut);
				for($i=0;$i<$n_ut;$i++) 
				{ /* listagem das utilizacoes */
					echo '
					<tr>
						<td>'.mysql_result($r_ut,$i,0).'</td>
						<td>'.mysql_result($r_ut,$i,2).'</td>
						<td>'.mysql_result($r_ut,$i,3).'</td>
						<td>€ '.mysql_result($r_ut,$i,1).'</td>
						<td><button onclick="eliminar(\'index.php?pagina=tubos&eu='.mysql_result($r_ut,$i,4).'\',\'Deseja eliminar a utilizacao?\')"><label class="but_tub">Apagar</label></button></td>
					</tr>';
				}
			}
			if($n_ut==0){$msg_t="Nao foram encontradas utilizacoes do tubo.";}else{$msg_t="";}
	echo '
	</tbody>
	</table><br><br>
		<center><h1>'.$msg_t.'</h1></center>
	</div>
'
?>