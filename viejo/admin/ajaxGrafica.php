<?php
/*
 * Retorna json con os datos estadisticos de las lamparas
 */
$raiz = "../";
include '../clases/funciones.php';
include '../clases/accionesbd.php';

Iniciar_Sesion();
/*********************************************
 * FUNCIONES: espera peticion ajax para responder con lista de parametros y valores
 * 
 **********************************************/
function Datos_Y_Labels ($id) {
	/*
	 * Traer de la base de datos todos los parametros con su historial completo
	 */
	$obj = new sql_query();
	$lampara = $obj->Limpiar($id);
	//obtenemos los registros con hora y frecha por la lampara
	$cta = "SELECT fecha,hora,id FROM t_registro WHERE id_lampara = '".$lampara."' ORDER BY fecha,hora";
	$registros = $obj->consulta($cta);
	//obtenemos los parametros de todos los registros
	$arreglo = array();
	
	foreach ($registros as $r) {
		$cta = "SELECT parametro,valor FROM t_registro_param WHERE id = ".$r["id"];
		$datos = $obj->consulta($cta);
		foreach ($datos as $dato) {
			if( !isset($arreglo[$dato["parametro"]]) )
			{
				$arreglo[$dato["parametro"]] = array();
				$arreglo[$dato["parametro"]]["datos"] = array();
				$arreglo[$dato["parametro"]]["labels"] = array();
			}
			$arreglo[$dato["parametro"]]["datos"][] = $dato["valor"];
			$arreglo[$dato["parametro"]]["labels"][] = $r["fecha"]."(".$r["hora"].")";
		}
	}
	$obj->__destruct();
	return $arreglo;
}
if(isset($_POST["accion"]) & isset($_POST["id"]))
{
	if ($_POST["accion"] == "datos")
	{
		$salida = Datos_Y_Labels($_POST["id"]);
		$salida["error"] = "";
		echo json_encode($salida);
		exit;
	}
}
$salida = array();
$salida["error"] = "Ninguna accion declarada";
echo json_encode($salida);
exit;
?>