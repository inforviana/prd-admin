<?php
	$id=$_GET['id'];
	@$novo=$_GET['novo'];
	@$guardar=$_GET['guardar'];
	if($guardar==1){
		$grupo=$_POST['grupo'];
		if($novo!=1){
			$q_guardar="UPDATE grupos_funcionario SET desc_grupo='".$grupo."' where id_grupo=".$id;
		}else{
			$q_guardar="INSERT INTO grupos_funcionario (desc_grupo) VALUES ('".$grupo."')"; 
		}
		if(mysql_query($q_guardar)){
			$msg= '<font class="font_titulo"><img src="ok.gif">Alterações salvas com sucesso!</font>';
		}else{
			$msg='<font class="font_titulo_erro"><img src="erro.gif">Erro ao gravar as alterações!</font>';
		}
	}
        
        if(isset($_GET['id']))
        {
            $q_funcionario="select * from grupos_funcionario where id_grupo=".$id;
            $r_funcionario=mysql_query($q_funcionario);
            $n_funcionario=mysql_num_rows($r_funcionario);
            $grupo=mysql_result($r_funcionario,0,'desc_grupo');
            $idn='&id='.$id;
        }else{
            $grupo="";
            $idn="";
        }
	
        
       
	
	echo '<table id="hor-minimalist-b" summary="motd"><thead><th>DADOS DO GRUPO</th></thead><tbody><tr></tr><tr><td><form method="POST" action="index.php?pagina=editargrupos'.$idn.'&guardar=1&novo='.$novo.'">
		Nome:<input type="text" size=40 name="grupo" value="'.$grupo.'">
		<br><br>';
	echo '</td></tr><tr><td align="right">'.@$msg.'<br><button type="submit">Guardar Alterações</button></form></td></tr></tbody></table>';
?>