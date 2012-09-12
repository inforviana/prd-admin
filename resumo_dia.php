   <?php
	//resumo do dia por funcionario
	//
	//
	$id_funcionario=$_GET['funcionario'];
	$dia_registo=$_GET['data'];

	$q_funcionario="select * from funcionario where id_funcionario=".$id_funcionario;
	$r_funcionario=mysql_query($q_funcionario);
	
	//query do combustivel
	$q_combustivel="SELECT viaturas.desc_viatura, mov_combustivel.id_movcombustivel, mov_combustivel.kms_viatura, mov_combustivel.valor_movimento
	FROM mov_combustivel
	LEFT JOIN viaturas ON viaturas.id_viatura = mov_combustivel.id_viatura
	WHERE mov_combustivel.id_funcionario = ".$id_funcionario." AND DATE(mov_combustivel.data)='".$dia_registo."' and mov_combustivel.valor_movimento > 0";
	$r_combustivel=mysql_query($q_combustivel);
	$n_combustivel=mysql_num_rows($r_combustivel);

	
	//horas do funcionario
	$q_horas="SELECT mov_viatura.id_movviatura, viaturas.desc_viatura, mov_viatura.horas_viatura
	FROM mov_viatura
	LEFT JOIN viaturas ON viaturas.id_viatura = mov_viatura.id_viatura
	WHERE mov_viatura.id_funcionario = ".$id_funcionario." AND mov_viatura.horas_viatura > 0 and DATE(mov_viatura.data)='".$dia_registo."'";
	$r_horas=mysql_query($q_horas);
	@$n_horas=mysql_num_rows($r_horas);

	
	//avarias do funcionario
	$q_avarias="SELECT 
    viaturas.desc_viatura,
    mov_avarias.categoria,
    mov_avarias.desc_avaria,
    mov_avarias.horas
FROM
    mov_avarias
JOIN 
    viaturas ON viaturas.id_viatura=mov_avarias.id_viatura
WHERE
    mov_avarias.id_funcionario= ".$id_funcionario." AND DATE(mov_avarias.data)='".$dia_registo."'";
	$r_avarias=mysql_query($q_avarias);
	@$n_avarias=mysql_num_rows($r_avarias);

	
	/*TITULO*/
	echo '<font class="font_titulo_listagem">Relatório Diario</font> -  <font class="font_nome">'.mysql_result($r_funcionario,0,'nome_funcionario').'</font><br><br><u>'.$dia_registo.'</u><br><br>';

	/*	TABELA PARA AS TABELAS     */
	echo '<table border=0 ><tr class="tr_topo"><td >';
	/*TABELA DAS HORAS*/
	echo '<table class="tabela_resumo"><thead><th colspan="3" align="left"><i>Horas</i></th></thead><thead><th></th><th>Viatura</th><th>Horas</th></thead>';
	$total_horas=0;
	for($i=0;$i<$n_horas;$i++){
		$total_horas=$total_horas+mysql_result($r_horas,$i,'horas_viatura');
		$horas=intval(mysql_result($r_horas,$i,'horas_viatura')/60);
		$minutos=intval(mysql_result($r_horas,$i,'horas_viatura')%60);
		if(intval($minutos)==0){$minutos="00";}
		echo '<tr>
			<td width="40"><a href="index.php?pagina=editarhoras&id='.mysql_result($r_horas,$i,'id_movviatura').'"><img height="20"  border=0 src="camiao.png"></a></td>
			<td width="120" align="center"><font style="font-family:Arial, Helvetica, sans-serif;font-size:14px;">'.mysql_result($r_horas,$i,'desc_viatura').'</font></td>
			<td width="100" align="center">'.$horas.':'.$minutos.'</td>
		</tr>';
	}
	$ht=intval($total_horas/60);
	$mt=intval($total_horas%60);
	if($mt==0){$mt="00";}
	echo '<thead><th colspan="3">Total Horas: '.$ht.':'.$mt.'</th></thead>
	</table></font>';

	echo '</td><td >';
	
	/*TABELA DO COMBUSTIVEL*/
	echo '<table class="tabela_resumo"><thead><th style="background-color:light-gray;"colspan="4" align="left"><i>Combustivel</i></th></thead><thead><th></th><th>Viatura</th><th>Horas/Kms</th><th>Litros</th></thead>';
	for($i=0;$i<$n_combustivel;$i++){
		echo '<tr>
			<td width="50"><a href="index.php?pagina=editarcomb&id='.  mysql_result($r_combustivel, $i, 'id_movcombustivel').'"><img height="20" src="gasoleo.png"></a></td><td width="100" align="center"><font class="font_topo">'.mysql_result($r_combustivel,$i,'desc_viatura').'</font></td>
			<td width="150" align="center"><font class="font_topo">'.mysql_result($r_combustivel,$i,'kms_viatura').' H/Kms</font></td>
			<td><font class="font_topo">'.mysql_result($r_combustivel,$i,'valor_movimento').' Litros</font></td>
		</tr>';
	}
	echo '</table>';
	
	echo '</td></tr><tr class="tr_topo"><td colspan="2" align="center">';
	
	/* TABELA DA OFICINA */
	echo '<br><table class="tabela_resumo"><thead><th colspan="5" align="left"><i>Avarias</i></th></thead><thead><th></th><th>Viatura</th><th>Tipo</th><th>Descrição</th><th>Tempo Gasto</th></thead>';
	if($n_avarias>0){ //se numero de avarias maior que 0 desenha a tabela das avarias
            for($i=0;$i<$n_avarias;$i++){ //preenche a tabela com as avarias
                    @$horas=intval(mysql_result($r_avarias,$i,'horas')/60);
                    @$minutos=intval(mysql_result($r_avarias,$i,'horas')%60);
                    if(intval($minutos)==0){$minutos="00";}
                    echo '<tr>
                            <td width="50"><img height="20" src="oficina.png"></td><td width="100" align="center"><font class="font_topo">'.@mysql_result($r_avarias,$i,'desc_viatura').'</font></td>
                            <td width="150" align="center"><font class="font_topo">'.@mysql_result($r_avarias,$i,'categoria').' </font></td>
                            <td><font class="font_topo">'.@mysql_result($r_avarias,$i,'desc_avaria').' </font></td>
                            <td style="text-align:center;"><font class="font_topo">'.@$horas.'H'.@$minutos.'m</font></td>
                    </tr>';
            }
        }
	echo '</table>';
	
	/* FIM DA TABELA PARA AS TABELAS*/
	echo '</td></tr>  
</table>';
          require 'graficos/horas_funcionario.php';
?>