<?php
	//adicionar novo utilizador
	if($_GET['gravar']==1){
		$q_nu="insert into users (username, password) values ('".$_POST['user']."','".md5($_POST['password'])."')";
		mysql_query($q_nu);
	}
	
	//apagar utilizador
	if($_GET['apagar']==1){
		$q_nu="delete from users where id_user=".$_GET['id'];
		mysql_query($q_nu);
	}

	$q_users="select * from users";
	$r_users=mysql_query($q_users);
	$n_users=mysql_num_rows($r_users);
	
	echo'
	<h1>Utlilizadores</h1><br>
	<form method="POST" action="index.php?pagina=utilizadores&gravar=1">
		<table>
			<thead>
				<tr>
					<th>Utilizador</th>
					<th>Password</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			';
			 
			for($i=0;$i<$n_users;$i++){
				echo '<tr><td>'.mysql_result($r_users,$i,'username').'</td><td>'.mysql_result($r_users,$i,'password').'</td><td style="text-align:center;font-size:10px;color:red;"><a href="index.php?pagina=utilizadores&apagar=1&id='.mysql_result($r_users,$i,'id_user').'">Apagar</a></td></tr>';
			}
			
			echo '
			<tr><td><input type="text" name="user"></td><td><input type="text" name="password"></td><td><button type="submit">Adicionar</button></td></tr>
			</tbody>
		</table>
		</form> 
	';
?>