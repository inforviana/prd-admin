<?php
$q_melhor_funcionario="
select mov_viatura.id_funcionario,sum(mov_viatura.horas_viatura)  as 'total_horas',funcionario.nome_funcionario
from mov_viatura 
inner join funcionario on funcionario.id_funcionario=mov_viatura.id_funcionario
group by mov_viatura.id_funcionario
order by sum(mov_viatura.horas_viatura) desc
limit 1
";

$q_melhor_viatura="
select mov_viatura.id_viatura,sum(mov_viatura.horas_viatura)  as 'total_horas',viaturas.desc_viatura
from mov_viatura 
inner join viaturas on viaturas.id_viatura=mov_viatura.id_viatura
group by mov_viatura.id_viatura
order by sum(mov_viatura.horas_viatura) desc
limit 1
";

$r_melhor_funcionario=mysql_query($q_melhor_funcionario);
$r_melhor_viatura=mysql_query($q_melhor_viatura);


echo '<div id="marquee_splash" style="width:800px;"><marquee style="backgroud-color:#000000;">Top Horas:'.mysql_result($r_melhor_funcionario, 0,'nome_funcionario').' com '.intval(mysql_result($r_melhor_funcionario, 0,'total_horas')/60).' Horas ||| Top Viatura: '.mysql_result($r_melhor_viatura,0,'desc_viatura').'</marquee></div>'

?>