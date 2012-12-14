  <?php
//WORKTRUCK - GESTAO DE FROTA
//
//MODULO DE ADMINISTRACAO
//
//Desenvolvido por Inforviana - Sistemas Inform�ticos, Lda
//Helder Correia
//Copyright 2012
//
//
//
//
require("config.php"); //ficheiro de configura��o (diferente do PRD)
require("include/funcoes.php");
//
	//ligar � base de dados
	mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
	//seleccionar a tabela a utilizar
	@mysql_select_db($DB_TABLE) or die('Erro de liga��o � base de dados!');
	
	//variaveis globais
	@$accao=$_GET['accao'];
	@$utilizador=$_POST['utilizador'];
	@$password=$_POST['password'];
	
    
    //variaveis 
    if(isset($_GET['a']))
    {
        switch($_GET['a'])
        {
            case 'datatrabalho':
                setcookie("data_i",$_POST['data_i'],time()+3600);
                setcookie("data_f",$_POST['data_f'],time()+3600);
                break;
        }
    }
    
    
if (isset($accao)) { //logout do utilizador 
	if($accao="sair") {
		setcookie("utilizador","",time()-3600); //eliminar as cookies
		header("Location:index.php");
	}
}

//verificar data a usar para o ponto e para os graficos
if(isset($_POST['data_ponto'])){ //verifica se ha passagem de dados para ver a data
	$data_ponto=$_POST['data_ponto'];
}else{
	$data_ponto=date('Y-m-j');
}


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

		<title><?php  echo $titulo;?></title>
		<!-- JAVA  -->
		<script type="text/javascript" src="simpletreemenu.js"></script>
		<script type="text/javascript" src="menu.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />	
		<link type="text/css" href="css/style.css" rel="stylesheet" />	
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"></script>
		<script type="text/javascript" src="jquery.ui.datepicker-pt-BR.js"></script>
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
		                <?php require 'grafico.php';?>		
		
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
					height:280,
					width:300
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
            <center><b><?php echo date('Y-m-j');?><br>WorkTruck GES - INFORVIANA 2012</b></center>
	</body>
</html>