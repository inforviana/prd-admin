<center>WORKTRUCK<br>GESTÃO</center><br>
<b><?php echo 'Versão '.$VERSAO_APP;?></b>

<?php 
if(isset($_COOKIE['utilizador'])){
	echo '<br><br><b>Utilizador:</b>'.$_COOKIE['utilizador'];
	echo '<br><a href="index.php?accao=sair"><img src="sair.gif" border=0> Terminar Sessão</a><br>';
?>
<ul class="topnav">
<li><a href="index.php">Inicio</a></li>
<li>Tabelas
	<ul>
	<li>Funcionários
		<ul class="subnav">
			<li><a href="index.php?pagina=funcionarios">Criar/Listar</a></li>
			<li><a href="index.php?pagina=grupos">Grupos</a></li>
		</ul>
	</li>
	<li>Viaturas
		<ul>
			<li><a href="index.php?pagina=viaturas">Criar/Listar</a></li>
		</ul>
	</li>
	</ul>
</li>
<li>Relatórios
	<ul>
		<li>
			Viaturas
		</li>
	</ul>
</li>
<li>Opções
	<ul>
		<li>
			Utilizadores
		</li>
		<li>
			Diversos
		</li>
	</ul>
</li>
</ul>

<script type="text/javascript">
//ddtreemenu.createTree(treeid, enablepersist, opt_persist_in_days (default is 1))
ddtreemenu.createTree("treemenu1", true)
ddtreemenu.createTree("treemenu2", false)
</script>
<?php 
}
?>