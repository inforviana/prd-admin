<?php
        //verifica se a data esta definida 
        if(isset($_POST['data']))
        {
            $data=$_POST['data'];
        }else{
            $data=date('Y-m-j');//aplica a data de sistema
        }
        
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
            <br><br><br>
           <div id=\"acord_funcionarios\" style=\"width:600px;\">
        ";
            
        //desenhar o conteudo do acordeao
        for($i=0;$i<$n_func;$i++)
        {
            $idfunc=mysql_result($r_func, $i,'id_funcionario');
            $contador_eventos = 0; //reinicia o contador de eventos para cada funcionario
            $dados_horas_funcionario="";
            $dados_combustivel_funcionario="";
            
            //verificar eventos de registo de horas ou ponto
            $r_eventos_horas = mysql_query("
                select mov_viatura.id_viatura, mov_viatura.horas_viatura,viaturas.desc_viatura, mov_viatura.id_acessorio, mov_viatura.horas_trab_acessorio
                from mov_viatura 
                left join viaturas on viaturas.id_viatura = mov_viatura.id_viatura
                where date(data)='".$data."' and id_funcionario=".$idfunc);
            $n_eventos_horas = mysql_num_rows($r_eventos_horas);
            $contador_eventos = $n_eventos_horas ;
            
            
            
            if($n_eventos_horas > 0) //se existirem eventos de horas do funcionario
            {
                for($j=0;$j<$n_eventos_horas;$j++)
                {
                    //linha horas
                    $dados_horas_funcionario=$dados_horas_funcionario."Trabalhou com <b>".mysql_result($r_eventos_horas,$j,'desc_viatura')."</b> ".(mysql_result($r_eventos_horas,$j,'horas_viatura')/60)."H ".(mysql_result($r_eventos_horas,$j,'horas_viatura')%60)."m";
                    
                    //mostrar acessorio utilizado
                    if(mysql_result($r_eventos_horas,$j,'mov_viatura.id_acessorio') > 0)
                    {
                        $r_detalhes_acessorio = mysql_query("select * from viaturas where id_viatura = ".mysql_result($r_eventos_horas,$j,'mov_viatura.id_acessorio'));
                        
                        $dados_horas_funcionario = " com <b>".mysql_result($r_detalhes_acessorio,$j,'desc_viatura')."</b> ".(mysql_result($r_eventos_horas,$j,'mov_viatura.horas_trab_acessorio')/60)."H".(mysql_result($r_eventos_horas,$j,'mov_viatura.horas_trab_acessorio')%60)."m";
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
                    $dados_avarias_funcionario=$dados_avarias_funcionario."Avaria no <b>".mysql_result($r_eventos_avarias,$h,'desc_viatura')."</b>, ".(mysql_result($r_eventos_avarias, $h, 'horas')/60)."H ".(mysql_result($r_eventos_avarias, $h, 'horas')%60)."m  ".  mysql_result($r_eventos_avarias, $h, 'desc_avaria')." <br>";
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
                        '.$dados_horas_funcionario.'
                        '.$dados_combustivel_funcionario.'
                        '.$dados_avarias_funcionario.'
                    </p>
                </div>
            ';
        }
        echo "</div><br>"; //fim da div do acordeao
?>  