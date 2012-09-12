<?php
/*
 * FUNCOES PARA O TECNICO
 */
function verificar_bd()
{
    $dbc1=mysql_connect("localhost","pcnor_inforviana","plasma2010");
    mysql_select_db('pcnor_worktruck', $dbc1);
    $r1=mysql_query("show table status",$dbc1);
    $n1=mysql_num_rows($r1);
    $c1=  mysql_num_fields($r1);
    
    $resultado="<table border=1>";
    for($i=0;$i<$n1;$i++)
    {
        $resultado=$resultado."<tr>";
        for($j=0;$j<$c1;$j++)
        {
            $resultado=$resultado."<td>".mysql_result($r1,$i,$j)."</td>";
        }
        $resultado=$resultado."</tr>";
    } 
    $resultado=$resultado."</table>";
    return $resultado;
}

function actualizar_precos_hora($cond1,$cond2)
{
    $q_hora="select * from mov_viatura where preco_hora_normal is null";
    $r_hora=  mysql_query($q_hora);
    $n_hora=  mysql_num_rows($r_hora);
    for($i=0;$i<$n_hora;$i++)
    {
        $q_func="select * from funcionario where id_funcionario=".mysql_result($r_hora,$i,'id_funcionario');
        $r_func=  mysql_query($q_func);
        $q_umov="update mov_viatura set preco_hora_normal=".mysql_result($r_func,0,'preco_hora_normal').", preco_hora_extra=".mysql_result($r_func,0,'preco_hora_extra')." where id_movviatura=".mysql_result($r_hora,$i,'id_movviatura'); 
        mysql_query($q_umov);
    }
    
    $q_horav="select * from mov_viatura where preco_viatura=".$cond1;
    $r_horav=mysql_query($q_horav);
    $n_horav=mysql_num_rows($r_horav);
    for($j=0;$j<$n_horav;$j++)
    {
        $q_ph="select * from viaturas where id_viatura=".mysql_result($r_horav,$j,'id_viatura');
        $r_ph=  mysql_query($q_ph);
        $q_umov="update mov_viatura set preco_viatura=".mysql_result($r_ph,0,'preco_hora')." where id_movviatura=".  mysql_result($r_horav, $j, 'id_movviatura');
        mysql_query($q_umov);
   }
}

 if(isset($_GET['op']))
 {
     $op=$_POST['operacao'];
     $c1=$_POST['cond1'];
     $c2=$_POST['cond2'];
     $v1=$_POST['val1'];
     $v2=$_POST['val2'];
     
     /* NULL */ if($c1==''){
         $c1=' is null ';
     }else{
         $temp=$c1;
         $c1=" = ".$temp;
     }
     
     if($c2==''){
         $c2=' is null ';
     }else{
         $temp=$c2;
         $c2 =" = ".$temp;
     }
     
     /* funcoes a executar */
     switch($op)
     {
         case 'funcionario_horas':
            /*
             * NULL dos funcionarios
             */
            $q="update funcionario set preco_hora_normal=".$v1.", preco_hora_extra=".$v2." where preco_hora_normal ".$c1." and preco_hora_extra ".$c2;
            break;
         case 'viatura_horas':
             /*
              * NULL das viaturas
              */
            $q="update viaturas set preco_hora=".$v1." where preco_hora ".$c1;
            break;
         case 'mov_viatura_preco_hora':
             /*
              * preenche movimentos de trabalho com preco hora dos funcionarios e das viaturas
              */
             actualizar_precos_hora();
             break;
             /*
              * comparar base de dados com versao actual
              */
         case 'verificar_db':
             $tab=verificar_bd();
             break;
     }
     if(isset($q)){
        if(mysql_query($q)){
                echo '<script>alert(\'Operacao executada com sucesso!\')</script>';
            }else{
                echo '<script>alert(\'Erro ao executar operacao...\\n'.$q.'\')</script>';
            }
     }
 }
 
 /* HTML */
 echo '
     <center><h1>Menu Tecnico</h1></center><br>
     <div>
        <form method="POST" action="index.php?pagina=tecnico&op=1">
            <select name="operacao">
                <option value="funcionario_horas">Preencher horas dos funcionarios</option>
                <option value="viatura_horas">Preencher horas das viaturas</option>
                <option value="mov_viatura_preco_hora">Preencher trabalhos com precos actuais</option>
                <option value="verificar_db">Verificar DB</option>
            </select>
            <input class="inp_tecnico2" type="text" name="cond1">
            <input class="inp_tecnico2" type="text" name="cond2">
            <input class="inp_tecnico1" type="text" name="val1">
            <input class="inp_tecnico1" type="text" name="val2">
            <button type="submit">Executar >></button>
        </form>
     </div>
';
 echo $tab;
?>