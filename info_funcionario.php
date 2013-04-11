<?php
if($novo!=1){
    //query para obter o total de horas trabalhadas
    @$q_total_horas="select sum(horas_viatura) as 'total' from mov_viatura where id_funcionario=".$id_funcionario." and month(data)=".date(m)." and year(data)=".date(Y);
    @$r_total_horas=mysql_query($q_total_horas);

    //query para obter a media de horas de trabalho por dia
    $q_media_horas="select avg(horas_dia) as 'media'
    from 
    (select sum(horas_viatura/60) as 'horas_dia'
    from mov_viatura
    where id_funcionario=".$id_funcionario."
    group by date(data)) as soma";
    $r_media_horas=mysql_query($q_media_horas);

    //seleccionar a cor a usar na media de horas trabalhadas
    $media_horas=(mysql_result($r_media_horas, 0,'media'));
    if($media_horas<8){ //condicao para seleccionar a cor
            $texto_media='<b><font style="color:red;">'.round($media_horas,2).' Horas</font></b>';
    }else{
            $texto_media='<b><font style="color:green;">'.round($media_horas,2).' Horas</font></b>';
    }
}
//mostrar informaçoes
echo '<div id="pagamentos_funcionario" style="width:400px;height:200px;border:1px gray solid;">
<center><u>Configurar Pagamentos</u></center><br><br>
<table>
<tr><td><b>Custo Hora Normal:</b></td><td><input style="text-align:right;" type="text" name="preco_hora_normal" size=5 value="'.@mysql_result($r_funcionario,0,'preco_hora_normal').'"> Eur<br></td></tr>
<tr><td><b>Custo Hora Extra:</b></td><td><input style="text-align:right;" type="text" name="preco_hora_extra" size=5 value="'.@mysql_result($r_funcionario,0,'preco_hora_extra').'"> Eur <br></td></tr>
<tr><td><b>Custo Sabado:</b></td><td><input style="text-align:right;" type="text" name="preco_sabado" size=5 value="'.@mysql_result($r_funcionario,0,'preco_sabado').'"> Eur <br></td></tr>
</table>
<br><br>
<b></b>
</div>
';
if($novo!=1){
    echo'    <div id="info_funcionario" style="width:400px;height:200px;border:1px gray solid;">
    <center><u>Estatisticas do Funcionario</u></center><br><br>
    <b>Total Horas em '.mes(date('n')).':</b> '.intval(mysql_result($r_total_horas, 0,'total')/60).' Horas<br>
    <b>Média de Horas por Dia de Trabalho:</b> '.$texto_media.'
    <br><br>
    <b></b>
    </div>';
}
?>