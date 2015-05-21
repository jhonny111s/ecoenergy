<?php
	class sql_query 
	{
		private $dbh;
		function sql_query()
		{
			try 
			{
				$this->dbh = new PDO("pgsql:dbname=postgres;host=localhost", "postgres", "oz1234" ); //nuestra bd
			}
			catch (PDOException $e) 
			{
				echo "paila base";
				exit;
			}
		}
		
		function __destruct()
		{
			//$this->conex = null;
			$this->dbh = null;
		}
		function consulta($cta)
		{
			//$cta = $this->Limpiar($cta);
			$result = $this->dbh->query($cta);
			$error = $this->dbh->errorInfo();
			if(empty($error[1]))
			{
				$result->setFetchMode(PDO::FETCH_ASSOC);
				$table = array();	
				while ($row = $result->fetch())
				{
					$table[] = $row;//almaceno el resultado de la consulta en un array
				}
				return $this->Verificar($table);
			}
			else
			{
				return $this->Verificar($error);
			}
		}
		function Verificar($resultado)
		{
			//toma el resultado de la consulta hecha en el marco del pdo y traduce el resultado en caso de error,
			//en caso de NO error, simplemente deja seguir el programa.
			//en caso de error, redirecciona el programa a la pagina principal de perfil con mensaje de error
			if (is_array($resultado) )
			{
				if ( isset($resultado[0]) and !is_array($resultado[0]) )
					if ( $resultado[0] == 23505 )
					{
						echo "Valor repetido";
						//return "Valor repetido";
						//exit();
					}
					else
					{
						echo var_dump($resultado)."<br/>Capturado En Verificar!";
						//return var_dump($resultado)."<br/>Capturado En Verificar!";
						//exit();
					}
			}
			else
			{
				Redirec("index.php?error=inesperado");
				exit;
			}
			return $resultado;
		}
		function Limpiar($qry) 
		{
			return pg_escape_string($qry);
		}
	}
?>