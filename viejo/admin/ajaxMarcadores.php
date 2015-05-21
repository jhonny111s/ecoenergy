<?php
/*
 * Retorna json con las posiciones y nombres de marcadores que representan la ubicacion de lamparas en el mapa
 */
$raiz = "../";
include '../clases/funciones.php';
include '../clases/accionesbd.php';

Iniciar_Sesion();
// ajax = accion : "marcadores",
function Traer_Info_Actual()
{
	/*
	 * Trae todas las variables medibles de la lampara. las estandar
	 */
	$obj = new sql_query();
	$cta = "SELECT parametro, valor, id_lampara  ";
	$cta.= "FROM t_registro_actual";
	$result = $obj->consulta($cta);
	$obj->__destruct();
	return $result;
}
if(isset($_POST["accion"]))
{
	if ($_POST["accion"] == "marcadores")
	{
		$retorno = Traer_Info_Actual();
		$coord = array();
		$salida = array();
		$salida["error"] = "";
		if (sizeof($retorno) <= 0)	//no hay ordenes pendientes, no eliminaos...
		{
			$salida["error"] = "Ninguna lampara informacion asociada";
			echo json_encode($salida);
			exit;
		}
		/*
		 * Armando marcadores para API google maps
		 */
		foreach ($retorno as $fila) {
			if( !isset( $coord[$fila["id_lampara"]] ) )
			{
				$coord[$fila["id_lampara"]] = array();
				$boton = Cargar_Plantilla("btnorden.html");
				$boton = str_replace("--nombrelamp--", $fila["id_lampara"], $boton);
				$boton = str_replace("--texto--", "Configuraciones", $boton);
				$html = "<div><h3>".$fila["id_lampara"]."</h3>";
				$html.= "<p>$boton</p></div>";
				$coord[$fila["id_lampara"]]["titulo"] = $fila["id_lampara"];
				$coord[$fila["id_lampara"]]["contenido"] = $html;
				
				$coord[$fila["id_lampara"]]["icono"] = "images/lamp_inactive.png";
			}
			$coord[$fila["id_lampara"]][strtolower($fila["parametro"])] = floatval($fila["valor"]);
			//$coord[$fila["id_lampara"]]["lng"] = floatval($fila["lng"]);
			//valores medibles
			//$coord[$fila["id_lampara"]]["estado"] =  floatval($fila["estado"]);
			
		}

		//ahora armamos el json
		$salida["marcadores"] = array();
		foreach ($coord as $lampara) {
			$salida["marcadores"][] = $lampara;
		}
		echo json_encode($salida);
		exit;
	}
}
$salida = array();
$salida["error"] = "Ninguna accion declarada";
echo json_encode($salida);
exit;
?>