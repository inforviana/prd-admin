<?php
    if(isset($_GET['viatura'])) //se houver passagem de valores 
    {
        $vt=$_GET['viatura']; //define a viatura a usar nas querys
        
        /*
         * 
         * calculos a utilizar para obter o lucro da viatura usando os dados disponiveis
         * 
         */
        
        //FORMULA A USAR: Lv=(HtxPh)-(Mo+C(f)+Hh+Ho)
        //COMBUSTIVEL: C(f)=Avg(Pl)xSum(Litros)
        //HORAS HOMENS: Hh=Sum(HorasHomem)xPhh -> Preco Horas por Homem
        //HORAS OFICINA: Ho=Sum(HorasOficina)xPho -> Preco Horas por Mecanico
        
        /* Ht */
        $q_Ht="select (sum(horas_viatura)/60) from mov_viatura where id_viatura=".$vt;
        $r_Ht=  mysql_query($q_Ht);
        $Ht=  mysql_result($r_Ht, 0,0);
        
        /* Sum(Litros) */
        $q_TL="select sum(valor_movimento) as 'total' from mov_combustivel where id_viatura=".$vt;
        $r_TL=mysql_query($q_TL);
        $TL=  mysql_result($r_TL, 0, 0);
        
        
        /* Mo */
        $q_Mo="select sum(preco) from mov_avarias where id_viatura=".$vt;
        $r_Mo=  mysql_query($q_Mo);
        $Mo=  mysql_result($r_Mo, 0, 0);
        
        /* Hh */
        
    }
    
    
?>
