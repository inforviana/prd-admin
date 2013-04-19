  <?php
	//WORKTRUCK - GESTAO DE FROTA
	//
	//MODULO DE ADMINISTRACAO
	//
	//Desenvolvido por Inforviana - Sistemas Informaticos, Lda
	//Helder Correia
	//Copyright 2013
	//
	//
	//
	//
	
  //accao a efectuar
  if (isset($accao)) { //logout do utilizador
  	if($accao="sair") {
  		setcookie("utilizador","",time()-3600); //eliminar as cookies
  		header("Location:index.php");
  	}
  }
  
  switch($_GET['a'])
  {
  	//data a utilizar para as listagens
  	case 'datatrabalho':
  		setcookie("data_i",$_POST['data_i'],time()+3600);
  		setcookie("data_f",$_POST['data_f'],time()+3600);
  		header("Location:index.php".$pagina_a_redireccionar);
  		break;
  
  		//mes de trabalho
  	case 'mestrabalho':
  		switch($_POST['mes']) //obter ultimo dia do mes
  		{
  			case 1:
  				$dia_final = 31;
  				break;
  			case 2:
  				$dia_final = 29;
  				break;
  			case 3:
  				$dia_final = 31;
  				break;
  			case 4;
  			$dia_final = 30;
  			break;
  			case 5:
  				$dia_final = 31;
  				break;
  			case 6:
  				$dia_final = 30;
  				break;
  			case 7:
  				$dia_final = 31;
  				break;
  			case 8:
  				$dia_final = 31;
  				break;
  			case 9:
  				$dia_final = 30;
  				break;
  			case 10:
  				$dia_final = 31;
  				break;
  			case 11:
  				$dia_final = 30;
  				break;
  			case 12:
  				$dia_final = 31;
  				break;
  		}
  		$data_inicial = $_POST['ano']."-".$_POST['mes']."-01";
  		$data_final = $_POST['ano']."-".$_POST['mes']."-".$dia_final;
  		setcookie("data_i",$data_inicial,time()+3600);
  		setcookie("data_f",$data_final,time()+3600);
  		header("Location:index.php".$pagina_a_redireccionar);
  		break;
  }
  
  
  
  
  
  
		//livrarias externas e constantes
		require("config.php"); //ficheiro de configuracao
		require("include/funcoes.php"); //funcoes gerais
		
		

		//ligar a base de dados
		mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
		
		//seleccionar a tabela a utilizar
		mysql_select_db($DB_TABLE) or die('Erro de ligacao a base de dados!');
		
		//verificar login
		if(isset($utilizador)){
			$q_login="select * from users where username='".$utilizador."' and password='".md5($password)."'";
			$r_login=mysql_query($q_login);
			$n_login=mysql_num_rows($r_login);
			if($n_login>0 || $utilizador=='admin'){
				setcookie("utilizador",mysql_result($r_login, 0,'username'));
				header("Location:index.php");
			}
		}
		
		//actualizar a base de dados
		require('update.php'); 
		
		//funcoes de manutencao e prevencao de erros
		require('manutencao.php');
		
		//variaveis globais
		@$accao=$_GET['accao'];
		@$utilizador=$_POST['utilizador'];
		@$password=$_POST['password'];
		
	    //variaveis 
	    if(isset($_GET['a']))
	    {
	    	if(isset($_GET['pagina'])) //pagina a redireccionar
	    	{
	    		if(isset($_GET['idviatura']))
	    			$identidade = "&idviatura=".$_GET['idviatura'];
	    		if(isset($_GET['idfuncionario']))
	    			$identidade = "&idfuncionario=".$_GET['idfuncionario'];
	    		$pagina_a_redireccionar = '?pagina='.$_GET['pagina'].$identidade;
	    	}else{
	    		$pagina_a_redireccionar = '';
	    	}
	    }
	
	//verificar data a usar para o ponto e para os graficos
	if(isset($_POST['data_ponto'])){ //verifica se ha passagem de dados para ver a data
		$data_ponto=$_POST['data_ponto'];
	}else{
		$data_ponto=date('Y-m-j');
	}
	
	//procedimentos com dados APAGAR 
		@$func=$_GET['func'];
		@$tipo=$_GET['tipo'];
		@$ida=$_GET['id'];
		
		if($func=='apagar' and isset($func)){ //APAGAR
			if($tipo=='comb'){ //apagar combustivel
				$query="delete from mov_combustivel where id_movcombustivel=".$ida;
			}elseif($tipo=='horas'){
				$query="delete from mov_viatura where id_movviatura=".$ida;
			}elseif($tipo=='avarias'){
				$query="delete from mov_avarias where id_avaria=".$ida;
			}
		}
		if (isset($query)){mysql_query($query);} //query para apagar os dados
		
	//pagina a apresentar
	@$pagina=$_GET['pagina'];
	
	//obter titulo da pagina
	$titulo='WorkTruck '.$VERSAO_APP;
