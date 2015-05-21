<?php
	include '../clases/funciones.php';
	include "../clases/pgquerys.php";
	function Login($usuario, $pass) 
	{//obtiene de la base de datos el nombre de usuario, compara pass y crea la sesion
		$sql_obj = new sql_query();
		$sql = "SELECT usuario,nombre,tipo FROM t_usuario WHERE clave = '".$pass."' ";
		$result = $sql_obj->consulta($sql);
		$res = array();
		foreach ($result as $fila) 
			if ( $fila['usuario'] == $usuario )
			{
					$res = $fila;
					break;
			}	
		$sql_obj->__destruct();
		return $res;
	}
	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//	//
	if(!isset($_POST['usuario']) || !isset($_POST['clave']))
	{
		Redirec("../index.php");
		exit();
	}
	$usuario = $_POST["usuario"];
	$pass = $_POST["clave"];
	
	$result = Login($usuario, $pass);
	if(!isset($result['usuario']))
	    Redirec("../index.php?error=");
	else
	{
        session_start();
        $_SESSION["autenticado"] = true;
        $_SESSION["nombre"]= $result["nombre"];
        $_SESSION["tipo"]= $result["tipo"];
        $_SESSION["filtro"] = "";
        $_SESSION["filtrol"] = array();
      
        Redirec("../usuarios.php");
	}
?>