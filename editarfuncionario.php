<?php
	$id_funcionario=$_GET['id'];
	@$novo=$_GET['novo'];
	@$guardar=$_GET['guardar'];
	
	if($guardar==1){ //CONDICAO PRINCIPAL PARA VERIFICAR A OPERAÇÃO A EFETUAR (CRIAR NOVO)------------------------------------------------------------------------------------------------------------
		$nome=$_POST['nome'];
		$morada=$_POST['morada'];
		$cp=$_POST['cp'];
		$localidade=$_POST['localidade'];
		$telefone=$_POST['telefone'];
		$telemovel=$_POST['telemovel'];
		$pin=$_POST['pin'];
		$grupo=$_POST['grupo'];
                $preco_hora_normal=$_POST['preco_hora_normal'];
                $preco_hora_extra=$_POST['preco_hora_extra'];
                $preco_sabado=$_POST['preco_sabado'];
                if(isset($_FILES["imgfile"]) && $_FILES["imgfile"]["size"]>0)
		{
			$tmpname=$_FILES['imgfile']['tmp_name'];
			$fp=fopen($tmpname,'r');
			$imgdata=fread($fp,filesize($tmpname));
			$imgdata=addslashes($imgdata);
			fclose($fp);
		}
		if($novo!=1){
			$q_guardar="UPDATE funcionario SET nome_funcionario='".$nome."',grupo_funcionario='".$grupo."',morada_funcionario='".$morada."',cp='".$cp."',localidade='".$localidade."',telefone_funcionario='".$telefone."',telemovel_funcionario='".$telemovel."',pin_funcionario='".$pin."', preco_hora_normal='".$preco_hora_normal."',preco_hora_extra='".$preco_hora_extra."', preco_sabado='".$preco_sabado."' where id_funcionario=".$id_funcionario;
		}else{
			$q_guardar="INSERT INTO funcionario (nome_funcionario,grupo_funcionario,morada_funcionario,cp,localidade,telefone_funcionario,telemovel_funcionario,pin_funcionario,preco_hora_normal,preco_hora_extra, preco_sabado) VALUES ('".$nome."','".$grupo."','".$morada."','".$cp."','".$localidade."','".$telefone."','".$telemovel."','".$pin."','".$preco_hora_normal."','".$preco_hora_extra."','".$preco_sabado."')";
		}
		if(mysql_query($q_guardar)){
                        
			$msg= '<font class="font_titulo"><img src="ok.gif">Alterações salvas com sucesso!</font>';
		}else{
			$msg='<font class="font_titulo_erro"><img src="erro.gif">Erro ao gravar as alterações!</font>';
			echo $q_guardar; 
		}
		//msg e redir da janela
		echo '
		<script type=text/javascript>
		window.location="index.php?pagina=funcionarios";
		</script>
		';
	}
	if ($novo==1&&$guardar!=1){ //CONDICAO PARA CRIAR UM NOVO-----------------------------------------------------------------------------------------------------------------------------
		echo '
	<table id="hor-minimalist-b" summary="motd"><thead><th>DADOS DO FUNCIONARIO</th><th></th></thead>
	<tbody><tr></tr>
	<tr><td><form enctype="multipart/form-data" method="POST" action="index.php?pagina=editarfuncionario&id='.$id_funcionario.'&guardar=1&novo='.$novo.'">
		Nome:<input type="text" size=40 name="nome" value="">
		<br><br>
		Morada:<input type="text" size=40 name="morada" value="">
		<br><br>
		Código Postal:<input type="text" size=10 name="cp" value="">	<br>
		Localidade: <input type="text" size=25 name="localidade" value="">
		<br><br>
		Telefone:<input type="text" size=15 name="telefone" value=""><br><br>		
		Telemovel:<input type="text" size=15 name="telemovel" value="">	
		<br><br>
                <div id="inputfoto">
                Imagem: <br>
                <input type="file" name="imgfile" class="inputfoto">
                </div>
                <br>
		PIN PRD:<input type="text" name="pin" size=6 value="">	
		<br><br>
		Grupo : ';
		
	$q_grupos="select * from grupos_funcionario";
	$r_grupos=mysql_query($q_grupos);
	$n_grupos=mysql_num_rows($r_grupos);
	
	for($j=0;$j<$n_grupos;$j++){ //desenhar os grupos de funcionarios
		if((@mysql_result($r_funcionario,0,'grupo_funcionario'))==(@mysql_result($r_grupos,$j,'desc_grupo'))){
			$checked='checked="checked"';
		}else{
			$checked="";
		}
		echo '<br><input type="radio" name="grupo" value="'.mysql_result($r_grupos,$j,'desc_grupo').'" '.$checked.'>'.mysql_result($r_grupos,$j,'desc_grupo').'';
	}
        
        //graficos e info lateral e guardar as alterações
	echo '</td>
	<td>
	';
	include 'info_funcionario.php';
	
	//botao para guardar os funcionarios
	echo '
	</td>
	</tr><tr><td align="right" colspan=2>'.$msg.'<br><button type="submit">Guardar Alterações</button></form></td></tr></tbody></table>
	';
		
		
	}else{ //CONDICAO SE FOR PARA EDITAR UM JA EXISTENTE --------------------------------------------------------------------------------------------------------------------------------------------
		
	$q_funcionario="select * from funcionario where id_funcionario=".$id_funcionario;
	$r_funcionario=mysql_query($q_funcionario);
	$n_funcionario=mysql_num_rows($r_funcionario);
	
	//procedimentos com dados nas tabelas
	@$func=$_GET['func'];
	@$tipo=$_GET['tipo'];
	@$ida=$_GET['ida'];
	
	if($func=='apagar'){ //APAGAR
		if($tipo=='comb'){ //apagar combustivel
			$query="delete from mov_combustivel where id_movcombustivel=".$ida;
		}elseif($tipo=='horas'){
			$query="delete from mov_viatura where id_movviatura=".$ida;
		}
	}
	if(isset($query)){mysql_query($query); }//query para apagar os dados
	
	echo '
	<table id="hor-minimalist-b" summary="motd"><thead><th>DADOS DO FUNCIONARIO</th><th></th></thead>
	<tbody><tr></tr>
	<tr><td><form method="POST" action="index.php?pagina=editarfuncionario&id='.$id_funcionario.'&guardar=1&novo='.$novo.'" enctype="multipart/form-data">
		Nome:<input type="text" size=40 name="nome" value="'.mysql_result($r_funcionario,0,'nome_funcionario').'">
		<br><br>
		Morada:<input type="text" size=40 name="morada" value="'.mysql_result($r_funcionario,0,'morada_funcionario').'">
		<br><br>
		Código Postal:<input type="text" size=10 name="cp" value="'.mysql_result($r_funcionario,0,'cp').'">	<br>
		Localidade: <input type="text" size=25 name="localidade" value="'.mysql_result($r_funcionario,0,'localidade').'">
		<br><br>
		Telefone:<input type="text" size=15 name="telefone" value="'.mysql_result($r_funcionario,0,'telefone_funcionario').'"><br><br>		
		Telemovel:<input type="text" size=15 name="telemovel" value="'.mysql_result($r_funcionario,0,'telemovel_funcionario').'">	
		<br><br>
                <div id="inputfoto">
                Imagem: <br>
                <input type="file" name="imgfile" class="inputfoto">
                </div>
                <br>
		PIN PRD:<input type="text" name="pin" size=6 value="'.mysql_result($r_funcionario,0,'pin_funcionario').'">	
		<br><br>
		Grupo : ';
	
	$q_grupos="select * from grupos_funcionario";
	$r_grupos=mysql_query($q_grupos);
	$n_grupos=mysql_num_rows($r_grupos);
	
	for($j=0;$j<$n_grupos;$j++){ //desenhar os grupos de funcionarios
		if((mysql_result($r_funcionario,0,'grupo_funcionario'))==(mysql_result($r_grupos,$j,'desc_grupo'))){
			$checked='checked="checked"';
		}else{
			$checked="";
		}
		echo '<br><input type="radio" name="grupo" value="'.mysql_result($r_grupos,$j,'desc_grupo').'" '.$checked.'>'.mysql_result($r_grupos,$j,'desc_grupo').'';
	}
	//graficos e info lateral e guardar as alterações
	echo '</td>
	<td>
	';
	include 'info_funcionario.php';
	
	//botao para guardar os funcionarios
	echo '
	</td>
	</tr><tr><td align="right" colspan=2>'.@$msg.'<br><button type="submit">Guardar Alterações</button></form></td></tr></tbody></table>
	';

	//ultimos registos do funcionario
		$q_mov_horas="select mov_viatura.id_movviatura, mov_viatura.data, funcionario.nome_funcionario, viaturas.desc_viatura, viaturas.marca_viatura, viaturas.modelo_viatura, mov_viatura.horas_viatura
from mov_viatura
inner join funcionario on funcionario.id_funcionario = mov_viatura.id_funcionario
inner join viaturas on viaturas.id_viatura = mov_viatura.id_viatura
where mov_viatura.id_funcionario=".$id_funcionario." and mov_viatura.horas_viatura
order by date(mov_viatura.data) desc
limit 2";
	$r_mov_horas=mysql_query($q_mov_horas); //resultados da query
	$n_mov_horas=mysql_num_rows($r_mov_horas); //numero de linhas
	//query lista movimentos combustivel
	$q_mov_combustivel="select mov_combustivel.id_movcombustivel, mov_combustivel.data, funcionario.nome_funcionario, viaturas.desc_viatura, viaturas.marca_viatura, viaturas.modelo_viatura, mov_combustivel.valor_movimento
from mov_combustivel
inner join funcionario on funcionario.id_funcionario = mov_combustivel.id_funcionario
inner join viaturas on viaturas.id_viatura = mov_combustivel.id_viatura
where mov_combustivel.id_funcionario=".$id_funcionario." and mov_combustivel.valor_movimento  > 0
order by date(mov_combustivel.data) desc
limit 2";
	$r_mov_combustivel=mysql_query($q_mov_combustivel);
	$n_mov_combustivel=mysql_num_rows($r_mov_combustivel);
	
	//desenhar tabelas com os registos	
		echo '<table id="hor-minimalist-b" summary="motd">
		<thead>
		<tr>
			<th colspan="5">Últimos registos</th>
		</tr>
		<tr>
			<th>Tipo</th>
			<th>Data e Hora</th>
			<th>Descrição</th>
			<th>Marca</th>
			<th>Modelo</th>
			<th>Horas</th>
			<th colspan=3>Operações</th>
		</tr>
		</thead>
		<tbody>';
		
		for($i=0;$i<$n_mov_horas;$i++){ //obter linhas dos ultimos movimentos
			echo '<tr>
					<td>
						<img src="camiao.png" height="20" border=0>
					</td>
					<td>
						'.mysql_result($r_mov_horas,$i,'data').'
					</td>
					<td>
						'.mysql_result($r_mov_horas,$i,'desc_viatura').'
					</td>
					<td>
						'.mysql_result($r_mov_horas,$i,'marca_viatura').'
					</td>
					<td>
						'.mysql_result($r_mov_horas,$i,'modelo_viatura').'
					</td>
					<td>
						'.intval(mysql_result($r_mov_horas,$i,'horas_viatura')/60).':'.(mysql_result($r_mov_horas,$i,'horas_viatura')%60).'
					</td>
					<td align="center">
						<a href="./index.php?pagina=editarhoras&id='.mysql_result($r_mov_horas,$i,'id_movviatura').'"><img src="editar.png" border=0></a>
					</td>
					<td align="center">
						<input type="image" onclick="apagar(\'./index.php?pagina=listagemhoras&idfuncionario='.$id_funcionario.'&func=apagar&tipo=horas&id='.mysql_result($r_mov_horas,$i,'id_movviatura').'\')" src="delete.gif">
					</td>								
			</tr>';
		}
		echo'
		</tbody>
	</table>
	';
		//TABELA DO COMBUSTIVEL (ULTIMOS 5 MOVIMENTOS)
	echo '
	<table id="hor-minimalist-b" summary="motd">
		<thead>
		<tr>
			<th>Tipo</th>
			<th>Data e Hora</th>
			<th>Descrição</th>
			<th>Marca</th>
			<th>Modelo</th>
			<th>Litros</th>
			<th colspan=3>Operações</th>
		</tr>
		</thead>
		<tbody>';
		for($i=0;$i<$n_mov_horas;$i++){ //obter linhas dos ultimos movimentos
			echo '<tr>
					<td>
						<img src="gasoleo.png" height="20" border=0>
					</td>
					<td>
						'.mysql_result($r_mov_combustivel,$i,'data').'
					</td>
					<td>
						'.mysql_result($r_mov_combustivel,$i,'desc_viatura').'
					</td>
					<td>
						'.mysql_result($r_mov_combustivel,$i,'marca_viatura').'
					</td>                            
					<td>
						'.mysql_result($r_mov_combustivel,$i,'modelo_viatura').'
					</td>
					<td>
						'.mysql_result($r_mov_combustivel,$i,'valor_movimento').' L
					</td>
					<td align="center">
						<a href="./index.php?pagina=editarcomb&id='.mysql_result($r_mov_combustivel,$i,'id_movcombustivel').'"><img src="editar.png" border=0></a>
					</td>
					<td align="center">
						<a href="./index.php?func=apagar&tipo=comb&id='.mysql_result($r_mov_combustivel,$i,'id_movcombustivel').'"><img src="delete.gif" border=0></a>
					</td>							
			</tr>';
		}
		echo'
		</tbody>
	</table>
	';	
	}
?>