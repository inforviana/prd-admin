<?php // content="text/plain; charset=utf-8"
require("config.php"); //ficheiro de configuraчуo (diferente do PRD)

	//ligar с base de dados
	mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
	//seleccionar a tabela a utilizar
	@mysql_select_db($DB_TABLE) or die('Erro de ligaчуo с base de dados!');
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_line.php');



//GRAFICO--------------------------------------------------------------------
//basta modificar aqui as variaveis :)

//variaveis globais do grafico
$titulo="Totais Horas Faturadas";
$legenda1="Numero de Horas Faturadas";

//obter dados
$q_total_comb_diario="select day(data) as 'dia', month(data) as 'mes',sum(mov_viatura.horas_viatura) as 'total' from mov_viatura group by date(data) order by data desc limit 7";
$r_total_comb_diario=mysql_query($q_total_comb_diario);
$n_total_comb_diario=mysql_num_rows($r_total_comb_diario);


//preencher array Y
$datay1 = array();
$descx = array();

$j=0;
for($i=$n_total_comb_diario-1;$i>=0;$i--){
	$datay1[$j]= intval(mysql_result($r_total_comb_diario, $i,'total')/60);
	$descx[$j]=mysql_result($r_total_comb_diario, $i,'dia').'/'.mysql_result($r_total_comb_diario, $i,'mes');
	$j++;
}
//-----------------------------------------------------------------------------------------------------



// Setup the graph
$graph = new Graph(350,250);
$graph->SetScale("textlin");

$theme_class= new UniversalTheme;
$graph->SetTheme($theme_class);

$graph->title->Set($titulo);
$graph->SetBox(false);

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

$graph->xaxis->SetTickLabels($descx);
$graph->ygrid->SetFill(false);

$p1 = new LinePlot($datay1);
$graph->Add($p1);

$p1->SetColor("#ff0000");
$p1->SetLegend($legenda1);
$p1->mark->SetType(MARK_FILLEDCIRCLE,'',1.0);
$p1->mark->SetColor('#55bbdd');
$p1->mark->SetFillColor('#55bbdd');
$p1->value->SetFormat('%d');
$p1->value->Show();
$p1->SetCenter();


$graph->legend->SetFrameWeight(1);
$graph->legend->SetColor('#4E4E4E','#00A78A');
$graph->legend->SetMarkAbsSize(8);


// Output line
$graph->Stroke();

?>