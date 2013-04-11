<?php
    /*
     * FUNCAO PARA CALCULAR O LUCRO DA VIATURA
     */
    if(isset($_GET['via']))
    {
        /* verifica se foi passado codigo da viatura */
        $v=$_GET['via'];   
    }

        /* HORAS */
        $q_horas="select * from mov_viatura where id_viatura=".$v;
        $r_horas=mysql_query($q_horas);
        $n_horas=mysql_num_rows($r_horas);
        
        $total_horas=0;
        $total=0;
        $th=0;
        
        for($i=0;$i<$n_horas;$i++) 
        {
            $th+=mysql_result($r_horas,$i,'horas_viatura');
            $horas_trabalho=  ((mysql_result($r_horas, $i, 'horas_viatura')/60)*mysql_result($r_horas,$i,'preco_hora_normal'));
            $horas_ganhas=((mysql_result($r_horas,$i,'horas_viatura')/60)*mysql_result($r_horas,$i,'preco_viatura'));
            $total_horas=$total_horas+($horas_ganhas-$horas_trabalho);
            
        }
        $total=$total_horas;
        echo 'Total Horas: Eur '.$total_horas;
        
        /* COMBUSTIVEL */
        
        $q_comb="select * from mov_combustivel where id_viatura=".$v;
        $r_comb=mysql_query($q_comb);
        $n_comb=mysql_num_rows($r_comb);
        
        
        for($i=0;$i<$n_comb;$i++)
        {
            $total_combustivel-=(mysql_result($r_comb,$i,'valor_monetario')*mysql_result($r_comb,$i,'valor_movimento'));
        }
         $total+=$total_combustivel;
        echo '<br>Total Combustivel: Eur '.$total_combustivel;
        
        
        /* OFICINA */
        
        $q_oficina="select * from mov_avarias where id_viatura=".$v;
        $r_oficina=mysql_query($q_oficina);
        $n_oficina=mysql_num_rows($r_oficina);
        
        for($i=0;$i<$n_oficina;$i++)
        {
            $total_oficina-=(mysql_result($r_oficina,$i,'preco')+((mysql_result($r_oficina, $i, 'horas')/60)*mysql_result($r_oficina,$i,'preco_hora')));
            
        }
        $total+=$total_oficina;
        echo '<br>Total Oficina: Eur '.$total_oficina;
     
    echo "<br><br><b>Horas Trabalhados: ".($th/60)." Horas   Total: </b>Eur ".$total;
    echo '<br><br><br><div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>';
?>