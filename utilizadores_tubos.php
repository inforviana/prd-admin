<?php
	//adicionar novo utilizador
	if($_GET['gravar']==1){
		$q_nu="insert into empresa (nome_empresa,utilizador_empresa,password_utilizador,codigo_tubos) values ('".$_POST['nome_empresa']."','".$_POST['utilizador_empresa']."','".$_POST['password_utilizador']."','".$_POST['codigo_tubos']."')";
		mysql_query($q_nu);
	}
	
	//apagar utilizador
	if($_GET['apagar']==1){
		$q_nu="delete from empresa where id_empresa=".$_GET['id'];
		mysql_query($q_nu);
	}

	$q_users="select * from empresa";
	$r_users=mysql_query($q_users);
	$n_users=mysql_num_rows($r_users);
	
	echo'
	<h1>Utlilizadores Tubos</h1><br>
	<form method="POST" action="index.php?pagina=utilizadorestubos&gravar=1">
		<table>
			<thead>
				<tr>
					<th>Empresa</th>
					<th>Utilizador</th>
					<th>Password</th>
					<th>Cod Tubos</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			';
			 
			for($i=0;$i<$n_users;$i++){
				echo '<tr><td>'.mysql_result($r_users,$i,'nome_empresa').'</td><td>'.mysql_result($r_users,$i,'utilizador_empresa').'</td><td>'.mysql_result($r_users,$i,'password_utilizador').'</td><td>'.mysql_result($r_users,$i,'codigo_tubos').'</td><td style="text-align:center;font-size:10px;color:red;"><a href="index.php?pagina=utilizadorestubos&apagar=1&id='.mysql_result($r_users,$i,'id_empresa').'">Apagar</a></td></tr>';
			}
			
			echo '
			<tr><td><input type="text" name="nome_empresa"></td><td><input type="text" name="utilizador_empresa"></td><td><input type="text" name="password_utilizador"></td><td><input type="text" name="codigo_tubos"></td><td><button type="submit">Adicionar</button></td></tr>
			</tbody>
		</table>
		</form> 
	';
?>