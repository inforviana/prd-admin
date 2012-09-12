<?php
/*
 * GRAFICOS A USAR O HIGHCHARTS
 */

//verificar data a usar para o ponto e para os graficos


function linhas_combustivel()
{
    $q="select id_viatura, sum(valor_movimento) as 'total' from mov_combustivel where date(data)='2012-02-20' group by id_viatura having sum(valor_movimento)>0";
    if(mysql_query($q))
    {
        $r=mysql_query($q);
        $n=  mysql_num_rows($r);        
    }
    //echo $q;
}



/*FUNCAO PRINCIPAL*/
function grafico()
{
   $tipo_grafico=$_GET['tipografico'];
   switch($tipo_grafico)
   {
       case 'linhas_combustivel':
           linhas_combustivel();
           break;
       default:
           linhas_combustivel();
           break;
           
   }
}  

/* VERIFICA SE É DEFINIDO PARA MOSTRAR GRAFICO */
       grafico();
?>