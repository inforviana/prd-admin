<?php
/*
 * 
 * HORAS TRABALHADAS DO FUNCIONARIO POR DIA
 */
$func=$_GET['funcionario'];
$q="select date(data) as 'data', day(data) as 'dia',month(data) as 'mes',(sum(horas_viatura)/60) as 'total' from mov_viatura where id_funcionario=".$func." group by date(data) having sum(horas_viatura)>0 order by date(data) desc limit 15";
$r=mysql_query($q);
$n=mysql_num_rows($r);

for($i=($n-1);$i>0;$i--)
{
    
    if($i<($n-1)) {
        $datas=$datas.",";
        $totais=$totais.",";
    }
            
    $datas=$datas."'".mysql_result($r,$i,'dia')."-".  mysql_result($r,$i,'mes')."'";
    $totais=$totais."".mysql_result($r,$i,'total')."";
}

echo "<script type=\"text/javascript\">
        $(function () {
            var chart;
            $(document).ready(function() {
                chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'container',
                        type: 'line',
                        marginRight: 130,
                        marginBottom: 25,
                        width: 900,
                        height:200
                    },
                    title: {
                        text: 'Horas Trabalhadas',
                        x: -20 //center
                    },
                    subtitle: {
                        text: '',
                        x: -20
                    },
                    xAxis: {
                        categories: [".$datas."]
                    },
                    yAxis: {
                        title: {
                            text: 'Horas'
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    tooltip: {
                        formatter: function() {
                                return '<b>'+ this.series.name +'</b><br/>'+
                                this.x +': '+ this.y +'H';
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'top',
                        x: -10,
                        y: 100,
                        borderWidth: 0
                    },
                    series: [{
                        name: 'Horas com Viatura',
                        data: [".$totais."]
                    }]
                });
            });

        });
</script>         
";
echo '<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>';
?>