<?php
	@$apagar=$_GET['apagar'];
	@$id=$_GET['id'];
	if($apagar==1){
		$q_apagar="DELETE FROM viaturas WHERE id_viatura=".$id;
		if (mysql_query($q_apagar)){
			$msg= '<font class="font_titulo"><img src="ok.gif">Viatura apagado com sucesso!</font>';
		}else{
			$msg='<font class="font_titulo_erro"><img src="erro.gif">Erro ao gravar as altera��es!</font>';
		}
	}
	@$p_viaturas=$_POST['procura'];
        
        if(isset($_POST['tipo_viatura']))
        {
            $cond=" AND tipo_viatura=".$_POST['tipo_viatura']." ";
        }else{
            $cond="";
        }
	$q_viaturas="select * from viaturas where (desc_viatura like '%".$p_viaturas."%' or marca_viatura like '%".$p_viaturas."%' or modelo_viatura like '%".$p_viaturas."%' or matricula_viatura like '%".$p_viaturas."%') ".$cond."  order by desc_viatura asc"; //query para seleccionar todos os viaturas
	$r_viaturas=mysql_query($q_viaturas);
	$n_viaturas=mysql_num_rows($r_viaturas);
	
	echo '<table width=700><tr><td>'.@$msg.'<br><b><img src="CAMIAO.gif">Viaturas</b></td>';
	echo '<td align="right"><form method="POST" action="index.php?pagina=viaturas"></td><td><input type="text" name="procura"><input type="image" src="lupa.gif" value="Procurar" alt="Procurar"></form></td></tr>
	</table><br>
        
        <table>
        <tr><td>
        <a href="index.php?pagina=editarviaturas&novo=1"><img src="novo.gif" border=0><font class="font_novo">Criar Viatura</font></a></td>';
	
        $r_tipo_viatura=mysql_query("select * from categorias_viatura");
        $n_tipo_viatura=mysql_num_rows($r_tipo_viatura);
        echo '<td style="width:300px;"></td><td><form method="POST" id="frm_cat" action="index.php?pagina=viaturas" >
                <select  name="tipo_viatura" onchange="javascript:document.getElementById(\'frm_cat\').submit()">
                    <option value="">**Categorias**</option>';
                
                for($i=0;$i<$n_tipo_viatura;$i++)
                {
                    echo '<option value="'.mysql_result($r_tipo_viatura,$i,'id_categoria').'">'.mysql_result($r_tipo_viatura,$i,'categoria').'</option>';
                }
        echo '</select></form></td></tr></table>';
        
        
	echo '
    <form method="POST" action="./index.php?pagina=grafico">
            <select name="tipo_grafico">
                <option value="avaliacao_global">Grafico Avaliacao Global</option>
            </select>
            <input type="submit" value="Mostrar Grafico">
    <table border=1 id="hor-minimalist-b" summary="motd">
	<thead>
                <th></th>
		<th></th>
		<th>Nome</th>
		<th>Tipo</th>
		<th>Marca</th>
		<th>Modelo</th>
		<th>Matricula</th>
		<th colspan=3>Operacoes</th>
	</thead>
	<tbody>'; 
	//inicio do loop de preenchimento da tabela
	for($i=0;$i<$n_viaturas;$i++){
		echo '<tr>';
                        echo '<td align="center"><input name="sel_viatura[]" value="'.mysql_result($r_viaturas,$i,'id_viatura').'" type="checkbox"></td>';
			echo '<td align="center"><a class="botao_detalhes" href="index.php?pagina=editarviaturas&id='.mysql_result($r_viaturas,$i,'id_viatura').'">Detalhes</a></td>';
			echo '<td>'.mysql_result($r_viaturas,$i,'desc_viatura').'</td>';
			echo '<td>';
			$qt="select categoria from categorias_viatura where id_categoria=".mysql_result($r_viaturas,$i,'tipo_viatura');
			$rt=mysql_query($qt);
			if (@mysql_num_rows($rt)>0){echo mysql_result($rt,0,'categoria');} //tipo de viatura
			echo '</td>';
			echo '<td>'.mysql_result($r_viaturas,$i,'marca_viatura').'</td>';
			echo '<td>'.mysql_result($r_viaturas,$i,'modelo_viatura').'</td>';
			echo '<td>'.mysql_result($r_viaturas,$i,'matricula_viatura').'</td>';
                                                      //echo '<td><a href="index.php?pagina=avaliacao&grafico=1&via='.mysql_result($r_viaturas,$i,'id_viatura').'"><img height=16 src="gasoleo.png" border=0></a></td>';
			echo '<td><a href="index.php?pagina=listagemcombustivel&idviatura='.mysql_result($r_viaturas,$i,'id_viatura').'"><img height=16 src="gasoleo.png" border=0></a></td>';
			echo '<td><a href="index.php?pagina=listagemavarias&idviatura='.mysql_result($r_viaturas,$i,'id_viatura').'"><img src="avarias.gif" border=0></a></td>';
			echo '<td><a href="index.php?pagina=listagemhoras&idviatura='.mysql_result($r_viaturas,$i,'id_viatura').'"><img src="grafico.gif" border=0></a></td>';			
			//echo '<td><input type="image" onclick="apagar(\'index.php?pagina=viaturas&apagar=1&id='.mysql_result($r_viaturas,$i,'id_viatura').'\')" src="delete.gif"></td>';
		echo '</tr>';
	}
	echo '</tbody></table></form>';
?>