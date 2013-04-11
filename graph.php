<?php
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_line.php');

require('config.php'); //carregar variaveis globais
	//ligar á base de dados
	mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
	//seleccionar a tabela a utilizar
	@mysql_select_db($DB_TABLE) or die('Erro de ligação á base de dados!');
	$q_horas_graph="select date(data),sum(horas_viatura),sum(mov_viatura.transporte) from mov_viatura join viaturas on mov_viatura.id_viatura=viaturas.id_viatura where id_funcionario=".$id." and viaturas.tipo_viatura <> 'Acessórios' group by day(data) order by date(data) desc limit 7";
	$r_horas_graph=mysql_query($q_horas_graph);
	
	
//
$datay1 = array(8,8,8,8,8,8,8);
$datay2 = array((mysql_result($r_horas_graph,6,1)/60),(mysql_result($r_horas_graph,5,1)/60),(mysql_result($r_horas_graph,4,1)/60),(mysql_result($r_horas_graph,3,1)/60),(mysql_result($r_horas_graph,2,1)/60),(mysql_result($r_horas_graph,1,1)/60),(mysql_result($r_horas_graph,0,1)/60));
$datay3 = array((mysql_result($r_horas_graph,6,2)/60),(mysql_result($r_horas_graph,5,2)/60),(mysql_result($r_horas_graph,4,2)/60),(mysql_result($r_horas_graph,3,2)/60),(mysql_result($r_horas_graph,2,2)/60),(mysql_result($r_horas_graph,1,2)/60),(mysql_result($r_horas_graph,0,2)/60));

// Setup the graph
$graph = new Graph(500,250);
$graph->SetScale("textlin");

$theme_class=new UniversalTheme;

$graph->SetTheme($theme_class);
$graph->img->SetAntiAliasing(false);
$tit_graph1="Horas do Funcionario ".$_COOKIE['nome_funcionario'];
$graph->title->Set($tit_graph1);
$graph->SetBox(false);

$graph->img->SetAntiAliasing();

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetTickLabels(array(mysql_result($r_horas_graph,6,0),mysql_result($r_horas_graph,5,0),mysql_result($r_horas_graph,4,0),mysql_result($r_horas_graph,3,0),mysql_result($r_horas_graph,2,0),mysql_result($r_horas_graph,1,0),mysql_result($r_horas_graph,0,0)));
$graph->xgrid->SetColor('#E3E3E3');

// Create the first line
$p1 = new LinePlot($datay1);
$graph->Add($p1);
$p1->SetColor("#6495ED");
$p1->SetLegend('Previstas');

// Create the second line
$p2 = new LinePlot($datay2);
$graph->Add($p2);
$p2->SetColor("#B22222");
$p2->SetLegend('Efectuadas');

// Create the third line
$p3 = new LinePlot($datay3);
$graph->Add($p3);
$p3->SetColor("#FF1493");
$p3->SetLegend('Deslocacoes');

$graph->legend->SetFrameWeight(1);

// Output line
$graph->Stroke();
?>