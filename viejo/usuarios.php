<?php
/*
 * Recibe todas las acciones que se hacen sobre el perfil de usuario autenticado, listar lamparas, registros, enviar ordenes, etc...
 */
	include 'clases/funciones.php';
	include 'clases/accionesbd.php';
	     
	Iniciar_Sesion();
	function Traducir_Filtro($entrada) 
	{
		//Sin USAR ya
		if ($entrada["id"] == "nofiltro")
		{
			$_SESSION["filtrol"] = array();
			return;
		}
		if ( $entrada["id"] != CUALQUIERA)
			$_SESSION["filtrol"]["id_lampara"] = " = '".$entrada["id"]."'";
		else
			$_SESSION["filtrol"]["id_lampara"] = "";
		if ( $entrada["tipo"] != CUALQUIERA)
			$_SESSION["filtrol"]["tipo"] = " = '".$entrada["tipo"]."'";
		else 
			$_SESSION["filtrol"]["tipo"] = "";
		if ($entrada["estado"] != "")
			$_SESSION["filtrol"]["estado"] = " = ".$entrada["estado"]."";
		else
			$_SESSION["filtrol"]["estado"] = "";
		if ($entrada["nombre"] != "")
			$_SESSION["filtrol"]["nombre"] = " ILIKE '%".$entrada["nombre"]."%'";
		else
			$_SESSION["filtrol"]["nombre"] = "";
	}
	if (isset($_POST["formulario"]))
	{
		//Ya no se usa
		if ($_POST["formulario"] == "filtro")	//Si vamos a mostrar la pagina con filtros...
		{
			if ($_POST["columna"] == "nofiltro")
			{
				$_SESSION["filtro"] = "";
				echo Listar_Registros();
			}
			else
				echo Listar_Registros($_POST);
			exit;
		}
		if ($_POST["formulario"] == "orden")
		{
			if (isset($_POST["nombre"]) & isset($_POST["valor"]))
				Dar_Orden($_POST["nombre"],$_POST["valor"]);
			echo Listar_Lamps();
			exit;	
		}
		if ($_POST["formulario"] == "lampara")
		{
			echo Listar_Lamps();
			exit;
		}
		if ($_POST["formulario"] == "registro")
		{
			echo Listar_Registros();
			exit;
		}
		if ($_POST["formulario"] == "filtrol")
		{
			Traducir_Filtro($_POST);
			echo Listar_Lamps();
			exit;
		}
	}
	/*
	 * Si no hay llamadas ajax, se muestra toda la pagina
	 */
	$_SESSION["filtro"]="";
	$_SESSION["filtrol"] = array();
	$pagina = Cargar_Plantilla("usuarios.html");
	$pagina = str_replace("--nombre--", $_SESSION["nombre"], $pagina);
	$pagina = str_replace("<!--lamparas-->", Selector_Lamparas(), $pagina);
	$pagina = str_replace("<!--tipo_lampara-->", Selector_Tipos(), $pagina);
	$pagina = str_replace("<!--tabla_lamparas-->", Listar_Lamps(), $pagina);
	//$pagina = str_replace("<!--tabla_registro-->", Listar_Registros(), $pagina);
	$pagina = str_replace("--btnfiltro--", "Filtrar", $pagina);
	echo $pagina; 
?>