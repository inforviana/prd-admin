<?php
	setcookie("utilizador","",time()-3600); //eliminar as cookies
	header("Location: ./index.php");
?>