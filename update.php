<?php 
	$ultima_versao = '0.7'; //MANDATORY -> definir ultima versao
	
	$r_versao = mysql_query("select value from config where attrib = 'versao'"); //obter a versao actual
	
	$versao_actual = mysql_result($r_versao,0,0);

	
	while($versao_actual != $ultima_versao)
	{
		$r_versao = mysql_query("select value from config where attrib = 'versao'"); //obter a versao actual
		
		$versao_actual = mysql_result($r_versao,0,0);
		
		
		switch($versao_actual) //MANDATORY -> actualizacao a efectuar
		{
			case '0.1':
				//cria a tabela de acessorios
				mysql_query("CREATE TABLE acessorios_viatura(id_acessorios_viatura INT NOT NULL AUTO_INCREMENT PRIMARY KEY, id_viatura INT NOT NULL, id_acessorio INT NOT NULL)");
				mysql_query("UPDATE config SET value='0.2' WHERE attrib='versao'");
				break;
			case '0.2':
				//actualiza todas as horas facturadas com o preco hora da viatura ate esta actualizacao
				mysql_query("update mov_viatura, viaturas
						     set mov_viatura.preco_viatura = viaturas.preco_hora
							 where mov_viatura.id_viatura = viaturas.id_viatura and mov_viatura.preco_viatura = 0");
				mysql_query("UPDATE config SET value='0.3' WHERE attrib='versao'");
				break;
			case '0.3':
				//cria a tabela das obras
				mysql_query("CREATE TABLE obras(id_obra INT NOT NULL AUTO_INCREMENT PRIMARY KEY, descricao_obra VARCHAR(200) NULL)");
				mysql_query("ALTER TABLE mov_viatura ADD id_obra INT NOT NULL");
				mysql_query("UPDATE config SET value='0.4' WHERE attrib='versao'");
				break;
			case '0.4':
				//seleccionar a obra por defeito
				mysql_query("ALTER TABLE obras ADD defeito INT NULL");
				mysql_query("UPDATE config SET value='0.5' WHERE attrib='versao'");
				break;
			case '0.5':
				//criar tabela para guardar os precos das obras por cada viatura
				mysql_query("CREATE TABLE obras_precos(id_preco_obra INT NOT NULL AUTO_INCREMENT PRIMARY KEY, id_viatura INT NOT NULL, id_obra INT NOT NULL, preco_obra FLOAT(10,2) NOT NULL)");
				mysql_query("UPDATE config SET value='0.6' WHERE attrib='versao'");
				break;
			case '0.6':
				mysql_query("UPDATE funcionario SET preco_hora_normal = 0 WHERE preco_hora_normal IS NULL");
				mysql_query("UPDATE funcionario SET preco_hora_extra = 0 WHERE preco_hora_extra IS NULL");
				mysql_query("UPDATE funcionario SET preco_sabado = 0 WHERE preco_sabado IS NULL");
				mysql_query("UPDATE config SET value='0.7' WHERE attrib='versao'");
		}
	}
	
?>