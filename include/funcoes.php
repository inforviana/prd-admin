<?php
require '../config.php'; //bugfix max_acessorios

/* converter meses do ano de inteiro para string em portugues */
function mes($m)
{
    switch($m){
        case '1':
            $mes="Janeiro";
            break;
        case '2':
            $mes="Fevereiro";
            break;
        case '3':
            $mes="Marco";
            break;
        case '4':
            $mes="Abril";
            break;
        case '5':
            $mes="Maio";
            break;
        case '6':
            $mes="Junho";
            break;
        case '7':
            $mes="Julho";
            break;
        case '8':
            $mes="Agosto";
            break;
        case '9':
            $mes="Setembro";
            break;
        case '10':
            $mes="Outubro";
            break;
        case '11':
            $mes="Novembro";
            break;
        case '12':
            $mes="Dezembro";
            break;
    }
return $mes;   
}

/* mostrar os acessorios para aquela viatura */
function ler_acessorios($viatura)
{
    $q_ace="select * from viaturas where acessorio=1 order by desc_viatura"; //seleccionar todos os acessorios
    $q_av="select * from acessorios_viatura where id_viatura=".$viatura;
    
    $r_ace=mysql_query($q_ace);
    $r_av=mysql_query($q_av);
    
    $n_ace=mysql_num_rows($r_ace);
    $n_av=mysql_num_rows($r_av);
    
    echo '<div id="div_acessorios"><table class="tabela_acessorios">
       <thead>
            <th colspan=2>
                Acessorios utilizados por esta viatura
            </th>
       </thead>
       <tr><td>';
    for($i=0;$i<$n_ace;$i++)
    {
        $checked=' ';
        
        for($j=0;$j<$n_av;$j++)
        {
            if((mysql_result($r_ace,$i,'id_viatura'))==(mysql_result($r_av,$j,'id_acessorio')))
            {
                
                $checked=' checked="yes" ';
            }
        }
        echo '<input type="checkbox" name="acessorios[]" '.$checked.' value="'.mysql_result($r_ace,$i,'id_viatura').'" > '.mysql_result($r_ace,$i,'desc_viatura').'<br>';
        if(($i%$MAX_ACESSORIOS==0)&&($i>0))
        {
            echo '</td><td>';
        }
        
    }
    echo '</td></tr></table></div>';
}

?>