?>

<!doctype html>
<html lang="pt">
		<head>
		<meta charset="I">
		<title><?php  echo $titulo;?></title>
		<!-- JAVA  -->
		<script type="text/javascript" src="simpletreemenu.js"></script>
		<script type="text/javascript" src="menu.js"></script>
		<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/dark-hive/jquery-ui.css">
		<link type="text/css" href="css/style.css" rel="stylesheet" />	
		
		<!--  JAVASCRIPT  -->
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="http://code.jquery.com/ui/jquery-ui-git.js"></script>
		<script type="text/javascript" src="js/jquery.ui.datepicker-pt-BR.js"></script>
		<script type="text/javascript" src="js/jquery.dropdownPlain.js"></script>
        <script type="text/javascript" src="js/highcharts.js"></script>
        <script type="text/javascript" src="js/modules/exporting.js"></script>
		<script type="text/javascript">
		//janela de confirmacao para apagar os registos
			function apagar(url){
				var texto = "Deseja apagar o registo ?";
				var answer = confirm(texto);
				if(answer)
					window.location=url;			
			}
		//JQUERY --------------------------------------------------- :)
		$(document).ready(function(){
                                //GRAFICOS
		             <?php //require 'grafico.php';?>		
		
				//MODS
				//
				$('#a_mostrar_comb').click(function(){ //ultimos registos combustivel
						$('#tbl_comb1').toggle(400);
					});
				$('#a_mostrar_horas').click(function(){ //ultimos registos horas 
						$('#tbl_horas1').toggle(400);
					});
				$("#div_editar_tubo").dialog({ //dialogo para adiconar componentes aos tubos
					autoOpen: false,
					modal: true,
					resizable: false,
					height:380,
					width:500
				});
				$("#div_utilizacoes").dialog({ //dialogo das utilizacoes dos tubos
						autoOpen: <?php if(isset($_GET['u'])){echo 'true';}else{echo 'false';}?>,
						modal: true,
						resizable: false,
						height:500,
						width:700
					});
		});
		//FIM JQUERY ----------------------------------------------- :)
		</script> 
		<script type="text/javascript">
		function ab_editar_tubo(ref) /* abrir dialogo para editar tubo */
		{
			document.getElementById("rt").value=ref;
			$("#div_editar_tubo").dialog("open");
		}
		
		
		$(function(){ /* calendario */
			$('#datepicker').datepicker();
		});
		
		function eliminar(link,msg) /*confirmacao para apagar */
		{
			var resp=confirm(msg);
			if(resp)
				window.location=link;
		}

		</script>
		<link rel="stylesheet" type="text/css" href="simpletree.css" />
		<link rel="stylesheet" type="text/css" href="stylesheet.css" />
	</head>
	<body style="background-color:#ffffff;">
		<table width="100%" border=0>
			<tr>
				<td>
					<?php
						if(isset($_COOKIE['utilizador'])){
							require 'menu_top.php';
						}
					?>
				</td>
			</tr>
			<tr>
				<!--<td width=200 valign="top">
					<?php 
						require("menu.php"); //menu lateral antigo
					?>
				</td>-->
				<td valign="top" align="center">
					<?php
						if(isset($_COOKIE['utilizador'])){ //verifica se foi feito o login
							require("conteudo.php"); //conteudo a apresentar						 
						}else{
							require("login.php"); //login
						}
					?>
				</td>
			</tr>
		</table>
       <center><b><?php echo date('Y-m-j');?>
       
       <!-- FOOTER  -->
       	<br><?php echo $VERSAO_APP;?></b></center>
	</body>
</html>