<?php
		echo '
				<div style="width:600px;margin-left: auto;margin-right:auto; text-align:center">
					<ul class="dropdown">
						<li><a href="index.php"><span>Inicio</span></a></li>
						<li><a href="#">Tabelas</a>
							<ul class="sub_menu">
								<li><a href="index.php?pagina=funcionarios"><span>Funcionarios</span></a></li>
								<li><a href="index.php?pagina=grupos"><span>Grupos</span></a></li>
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
										<li><a href="#">Totais Mensais</a></li>
										<li><a href="#">Totais Por Viatura</a></li>
										<li><a href="#">Totais Por Funcionario</a></li>
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
								<li><a href="#">Horas por Funcionario</a></li>
								<li><a href="#">Horas por Viatura</a></li>
							</ul>	
						</li>
						<li><a href="index.php"><span>Opções</span></a>
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

		';
?>