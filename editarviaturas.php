<?php
	if(isset($_GET['id'])) $id=$_GET['id'];
	if(isset($_GET['novo'])) {
		$novo=$_GET['novo'];
	}else{
		$novo = "";
	}
	if(isset($_GET['guardar'])) 
		{
			$guardar=$_GET['guardar'];
		}else{
			$guardar = "";
		}

	if($guardar==1){ //dados a guardar da viatura
		$activo=$_POST['activo'];
		$desc_viatura=$_POST['desc_viatura'];
		$marca_viatura=$_POST['marca_viatura'];
		$modelo_viatura=$_POST['modelo_viatura'];
		$matricula_viatura=$_POST['matricula_viatura'];
		$ano_viatura=$_POST['ano_viatura'];
		$mes_viatura=$_POST['mes_viatura'];
		$tipo_combustivel=$_POST['tipo_combustivel'];
		$tipo_viatura=$_POST['tipo_viatura'];
		$nserie=$_POST['nserie'];
		$nidentificacao=$_POST['nidentificacao'];
                $preco_hora=$_POST['preco_hora'];
                if($_POST['acessorio']=='1')
                {
                    $acessorio='1';
                }else{
                    $acessorio='0';
                }
		
                //ler o ficheiro passado pelo form de edicao da viatura
		if(isset($_FILES["imgfile"]) && $_FILES["imgfile"]["size"]>0)
		{
			$tmpname=$_FILES['imgfile']['tmp_name'];
			$fp=fopen($tmpname,'r');
			$imgdata=fread($fp,filesize($tmpname));
			$imgdata=addslashes($imgdata);
			fclose($fp);
		}
		
		if($novo!=1){ //verifica se e para fazer update ou insert
			if((strlen($imgdata)>5)){
						$q_guardar="UPDATE viaturas SET activo=".$activo.",img='".$imgdata."', marca_viatura='".$marca_viatura."',modelo_viatura='".$modelo_viatura."',matricula_viatura='".$matricula_viatura."',ano_viatura=".$ano_viatura.",mes_viatura=".$mes_viatura.",tipo_combustivel='".$tipo_combustivel."',tipo_viatura='".$tipo_viatura."',imagem_viatura='".$imagem_viatura."',desc_viatura='".$desc_viatura."',nserie='".$nserie."',nidentificacao='".$nidentificacao."',preco_hora=".$preco_hora.",acessorio=".$acessorio." where id_viatura=".$id;
			}else{
						$q_guardar="UPDATE viaturas SET activo=".$activo.",marca_viatura='".$marca_viatura."',modelo_viatura='".$modelo_viatura."',matricula_viatura='".$matricula_viatura."',ano_viatura=".$ano_viatura.",mes_viatura=".$mes_viatura.",tipo_combustivel='".$tipo_combustivel."',tipo_viatura='".$tipo_viatura."',imagem_viatura='".$imagem_viatura."',desc_viatura='".$desc_viatura."',nserie='".$nserie."',nidentificacao='".$nidentificacao."', preco_hora=".$preco_hora.",acessorio=".$acessorio." where id_viatura=".$id;			
			}
                        
                        $acess=$_POST['acessorios']; //obtem os acessorios seleccionados para a viatura
                        mysql_query("delete from acessorios_viatura where id_viatura=".$id); //apagar os acessorios actuais
                        
                        for($i=0;$i<count($acess);$i++)
                        {
                            mysql_query("insert into acessorios_viatura (id_viatura,id_acessorio) values (".$id.",".$acess[$i].")");
                        }
                        
		}else{
			$q_guardar="INSERT INTO viaturas (img,marca_viatura,modelo_viatura,matricula_viatura,ano_viatura,mes_viatura,tipo_combustivel,tipo_viatura,imagem_viatura,desc_viatura,nserie,nidentificacao,preco_hora,acessorio, activo) VALUES ('".$imgdata."','".$marca_viatura."','".$modelo_viatura."','".$matricula_viatura."',".$ano_viatura.",".$mes_viatura.",'".$tipo_combustivel."','".$tipo_viatura."','".$imagem_viatura."','".$desc_viatura."','".$nserie."','".$nidentificacao."',".$preco_hora.",".$acessorio.",".$activo.")";
		}
		if(mysql_query($q_guardar)){ //mensagem 
			$msg= '<font class="font_titulo"><img src="ok.gif">Alterações salvas com sucesso!</font>';
			echo '
			<script type="text/javascript">
			alert("Viatura guardada com sucesso!");
			window.location="index.php?pagina=viaturas";
			</script>
			';
		}else{
			$msg='<font class="font_titulo_erro"><img src="erro.gif">Erro ao gravar as alterações!</font>';
		}
	}
	$q_viatura="select * from viaturas where id_viatura=".$id; //selecciona dados da viatura
	$r_viatura=mysql_query($q_viatura);
	@$n_viatura=mysql_num_rows($r_viatura);
	
	if ($novo==1&&$guardar!=1){ //se for uma viatura nova não apresenta os dados
		
		echo '<table id="hor-minimalist-b" summary="motd">
                    <thead>
                        <th colspan=2>
                            DETALHES DA VIATURA
                        </th>
                    </thead>
                    <tbody>
                        <tr>
                        </tr>
                                
                        <tr>
                        <td>
	<form enctype="multipart/form-data" method="POST" action="index.php?pagina=editarviaturas&id='.$id.'&guardar=1&novo='.$novo.'">
		Activo: <select name="activo">
					<option value=1>Sim</option>
					<option value=0>Nao</option>
				</select>
		<br><br>		
		Nome: <input type="text" class="inp_viatura" name="desc_viatura" value="">
		<br><br>';
	echo 'Marca: <input type="text" class="inp_viatura" name="marca_viatura" value="">
		<br><br>';
	echo 'Modelo: <input type="text" class="inp_viatura" name="modelo_viatura" value="">
		<br><br>';
	echo 'Matricula: <input type="text" class="inp_viatura" name="matricula_viatura" value="">
		<br><br>';
	echo 'Ano/Mês: <input type="text" size=5 name="ano_viatura" value="'.date('Y').'"> <input type="text" size=3 name="mes_viatura" value="'.date('m').'">
		<br><br>';
	echo 'Combustivel: <select name="tipo_combustivel">';
			//tipo de combustivel
			$rc=mysql_query("select * from combustivel order by combustivel");
			$nc=mysql_num_rows($rc);
			for($i=0;$i<$nc;$i++)
			{
				echo '<option value="'.mysql_result($rc,$i,'id_combustivel').'">'.mysql_result($rc,$i,'combustivel').'</option>';
			}

		echo'</select><br><br><input type="checkbox" name="acessorio" value="1" '.$checked.'>Acessorio
		<br><br>';
	echo 'Tipo Viatura: <select name="tipo_viatura">';
			//tipos de viaturas
			$rt=mysql_query("select * from categorias_viatura order by categoria");
			$nt=mysql_num_rows($rt);
			
			for($i=0;$i<$nt;$i++)
			{
				echo '<option value="'.mysql_result($rt,$i,'id_categoria').'">'.mysql_result($rt,$i,'categoria').'</option>';
			}
			//FIM
	echo'</select>
		<br><br>';
	echo 'Nº Serie: <input type="text" class="inp_viatura" name="nserie" value="">
		<br><br>';
	echo 'Nº Identificacao: <input type="text" class="inp_viatura" name="nidentificacao" value="">
		<br><br>';
	echo 'Preço Hora: Eur <input class="inp_viatura_hora" type="text" name="preco_hora" value="'.@mysql_result($r_viatura,0,'preco_hora').'">
		<br><br>';
	echo ' Imagem: <input name="imgfile" type="file" size="30">
		<br><br>';
	echo '</td></tr><tr><td align="right">'.@$msg.'<br><button type="submit">Guardar Alterações</button></form></td></tr></tbody></table>';
		
	}else{  //mostrar dados da viatura
    	//obter estado do funcionario, activo ou desactivado
	$estadoActivo = mysql_result($r_viatura,0,'activo');

	if($estadoActivo == 1)
	{
		$sim = ' selected="selected" ';
		$nao = '';
	}else{
		$sim = '';
		$nao = ' selected="selected" ';
	}
	
	
	echo '<table id="hor-minimalist-b" summary="motd">
            <thead>
                <th colspan=2>DETALHES DA VIATURA</th>
            </thead>
            <tbody><tr></tr><tr><td>
	<form  enctype="multipart/form-data" method="POST" action="index.php?pagina=editarviaturas&id='.$id.'&guardar=1&novo='.$novo.'">
		Activo: <select name="activo">
					<option value=1 '.$sim.'>Sim</option>
					<option value=0 '.$nao.'>Nao</option>
				</select>
		<br><br>		
		Nome: <input type="text" name="desc_viatura" value="'.@mysql_result($r_viatura,0,'desc_viatura').'">
		<br><br>';
	echo 'Marca: <input type="text" class="inp_viatura" name="marca_viatura" value="'.@mysql_result($r_viatura,0,'marca_viatura').'">
		<br><br>';
	echo 'Modelo: <input type="text" class="inp_viatura" name="modelo_viatura" value="'.@mysql_result($r_viatura,0,'modelo_viatura').'">
		<br><br>';
	echo 'Matricula: <input type="text" class="inp_viatura" name="matricula_viatura" value="'.@mysql_result($r_viatura,0,'matricula_viatura').'">
		<br><br>';
	echo 'Ano/Mês: <input type="text" size=5 name="ano_viatura" value="'.@mysql_result($r_viatura,0,'ano_viatura').'"> <input type="text" size=3 name="mes_viatura" value="'.@mysql_result($r_viatura,0,'mes_viatura').'">
		<br><br>';
	echo 'Combustivel: <select name="tipo_combustivel">';
			//tipo de combustivel
			$rc=mysql_query("select * from combustivel order by combustivel");
			$nc=mysql_num_rows($rc);
			for($i=0;$i<$nc;$i++)
			{
				if(mysql_result($rc,$i,'id_combustivel')==@mysql_result($r_viatura,0,'tipo_combustivel')){$selected='selected="selected"';}else{$selected="";}
				echo '<option '.$selected.' value="'.mysql_result($rc,$i,'id_combustivel').'">'.mysql_result($rc,$i,'combustivel').'</option>';
			}
		echo'</select>
		<br><br>';
	echo 'Tipo Viatura: <select name="tipo_viatura">';
			//tipos de viaturas
			$rt=mysql_query("select * from categorias_viatura order by categoria");
			$nt=mysql_num_rows($rt);
			
			for($i=0;$i<$nt;$i++)
			{
				if(mysql_result($rt,$i,'id_categoria')==@mysql_result($r_viatura,0,'tipo_viatura')){$selected='selected="selected"';}else{$selected="";}
				echo '<option '.$selected.' value="'.mysql_result($rt,$i,'id_categoria').'">'.mysql_result($rt,$i,'categoria').'</option>';
			}
			//FIM
                /*CHECK DO ACESSORIO*/
                if(mysql_result($r_viatura,0,'acessorio')==1){
                    $checked = ' checked ';
                }else{
                    $checked = "";
                }
	echo'</select><br><br><input type="checkbox" name="acessorio" value="1" '.$checked.'> Acessorio
		<br><br>';
	echo 'Nº Serie: <input type="text" class="inp_viatura" name="nserie" value="'.@mysql_result($r_viatura,0,'nserie').'">
		<br><br>';
	echo 'Nº Identificacao: <input type="text" class="inp_viatura"  name="nidentificacao" value="'.@mysql_result($r_viatura,0,'nidentificacao').'">
		<br><br>';
        echo 'Preço Hora Base : Eur <input class="inp_viatura_hora" type="text" name="preco_hora" value="'.@mysql_result($r_viatura,0,'preco_hora').'">
        	 <br><br>
        		<input type="button" value="Precos das Obras desta Viatura" onclick="window.location.href=\'./index.php?pagina=precosobra&idviatura='.$id.'\'">
		<br><br>';
	echo '<img class="img_viatura" src="imagem.php?idviatura='.@mysql_result($r_viatura,0,'id_viatura').'"><br>Imagem: <input name="imgfile" type="file" size="20">
		<br><br>';
	echo '</td>
            <td style="vertical-align:middle;width:300px;">
                        ';
                /* ACESSORIOS PARA A VIATURA */
                ler_acessorios($id,$MAX_ACESSORIOS);
                echo '
            </td>
            </tr>
            <tr>
                <td align="center" colspan=2>
                    '.@$msg.'<br><button type="submit">Guardar Alterações</button></form>
                </td>
            </tr>
        </tbody>
       </table>';
	}
?>