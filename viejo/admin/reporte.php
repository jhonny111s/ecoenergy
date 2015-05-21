<?php
/*
 * Muestra un menu de graficas en tiempo real.
 */
$raiz = "../";
include '../clases/funciones.php';
include '../clases/accionesbd.php';

Iniciar_Sesion();
if(isset($_POST["accion"]) & isset($_POST["id"]))
{
	/*
	 * Para acciones por AJAX
	 */
	if ($_POST["accion"] == "datos" & isset($_POST["parametro"]))
	{
		/*
		 * Consultamos el parametro necesitado y retornamos el json
		 * Soo se pide el data y labels, nada mas....
		 */
	}
	if ($_POST["accion"] == "")
	{
	}
	if ($_POST["accion"] == "tr")
	{
	}
	if($_POST["accion"] == "cancelarTodo")
	{
	}
}
if(isset($_GET["id"]))
{
	/*
	 * Muestra informacion de la lampara seleccionada
	 */
	$lampara = $_GET["id"];
	$portal = Cargar_Plantilla("reporte.html");
	$portal = str_replace("<!--idLampara-->", $lampara, $portal);
	//ojo, el pie debe ir antes de reemplazar idLampara, pie incluye esa marca	
	echo $portal;
	exit;
}
echo "No lamparas = no diversión";
		exit(0);
?>