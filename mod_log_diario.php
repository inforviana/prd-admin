<?php
        //verifica se a data esta definida 
        if(isset($_POST['data']))
        {
            $data=$_POST['data'];
        }else{
            $data=date('Y-m-j');//aplica a data de sistema
        }
	$q_ultimos="SELECT MAX(id_movviatura), mov_viatura.id_funcionario AS 'idfunc', funcionario.nome_funcionario AS 'nome', DATA AS 'hora', DATE_SUB(DATA, INTERVAL (th.total_horas+60) MINUTE) AS 'hora_entrada', round((th.total_horas/60),2) as 'total_horas'
FROM mov_viatura
LEFT JOIN funcionario ON funcionario.id_funcionario=mov_viatura.id_funcionario
LEFT JOIN (
SELECT id_funcionario,SUM(horas_viatura) AS 'total_horas'
FROM mov_viatura
WHERE DATE(DATA)='".$data."'
GROUP BY id_funcionario
			) AS th ON mov_viatura.id_funcionario=th.id_funcionario
WHERE DATE(DATA)='".$data."'
GROUP BY mov_viatura.id_funcionario
ORDER BY DATA DESC";
        $r_ultimos=mysql_query($q_ultimos);
        $n_ultimos=  mysql_num_rows($r_ultimos);
        
        if($n_ultimos==0){
            echo "<br><h1>Sem registos hoje.</h1></br>";//sem registos hoje
        }else{
            //mostra resgistos
            echo '<h1>Ultimos Registos</h1><br><br><table class="tabela_registo">
                    <thead><th>Entrada</th><th>Saida</th><th>Funcionario</th><th>Horas Trabalhadas</th></thead><tbody>';
                for($i=0;$i<$n_ultimos;$i++)
                {
                    echo '<tr><td>'.mysql_result($r_ultimos,$i,'hora_entrada').'</td><td>'.mysql_result($r_ultimos,$i,'hora')."</td><td style=\"width:350px;\"><a href=\"index.php?pagina=resumodia&funcionario=".mysql_result($r_ultimos,$i,'idfunc')."&data=".date('Y-m-j')."\">".mysql_result($r_ultimos,$i,'nome').'</td><td>'.mysql_result($r_ultimos,$i,'total_horas').'<b> +1</b></td></tr>';
                }
            echo '</tbody></table><br><br>'; 
        }
?>