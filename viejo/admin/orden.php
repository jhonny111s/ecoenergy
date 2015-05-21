<?php
/*
 * Muestra menu de ordenes posibles y procesa las ordenes que piden
 */
$raiz = "../";
include '../clases/funciones.php';
include '../clases/accionesbd.php';

Iniciar_Sesion();
if(isset($_POST["accion"]) & isset($_POST["id"]))
{
	/*
	 * Si se esta haciendo una peticion ajax
	 * peticion de enviar
	 */
	if ($_POST["accion"] == "enviar")
	{
		if(isset($_POST["parametro"]) & isset($_POST["valor"]))
		{
				$agrego = Agregar_Orden($_POST["id"],$_POST["parametro"],$_POST["valor"] );
				$retorno["error"] = $agrego;
		}else
		$retorno["error"] = "Falta el parametro y el valor";
		echo json_encode($retorno);
		exit;
	}
	//peticion de cancelar
	if ($_POST["accion"] == "cancelar")
	{
		if(isset($_POST["parametro"]))
		{
			$elimino = Eliminar_Orden($_POST["id"],$_POST["parametro"]);
			if($elimino)
			{
				$retorno["error"] = "";
			}else {
				$retorno["error"] = "No pudo elimiar la orden, ya fue cumplida o eliminada.";
			}
		}
		else
		$retorno["error"] = "Falta el parametro";
		echo json_encode($retorno);
		exit;
	}
	//peticion de actualizar una fila o TR
	if ($_POST["accion"] == "tr")
	{
		if(isset($_POST["parametro"]))
		{
			$tr = Listar_Param_Act_Y_Ordenes_TR($_POST["id"],$_POST["parametro"]);
			$retorno["error"] = "";
			$retorno["tr"] = $tr;
		}
		else
		$retorno["error"] = "Falta el parametro";
		echo json_encode($retorno);
		exit;

	}
	//peticion de cacelar todo
	if($_POST["accion"] == "cancelarTodo")
	{
		$retorno["error"] = Cancelar_Todas_Ordenes($_POST["id"]);
		echo json_encode($retorno);
		exit;
	}
}
/*
 * Si no se esta haciendo una peticion AJAX
 */
if(isset($_GET["id"]))
{
	/*
	 * Si solamente estamos revisando una lampara
	 */
	$lampara = $_GET["id"];
	$portal = Cargar_Plantilla("orden.html");
	$portal = str_replace("<!--cabezatabla-->", Cargar_Plantilla("cabezatablaorden.html"), $portal);
	$portal = str_replace("<!--contenido-->", Listar_Param_Act_Y_Ordenes($lampara), $portal);
	//ojo, el pie debe ir antes de reemplazar idLampara, pie incluye esa marca
	$portal = str_replace("<!--pie-->", Cargar_Plantilla("pieindividualorden.html"), $portal);
	$portal = str_replace("<!--idLampara-->", $lampara, $portal);
	
	echo $portal;
	exit;
}
if(isset($_GET["ids"]))
{
	/*
	 * Si han seleccionado varias lamparas
	 */
	$lamparas = $_GET["ids"];
	$portal = Cargar_Plantilla("orden.html");
	$portal = str_replace("<!--cabezatabla-->", Obtener_Parametros_Comun($lamparas), $portal);
	$portal = str_replace("<!--contenido-->", Crear_Matriz_Params_Lamps($lamparas), $portal);
	$portal = str_replace("<!--idLampara-->", "Multiordenes", $portal);
	echo $portal;
	exit;
}
/*
 * Error, no lamparas seleccionadas
 */
echo "No lamparas = no diversión";
		exit(0);
?>