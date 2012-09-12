<?php
//ponto dos funcionarios

if(isset($_POST['data_ponto'])){ //verifica se ha passagem de dados para ver a data
	$data_ponto=$_POST['data_ponto'];
}else{
	$data_ponto=date('Y-m-j');
}

echo '<h1>Ponto dos Funcionarios</h1><br><br>'; //titulo

//query
$q_ponto_funcionarios="select mov_viatura.id_funcionario, funcionario.nome_funcionario, (sum(mov_viatura.horas_viatura)/60) as 'horas' 
from mov_viatura
join funcionario on funcionario.id_funcionario = mov_viatura.id_funcionario
where date(mov_viatura.data)='".$data_ponto."'
group by mov_viatura.id_funcionario 
order by funcionario.nome_funcionario asc";

$r_ponto_funcionarios=mysql_query($q_ponto_funcionarios);
$n_ponto_funcionarios=mysql_num_rows($r_ponto_funcionarios);

//codigo insercao data
	echo "
	<script>
		$(function() {
			$( '#datepicker_inicio' ).datepicker();
			$( '#datepicker_fim' ).datepicker();
		});
	</script>";
	echo '<form action="index.php?pagina=ponto" method="POST"><input  name="data_ponto" value="'.$data_ponto.'" size=10 id="datepicker_inicio" type="text"><input type="image" src="./images/calendar.png" value="Procurar" alt="Procurar"></form>';

echo '<table><thead><th><u>Nome</u></th><th><u>Horas</u></th></thead>';
for($i=0;$i<$n_ponto_funcionarios;$i++){
	echo '<tr><td><a href="index.php?pagina=resumodia&funcionario='.mysql_result($r_ponto_funcionarios,$i,'id_funcionario').'&data='.$data_ponto.'"><img height=16 src="./images/check.png" border=0></a><font style="text-align:right;font-family:Arial, Helvetica, sans-serif;font-size:11px;">'.mysql_result($r_ponto_funcionarios,$i,'nome_funcionario').'</font></td><td valign="middle" align="center">'.intval(mysql_result($r_ponto_funcionarios,$i,'horas')).'</td></tr>';
}
echo '</table><br><br>';
?>