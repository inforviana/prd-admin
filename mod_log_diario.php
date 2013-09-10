<?php
        //verifica se a data esta definida 
        if(isset($_COOKIE['data_f']))
        {
            $data=$_COOKIE['data_f'];
        }else{
            $data=date('Y-m-j');//aplica a data de sistema
        }
        
        //dias da semana
        switch(date('D',strtotime($data))){
        	case 'Mon':
        		$diaSemana = 'Segunda-Feira';
        		break;
            case 'Tue':
                $diaSemana = 'Terca-Feira';
                break;
            case 'Wed':
                $diaSemana = 'Quarta-Feira';
                break;
            case 'Thu':
                $diaSemana = 'Quinta-Feira';
                break;
            case 'Fri':
                $diaSemana = 'Sexta-Feira';
                break;
            case 'Sat':
                $diaSemana = 'Sabado';
                break;
            case 'Sun':
                $diaSemana = 'Domingo';
                break;
        }
        
        //obter todos os funcionarios
        $q_func = "select * from funcionario order by nome_funcionario asc";
        $r_func = mysql_query($q_func);
        $n_func = mysql_num_rows($r_func);
        
        //jquery e html do acordeao
        echo "
            <script>
                $(function(){
                    $('#acord_funcionarios').accordion();
                });
            </script>
            <br><h3>Registos de ".$data." (".$diaSemana.")</h3><br><br>
           <div id=\"acord_funcionarios\" style=\"width:600px;\">
        ";
            
        //desenhar o conteudo do acordeao
        for($i=0;$i<$n_func;$i++)
        {
        	$somaHoras = 0;
            $idfunc=mysql_result($r_func, $i,'id_funcionario');
            $contador_eventos = 0; //reinicia o contador de eventos para cada funcionario
            $dados_horas_funcionario="";
            $dados_combustivel_funcionario="";
            
            //verificar eventos de registo de horas ou ponto
            $r_eventos_horas = mysql_query("
                select mov_viatura.transporte as deslocacao, time(mov_viatura.data) as data_registo, mov_viatura.id_viatura, mov_viatura.horas_viatura,viaturas.desc_viatura, mov_viatura.id_acessorio, mov_viatura.horas_trab_acessorio, obras.descricao_obra as local
                from mov_viatura 
                left join viaturas on viaturas.id_viatura = mov_viatura.id_viatura
                left join obras on obras.id_obra = mov_viatura.local
                where date(data)='".$data."' and id_funcionario=".$idfunc);
            $n_eventos_horas = mysql_num_rows($r_eventos_horas);
            $contador_eventos = $n_eventos_horas ;
            
            if($n_eventos_horas > 0) //se existirem eventos de horas do funcionario
            {
                for($j=0;$j<$n_eventos_horas;$j++)
                {
                    //linha horas
                    if(((mysql_result($r_eventos_horas,$j,'horas_viatura')/60) % 1) >= 0.5)
                    {
                    	$hora = round((mysql_result($r_eventos_horas,$j,'horas_viatura')/60),0) - 1; 
                    }else{
                    	$hora = round((mysql_result($r_eventos_horas,$j,'horas_viatura')/60),0);
                    }
                    
                    $dados_horas_funcionario=$dados_horas_funcionario."Trabalhou com <b>".mysql_result($r_eventos_horas,$j,'desc_viatura')."</b> ".floor(mysql_result($r_eventos_horas,$j,'horas_viatura')/60)."H ".(mysql_result($r_eventos_horas,$j,'horas_viatura')%60)."m em ".mysql_result($r_eventos_horas, $j,'local');
                    
                    $somaHoras = $somaHoras + mysql_result($r_eventos_horas, $j, 'horas_viatura'); //adiciona horas de trabalho
                    $somaHoras = $somaHoras + mysql_result($r_eventos_horas, $j, 'deslocacao'); //adiciona tempo de deslocacao
                    
                    //mostrar acessorio utilizado
                    if(mysql_result($r_eventos_horas,$j,'mov_viatura.id_acessorio') > 0)
                    {
                        $r_detalhes_acessorio = mysql_query("select * from viaturas where id_viatura = ".mysql_result($r_eventos_horas,$j,'mov_viatura.id_acessorio'));
                        
                        $dados_horas_funcionario = $dados_horas_funcionario." com <b>".mysql_result($r_detalhes_acessorio,0,'desc_viatura')."</b> ".(mysql_result($r_eventos_horas,$j,'mov_viatura.horas_trab_acessorio')/60)."H".(mysql_result($r_eventos_horas,$j,'mov_viatura.horas_trab_acessorio')%60)."m";
                    }
                    $dados_horas_funcionario = $dados_horas_funcionario."<br>";
                }
            }
            
            //verificar eventos de combustivel
            $r_eventos_combustivel = mysql_query("
                select mov_combustivel.valor_movimento, viaturas.desc_viatura
                from mov_combustivel
                left join viaturas on viaturas.id_viatura = mov_combustivel.id_viatura 
                where mov_combustivel.valor_movimento > 0 and date(data)='".$data."' and id_funcionario=".$idfunc);
            $n_eventos_combustivel = mysql_num_rows($r_eventos_combustivel);
            $contador_eventos+=$n_eventos_combustivel;
            if($n_eventos_combustivel>0)
            {
                for($h=0;$h<$n_eventos_combustivel;$h++)
                {
                    $dados_combustivel_funcionario=$dados_combustivel_funcionario."Atestou <b>".mysql_result($r_eventos_combustivel,$h,'desc_viatura')."</b> com ".mysql_result($r_eventos_combustivel,$h,'valor_movimento')." Litros<br>";
                }
            }
            
            //verificar eventos de avarias ou oficina
            $r_eventos_avarias = mysql_query("
                select viaturas.marca_viatura, viaturas.modelo_viatura, mov_avarias.categoria, mov_avarias.desc_avaria, mov_avarias.preco, mov_avarias.horas, viaturas.desc_viatura, mov_avarias.desc_avaria
                from mov_avarias
                left join viaturas on viaturas.id_viatura = mov_avarias.id_viatura
                where date(mov_avarias.data) = '".$data."' and mov_avarias.id_funcionario=".$idfunc);
            $n_eventos_avarias = mysql_num_rows($r_eventos_avarias);
            $contador_eventos+=$n_eventos_avarias;
            if($n_eventos_avarias>0)
            {
                for($h=0;$h<$n_eventos_avarias;$h++)
                {
                	$somaHoras = $somaHoras + mysql_result($r_eventos_avarias, $h, 'horas');
                    $dados_avarias_funcionario=$dados_avarias_funcionario."Avaria no <b>".mysql_result($r_eventos_avarias,$h,'desc_viatura')."</b>, ".number_format((mysql_result($r_eventos_avarias, $h, 'horas')/60),0)."H ".(mysql_result($r_eventos_avarias, $h, 'horas')%60)."m  ".  mysql_result($r_eventos_avarias, $h, 'desc_avaria')." <br>";
                }
            }else{
                $dados_avarias_funcionario = "";
            }

            //desenhar interior do acordeao
            if($contador_eventos > 0) //se houver algum evento desenha a caixa do funcionario
            echo '
                <h3>'.mysql_result($r_func,$i,'nome_funcionario').'</h3>
                <div>
                    <p style="text-align:left;">
                		<b>'.mysql_result($r_eventos_horas,0,'data_registo').'</b> -> <u>'.round(($somaHoras/60),0).':'.($somaHoras%60).' Horas de trabalho</u><br>
                        '.$dados_horas_funcionario.'
                        '.$dados_combustivel_funcionario.'
                        '.$dados_avarias_funcionario.'
                    </p>
                </div>
            ';
        }
        echo "</div><br>"; //fim da div do acordeao
?>