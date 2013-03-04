<?php
		echo '<center>
				<div style="width:600px;margin-left: auto;margin-right:auto; text-align:center">
					<ul class="dropdown">
						<li><a href="index.php"><span>Inicio</span></a></li>
						<li><a href="#">Tabelas</a>
							<ul class="sub_menu">
								<li><a href="index.php?pagina=funcionarios"><span>Funcionarios</span></a></li>
								<li><a href="index.php?pagina=grupos"><span>Grupos</span></a></li>
								<li><a href="index.php?pagina=obras"><span>Obras</span></a></li>
								<li><a href="index.php?pagina=viaturas"><span>Viaturas</span></a></li>
								<li><a href="index.php?pagina=tubos"><span>Tubos</span></a></li>
							</ul>
						</li>
						<li><a href="#"><span>Relatorios</span></a>
							<ul class="sub_menu">
								<li><a href="#">Resultados</a>
									<ul>
										<li><a href="index.php?pagina=globalviatura">Vista Global Por Viatura</a></li>
									</ul>
								</li>
								<li><a href="#">Horas</a>
									<ul>
										<li><a href="#">Horas</a></li>
									</ul>
								</li>
								<li><a href="#">Ponto</a>
									<ul>
										<li><a href="index.php?pagina=ponto">Registo Diario</a></li>
									</ul>
								</li>
							</ul>
						</li>
						<li><a href="index.php"><span>Extratos</span></a>
							<ul class="sub_menu">
								<li><a href="./index.php?pagina=extractofuncionarios">Horas por Funcionario</a></li>
								<li><a href="./index.php?pagina=extractoviaturas">Horas por Viatura</a></li>
							</ul>	
						</li>
						<li><a href="index.php"><span>Opcoes</span></a>
							<ul class="sub_menu">
								<li><a href="index.php?pagina=opcoes">Parametros Gerais</a></li>
								<li><a href="index.php?pagina=utilizadores">Utilizadores</a></li>
								<li><a href="index.php?pagina=utilizadorestubos">Tubos - Utilizadores</a></li>
                                                                <li><a href="index.php?pagina=tecnico">Tecnico</a></li>
							</ul>	
						</li>
						<li><a href="index.php?accao=sair"><span>Terminar Sessao</span></a></li>
					</ul>
			<br><br>
			<font style="font-family:Arial, Helvetica, sans-serif;font-size:11px;">'.$NOME_APP.' - '.$VERSAO_APP.'</font>					
		</div>
        </center>
        <br>
		';
           
    //seleccao da data de trabalho
    if(isset($_COOKIE['data_i']))
    {
        $data_i=$_COOKIE['data_i'];
        $data_f=$_COOKIE['data_f'];
    }else{
        $data_i=date('Y-m-d',strtotime('-1 month'));
        $data_f=date('Y-m-d'); 
    }
    
    echo '
        <script>
            $(function(){
                $("#data_i, #data_f").datepicker({dateFormat: "yy-mm-dd"});
            });
        </script>
        
        <form method="POST" action="./index.php?a=datatrabalho">
            <center>
            <table>
                <tr>
                    <td><input name="data_i" id="data_i" type="text" placeholder="data inicial" style="width:150px;text-align:center;font-size:18px;" value="'.$data_i.'"></td>
                    <td><input name="data_f" id="data_f" type="text" placeholder="data final" style="width:150px;text-align:center;font-size:18px;" value="'.$data_f.'"></td>
                    <td><input type="submit" value="OK"></td>
                </tr>
            </table>
            </center>
        </form>
        <center>
			<form method="POST" action="./index.php?a=mestrabalho">
	              <select name="mes">
	                    	<option value="1">Janeiro</option>
	                    	<option value="2">Fevereiro</option>
	                    	<option value="3">Marco</option>
	                    	<option value="4">Abril</option>
	                    	<option value="5">Maio</option>
	                    	<option value="6">Junho</option>
	                    	<option value="7">Julho</option>
	                    	<option value="8">Agosto</option>
	                    	<option value="9">Setembro</option>
	                    	<option value="10">Outubro</option>
	                    	<option value="11">Novembro</option>
	                    	<option value="12">Dezembro</option>
	              </select>
	              <select name="ano">
	                    	<option value="2011">2011</option>
	                    	<option value="2012">2012</option>
	                    	<option value="2013">2013</option>
	              </select>
	              <input type="submit" value="OK">
        </form>
        </center>                    		
        ';
?>