<?php

/*
 *HORAS TRABALHADAS / COMBUSTIVEL
 * 
 */
echo '<h1>Relatorio Global por Viatura</h1><br><br>';

//intervalo de datas para o grafico
if(isset($_POST['ano'])) //ANO
{
    $ano=$_POST['ano'];
}else{
    $ano=date('Y');
}

if(isset($_POST['mes'])){
    if($_POST['mes']=="")
    {
        
    }else{
    $condm_comb=" AND month(mov_combustivel.data) = '".$_POST['mes']."' ";
    $condm_ava=" AND month(mov_avarias.data) = '".$_POST['mes']."' ";
    $condm_v=" AND month(mov_viatura.data) = '".$_POST['mes']."'";
    }
}

//categoria de viatura a filtrar para o grafico
if(isset($_POST['categoria']))
{
    if($_POST['categoria']=='all')
    {
        
    }else{
        $cond=$cond." AND viaturas.tipo_viatura=".$_POST['categoria']." ";
    }
}

//query
$q="SELECT viaturas.desc_viatura, IFNULL(SUM(((mov_viatura.horas_viatura)/60)*mov_viatura.preco_hora_normal),0) AS 'horasf' ,IFNULL(SUM(((mov_viatura.horas_viatura)/60)*mov_viatura.preco_viatura),0) AS 'horast', IFNULL(comb.totalc,0) as 'totalc', IFNULL(ava.totalava,0) as 'totalava'
FROM mov_viatura
join viaturas on viaturas.id_viatura = mov_viatura.id_viatura
left join (
	select mov_combustivel.id_viatura,(sum(mov_combustivel.valor_movimento)*1.15) as totalc 
	from mov_combustivel 
        where year(mov_combustivel.data) = '".$ano."' ".$condm_comb." 
	group by mov_combustivel.id_viatura
        
	) as comb on mov_viatura.id_viatura=comb.id_viatura
left join (
        select mov_avarias.id_viatura, sum(mov_avarias.preco) as totalava
        from mov_avarias
        where year(mov_avarias.data) = '".$ano."' ".$condm_ava."
        group by mov_avarias.id_viatura
        
        ) as ava on mov_viatura.id_viatura=ava.id_viatura
where viaturas.acessorio = 0 and year(mov_viatura.data) = '".$ano."' ".$condm_v." ".$cond."
group by mov_viatura.id_viatura
order by horast desc
limit 15";
$r=mysql_query($q);
$n=mysql_num_rows($r);


//preencher variaveis para o grafico
for($i=0;$i<$n;$i++)
{
    if($i>0){$vs=$vs.",";$hf=$hf.",";$htr=$htr.",";$cb=$cb.",";$ava=$ava.",";$tot=$tot.",";}
    $vs=$vs."'".mysql_result($r,$i,'desc_viatura')."'";
    $hf=$hf.mysql_result($r,$i,'horasf');
    $htr=$htr.mysql_result($r,$i,'horast');
    $cb=$cb.mysql_result($r,$i,'totalc');
    $ava=$ava.mysql_result($r,$i,'totalava');
    $total=mysql_result($r,$i,'horast')-(mysql_result($r,$i,'totalc')+mysql_result($r,$i,'totalava')+mysql_result($r,$i,'horasf'));
    $tot=$tot.$total;
}

$mes=mes($_POST['mes']);


//HTML + JavaScript
echo "<script type=\"text/javascript\">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'bar',
                width: 700,
                height: 900
            },
            title: {
                text: 'Avaliação Viaturas ".$mes."-".$_POST['ano']."'
            },
            subtitle: {
                text: 'Comparação Gastos Ganhos'
            },
            xAxis: {
                categories: [".$vs."],
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Euros',
                    align: 'high'
                }
            },
            tooltip: {
                formatter: function() {
                    return ''+
                        this.series.name +': '+ this.y +' euros';
                }
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -100,
                y: 100,
                floating: true,
                borderWidth: 1,
                backgroundColor: '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Horas Facturadas',
                data: [".$htr."]
            }, {
                name: 'Horas Homem',
                data: [".$hf."]
            },
                {
                name: 'Combustivel',
                data: [".$cb."]
            }, {
                name: 'Avarias',
                data: [".$ava."]
            }, {
                name: 'AVALIACAO GLOBAL',
                data: [".$tot."]
            }] 
        });
    });
    
});
		</script>
                
";

/*seleccionar categorias de viaturas */
$q_categorias="select * from categorias_viatura";
$r_categorias=mysql_query($q_categorias);
$n_categorias=mysql_num_rows($r_categorias);
$categorias=$categorias.'
    <form action="index.php?pagina=globalviatura" method="POST">
            Ano: <select name="ano">';
            for($i=2011;$i<=2012;$i++){
                    if(isset($_POST['ano'])){if($_POST['ano']==$i){$sel=' selected="selected" ';}else{$sel=' ';}}
                     $categorias=$categorias.'<option value="'.$i.'" '.$sel.'>'.$i.'</option>';
            }
            $categorias=$categorias. '</select> 
            Mes: <select name="mes">
                        <option value=""></option>';
            for($i=1;$i<=12;$i++)
            {
                if(isset($_POST['mes'])){if($_POST['mes']==$i){ $sel=' selected="selected" '; }else{ $sel=''; }}
                $categorias=$categorias.' <option value="'.$i.'"  '.$sel.'>'.mes($i).'</option> ';
            }
                   $categorias=$categorias.'</select>
           Semana: <select name="semana">
                        <option></option>
                   </select>
            <br>
        <select name="categoria">
        <option value="all">Todas as Viaturas</option>
     1200';

/*ler categorias das viaturas*/
for($i=0;$i<$n_categorias;$i++)
{
    if(mysql_result($r_categorias,$i,'id_categoria')==$_POST['categoria'])
    {
        $checked=' selected="selected"';
    }else{
        $checked="";
    }
    $categorias = $categorias.'<option  value="'.mysql_result($r_categorias, $i,'id_categoria').'" '.$checked.'>'.mysql_result($r_categorias,$i,'categoria').'</option>';
}

//botao para filtrar o grafico
$categorias = $categorias."</select>".'<button type="submit">Filtrar</button>';

/* div para o grafico */
echo $categorias.'<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>';
?>