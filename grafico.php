<?php
/*
 * GRAFICOS A USAR O HIGHCHARTS
 */

//verificar data a usar para o ponto e para os graficos



//devolve o resultado da query em formato array JS ['','',...]
function array_js($query,$coluna)
{
    $res = mysql_query($query); 
    $num = mysql_num_rows($res); //numero de linhas da query
    
    $array_js = "["; //inicializa o array com o parentesis recto
    
    for($i=0;$i<$num;$i++) //dump da coluna seleccionada para um array em JS
    {
        if(($i > 0)&&($i<($num-1))) $array_js=$array_js.","; //adiciona virgula se nao for o primeiro dados da query
        $array_js = $array_js.mysql_result($res,$i,$coluna); //adiciona aspas ao texto obtido
    }
    
    $array_js=$array_js."]"; //finaliza o array com o parentesis recto
} 





//************* GRAFICOS ********************

function linhas_combustivel()
{
    echo "testes";  
}







/*FUNCAO PRINCIPAL*/
function grafico()
{
   $tipo_grafico=$_POST['tipografico'];
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

/* VERIFICA SE � DEFINIDO PARA MOSTRAR GRAFICO */
       grafico();
?>