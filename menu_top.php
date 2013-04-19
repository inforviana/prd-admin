<?php

	//seleccao da data de trabalho
	if(isset($_COOKIE['data_i'])) //verifica se a data esta definida nas cookies
	{
		$data_i=$_COOKIE['data_i'];
		$data_f=$_COOKIE['data_f'];
	}else{ //se nao estiver usa a data de sistema
		$data_i=date('Y-m-d',strtotime('-1 month'));
		$data_f=date('Y-m-d');
	}
	 
	//parametros a confirmar passagem
	$idViatura = "";
	$idFuncionario = "";
	 
	if(isset($_GET['idviatura']))
		$idViatura = '&idviatura='.$_GET['idviatura'];
	 
	if(isset($_GET['idfuncionario']))
		$idFuncionario = '&idfuncionario='.$_GET['idfuncionario'];
	 
	$data = explode('-',$data_i); //separa a data num array para verificacao do ano e mes para usar nas combo boxes superiores




	if(isset($_GET['impressao'])) //versao de impressao
	{
		$versao_impressao = 1;
	} else { //versao web
		$versao_impressao = 0;
			//arrays a utilizar para as combo boxes
		 	$meses = array("Janeiro", "Fevereiro", "Marco","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
		 	$anos = array("2011","2012","2013");
		
			//html
			echo '<center>
					<!--
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
			<br>-->
						<!-- menu 2.0 -->
					<table id="tabela_menu">
						<tr>
							<td>
								<a href="./index.php?pagina=funcionarios"><img class="menu_topo" border=0 src="./images/funcionarios_small.png"></a>
							</td>
							<td>
								<a href="./index.php?pagina=grupos"><img class="menu_topo" border=0 src="./images/grupos_small.png"></a>
							</td>
							<td>
								<a href="./index.php?pagina=viaturas"><img class="menu_topo" border=0 src="./images/viaturas_small.png"></a>
							</td>
							<td>
								<a href="./index.php?pagina=obras"><img class="menu_topo" border=0 src="./images/obras_small.png"></a>
							</td>
							<td>
								<a href="./index.php?pagina=tubos"><img class="menu_topo" border=0 src="./images/tubos_small.png"></a>
							</td>
							<td>
								<a href="./index.php"><img class="menu_topo" border=0 src="./images/registo_diario_small.png"></a>
							</td>
							<td>
								<a href="./index.php?pagina=extractofuncionarios"><img class="menu_topo" border=0 src="./images/horashomem_small.png"></a>
							</td>
							<td>	
								<a href="./index.php?pagina=extractoviaturas"><img class="menu_topo" border=0 src="./images/horasviatura_small.png"></a>
							</td>
							<td>
								<a href="./index.php?pagina=opcoes"><img class="menu_topo" border=0 src="./images/opcoes_small.png"></a>
							</td>
							<td>
								<a href="./sair.php"><img class="menu_topo" border=0 src="./images/sair_small.png"></a>
							</td>
						</tr>
					</table>
	        </center>
	        <br>
			';
			
			
			if(isset($_GET['pagina']))
			{
				$paginaRedireccionar = '&pagina='.$_GET['pagina'];
			}else{
				$paginaRedireccionar = "";
			}
						

	    echo '
	        <script>
	            $(function(){
	                $("#data_i, #data_f").datepicker({dateFormat: "yy-mm-dd"});
	            });
	        </script>
	        
	        <form method="POST" action="./index.php?a=datatrabalho'.$paginaRedireccionar.''.$idFuncionario.''.$idViatura.'">
	            <center>
	            <table>
	                <tr>
	                    <td><input name="data_i" id="data_i" type="text" placeholder="data inicial" style="color:white;background-color:#0066FF;width:190px;text-align:center;font-size:30px;" value="'.$data_i.'"></td>
	                    <td><input name="data_f" id="data_f" type="text" placeholder="data final" style="color:white;background-color:#0033FF;width:190px;text-align:center;font-size:30px;" value="'.$data_f.'"></td>
	                    <td><input type="submit" value="OK"></td>
	                </tr>
	            </table>
	            </center>
	        </form>
	        <center>
				<form method="POST" action="./index.php?a=mestrabalho'.$paginaRedireccionar.''.$idFuncionario.''.$idViatura.'">
		              <select name="mes" style="font-size:30px;">
		                    	';
	    	
		    			//combo box meses do ano
		    			for($i=0;$i<count($meses);$i++)
		    			{
		    				if(($i+1)==$data[1])
		    				{
		    					$sel = ' selected="selected" ';
		    				}else{
		    					$sel="";
		    				}
		    				echo '<option '.$sel.' value="'.($i+1).'">'.$meses[$i].'</option>';
		    			}
	    			
	    			
	    	echo '
		              </select>
		              <select name="ano" style="font-size:30px;">
		             	';
	    	
		    			//combo box anos
		    			for($i=0;$i<count($anos);$i++)
		    			{
		    				if($anos[$i]==$data[0])
		    				{
		    					$sel = ' selected="selected" ';
		    				}else{
		    					$sel="";
		    				}
		    				echo '<option '.$sel.' value="'.$anos[$i].'">'.$anos[$i].'</option>';
		    			}
	    	echo '
		              </select>
		              <input type="submit" value="OK">
	        </form>
	        </center>                    		
	        ';
	}
?>