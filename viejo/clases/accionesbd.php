<?php
//include 'querys.php'; //clase para las conexiones con mysql
if(!isset($raiz))
$raiz="";
include $raiz.'clases/pgquerys.php'; //clase para las conexiones con postgres
//include $raiz.'clases/funciones.php';
include $raiz.'lang/lang.php';
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//esta calse contiene todas las funciones que son necesitadas por varios php, por eso esta aca, para uso//
//compartido																							//
//esta clase tambien selecciona entre las distintas librerias para conectarce a la base de datos		//
//las opciones son pgquerys para postgres y querys para mysql...										//
//////////////////////////////////////////////////////////////////////////////////////////////////////////
function Listar_Lamps()
{
	/*
	 * Se listan las lamparas que tiene el usuario a su disposicion, junto con informacion como estado, nombre y tipo.
	 */
	$obj = new sql_query();
	//consulta para listar las lamparas y obtener su estado mas reciente
	$cta = "";
	$cta.= "SELECT id_lampara,nombre,tipo FROM t_lampara";
	if (sizeof($_SESSION["filtrol"]) > 0)
	{
		$cta .= " WHERE ";
		foreach ($_SESSION["filtrol"] as $key => $value)
		{
			if ($value!="")
			$cta .= $key." ".$value." AND ";
		}
		$cta.=";";
		$cta = str_replace("AND ;", "", $cta);
		/*
			$result = $obj->consulta($cta);
			echo var_dump($result);
			exit;*/
	}
	$cta.= " ORDER BY nombre";
	$result = $obj->consulta($cta);

	$str = "";
	foreach ($result as $fila)
	{
		$str.= "<tr>";
		foreach ($fila as $val)
		{
			$str.= "<td>".$val."</td>";
		}/*
		$boton = Cargar_Plantilla("btnapagar.html");
		$boton = str_replace("--nombrelamp--", $fila["id_lampara"], $boton);
		$cta = "SELECT parametro FROM t_orden WHERE id_lampara = '".$fila["id_lampara"]."' AND pendiente = true";
		$ordenes = $obj->consulta($cta);
		if ( sizeof($ordenes) == 0)	//si no tiene ordenes pendentes...
		if ($fila["estado_actual"] > 1)
		$boton = str_replace("--estado--", APAGAR, $boton);
		else
		$boton = str_replace("--estado--", ENCENDER, $boton);
		else
		{//si tiene tareas pendientes
		$boton = ESPERANDO;
		}
		$str.= "<td>".$boton."</td>";
		*/
		$boton = Cargar_Plantilla("btnorden.html");
		$boton = str_replace("--nombrelamp--", $fila["id_lampara"], $boton);
		$boton = str_replace("--texto--", "Configuraciones", $boton);
		$str.= "<td>$boton</td>";
		$str.= "</tr>";
	}
	$obj->__destruct();
	return $str;
}
function Listar_Param_Act_Y_Ordenes($lampara){
	/*
	 * Lista las lamparas con las acciones y ordenes disponibles
	 * retorna una tabla ya preparada
	 */
	$obj = new sql_query();
	//consulta para listar las lamparas y obtener su estado mas reciente
	$lampara = $obj->Limpiar($lampara);
	$cta = "SELECT * FROM t_registro_actual WHERE id_lampara = '".$lampara."'";
	$registro = $obj->consulta($cta);
	$cta = "SELECT * FROM t_orden WHERE id_lampara = '".$lampara."' AND pendiente = true";
	$orden = $obj->consulta($cta);
	$str = "";
	$cont = 0;
	foreach ($registro as $fila)
	{
		if ($cont % 2 == 0)
		$str.= "<tr>";
		else
		$str.= "<tr class='alt'>";
		$str.= "<td>".$fila["parametro"]."</td>";
		$str.= "<td>".$fila["valor"]."</td>";
		if($fila["ordenable"])
		{
			$opcion = "<span></span><input type='text' id='".$fila["parametro"]."'>";
			$boton = "<button class='submit_btn' onclick=\"ordenar(this,'".$fila["parametro"]."','$lampara');\" value='enviar'>Enviar Orden</button>";
			foreach ($orden as $ord) {
				if ($fila["parametro"] == $ord["parametro"])
				{
					$opcion = "<span>Orden enviada: Cambiar a ".$ord["valor"]."</span><input type='text' id='".$fila["parametro"]."' style=\"display: none;\">";
					$boton = "<button class='submit_btn' onclick=\"ordenar(this,'".$fila["parametro"]."','$lampara');\" value='cancelar'>Cancelar Orden</button>";
				}
			}
			$str.= "<td>".$opcion."</td>";
			$str.= "<td>".$boton."<img id='cargando' src='../images/ajax-loader.gif' style='display:none;'/></td>";
		}
		else{
			$str.= "<td>Valor est&aacutetico</td>";
			$str.= "<td>Ninguna<img id='cargando' src='../images/ajax-loader.gif' style='display:none;'/></td>";
		}
		$str.= "</tr>";
		$cont++;
	}
	$obj->__destruct();
	return $str;
}
function Listar_Param_Act_Y_Ordenes_TR($lampara,$parametro){
	/*
	 * Retorna las lamparas con las ordenes y acciones posibles, sin embargo no retorna una tabla
	 * retorna un tr, el tr determinado de la tabla
	 */
	$obj = new sql_query();
	//consulta para listar las lamparas y obtener su estado mas reciente
	$lampara = $obj->Limpiar($lampara);
	$parametro = $obj->Limpiar($parametro);
	$cta = "SELECT * FROM t_registro_actual WHERE id_lampara = '".$lampara."' AND parametro = '".$parametro."'";
	$registro = $obj->consulta($cta);
	$str = "";
	$cont = 0;
	if (count($registro)<1)
	{
		$obj->__destruct();
		return "";
	}
	$fila = $registro[0];
	$str.= "<td>".$fila["parametro"]."</td>";
	$str.= "<td>".$fila["valor"]."</td>";
	if($fila["ordenable"])
	{
		$opcion = "<span></span><input type='text' id='".$fila["parametro"]."'>";
		$boton = "<button class='submit_btn' onclick=\"ordenar(this,'".$fila["parametro"]."','$lampara');\" value='enviar'>Enviar Orden</button>";
		$cta = "SELECT * FROM t_orden WHERE id_lampara = '".$lampara."' AND pendiente = true AND parametro = '".$parametro."'";;
		$orden = $obj->consulta($cta);
		$obj->__destruct();
		if (count($orden)>0)
		{
			$ord = $orden[0];
			$opcion = "<span>Orden enviada: Cambiar a ".$ord["valor"]."</span><input type='text' id='".$fila["parametro"]."' style=\"display: none;\">";
			$boton = "<button class='submit_btn' onclick=\"ordenar(this,'".$fila["parametro"]."','$lampara');\" value='cancelar'>Cancelar Orden</button>";
		}
		$str.= "<td>".$opcion."</td>";
		$str.= "<td>".$boton."<img id='cargando' src='../images/ajax-loader.gif' style='display:none;'/></td>";
	}
	else{
		$str.= "<td>Valor est&aacutetico</td>";
		$str.= "<td>Ninguna<img id='cargando' src='../images/ajax-loader.gif' style='display:none;'/></td>";
	}
	return $str;
}
function Selector_Lamparas()
{
	/*
	 * Listar las lamparas para el select que lista las lamparas disponibles para el filtro
	 */
	$obj = new sql_query();
	$cta = "SELECT id_lampara FROM t_lampara ORDER BY id_lampara";	//ordenamos los registros por mas nuevo
	$result = $obj->consulta($cta);
	$str = "";
	foreach ($result as $fila)
	{
		$str .= "<option value='".$fila["id_lampara"]."'>".$fila["id_lampara"]."</option>";
	}
	$obj->__destruct();
	return $str;
}
function Selector_Tipos()
{
	/*
	 * Listar las lamparas para el select que lista las lamparas disponibles para el filtro
	 */
	$obj = new sql_query();
	$cta = "SELECT DISTINCT ON (tipo) tipo FROM t_lampara ORDER BY tipo";	//ordenamos los registros por mas nuevo
	$result = $obj->consulta($cta);
	$str = "";
	foreach ($result as $fila)
	{
		$str .= "<option value='".$fila["tipo"]."'>".$fila["tipo"]."</option>";
	}
	$obj->__destruct();
	return $str;
}
function Listar_Registros($filtro="")
{
	/*
	 * Trae la informacion actual de los parametros de las lamparas
	 * FUNCION SIN ACTUALIZAR
	 */
	$obj = new sql_query();
	$cta = "SELECT id_lampara, parametro, valor FROM t_registro_actual";
	if ($_SESSION["filtro"]!="" )
	$cta.= " WHERE ".$_SESSION["filtro"];

	if ( $filtro != "")
	{
		if ($_SESSION["filtro"]!="" )
		{
			$_SESSION["filtro"] .= " AND ";
			$cta.= " AND ";
		}
		else
		$cta.= " WHERE ";
		switch ($filtro["columna"])
		{
			case "estado":
				$cta.= "estado ";
				$_SESSION["filtro"] .="estado ";
				break;
			case "consumo":
				$cta.= "consumo ";
				$_SESSION["filtro"] .="consumo ";
				break;
			case "temp_i":
				$cta.= "temp_interna ";
				$_SESSION["filtro"] .="temp_interna ";
				break;
			case "temp_e":
				$cta.= "temp_externa ";
				$_SESSION["filtro"] .="temp_externa ";
				break;
			case "humedad":
				$cta.= "humedad ";
				$_SESSION["filtro"] .="humedad ";
				break;
			case "brillo_i":
				$cta.= "brillo_interno ";
				$_SESSION["filtro"] .="brillo_interno ";
				break;
			case "brillo_e":
				$cta.= "brillo_externo ";
				$_SESSION["filtro"] .="brillo_externo ";
				break;
			default:
				return "";
				break;
		}
		switch ($filtro["comparador"])
		{
			case "mayor":
				$cta.="> ";
				$_SESSION["filtro"] .="> ";
				break;
			case "igual":
				$cta.="= ";
				$_SESSION["filtro"] .="= ";
				break;
			case "menor":
				$cta.="< ";
				$_SESSION["filtro"] .="< ";
				break;
			default:
				return "";
				break;
		}
		if ($filtro["valor"]=="")
		$filtro["valor"]= 0;
		$cta.= $obj->Limpiar($filtro["valor"]);
		$_SESSION["filtro"] .= $obj->Limpiar($filtro["valor"]);
	}
	$result = $obj->consulta($cta);
	$str = "";
	foreach ($result as $fila)
	{
		$str.="<tr>";
		$primero = 0;
		foreach ($fila as $val)
		{
			if ($primero == 0)
			$str.= "<th scope='row'>".$val."</th>";
			else
			$str.= "<td>".$val."</td>";
			$primero++;
		}
		$str.= "</tr>";
	}
	$obj->__destruct();
	return $str;
}
function Agregar_Orden($id,$parametro,$valor)
{
	/*
	 * Agrega en la base de datos una orden a cierta lampara
	 * $id hace referencia a la lampara, luego viene el parametro y el valor
	 */
	$obj = new sql_query();
	$id = $obj->Limpiar($id);
	$parametro = $obj->Limpiar($parametro);
	$valor = $obj->Limpiar($valor);
	$cta = "SELECT parametro FROM t_orden WHERE id_lampara = '".$id."' AND pendiente = true
			AND parametro = '".$parametro."'";
	$result = $obj->consulta($cta);
	if (count($result) > 0)	//si hay ordenes pendientes, no agregamos mas...
	{
		$obj->__destruct();
		return "Ya fue enviada una orden para ese parametro";
	}
	date_default_timezone_set("America/Bogota");
	$fecha = date("Y/M/d");		//fecha
	$hora = date("H:m:s");
	$cta = "INSERT INTO t_orden (id_lampara,parametro,valor,fecha_i,hora_i)
			VALUES ('".$id."','".$parametro."','".$valor."','".$fecha."','".$hora."')";
	$obj->consulta($cta);
	$obj->__destruct();
	return "";
}
function Eliminar_Orden($id,$parametro)
{
	/*
	 * Elimina la orden de la tabla de ordenes
	 */
	$obj = new sql_query();
	$id = $obj->Limpiar($id);
	$parametro = $obj->Limpiar($parametro);

	$cta = "SELECT parametro FROM t_orden WHERE id_lampara = '".$id."' AND pendiente = true
			AND parametro = '".$parametro."'";
	$result = $obj->consulta($cta);
	if (count($result) <= 0)	//no hay ordenes pendientes, no eliminaos...
	{
		$obj->__destruct();
		return false;
	}
	$cta = "DELETE FROM t_orden WHERE id_lampara = '".$id."' AND parametro = '".$parametro."'
			AND pendiente = true";
	$obj->consulta($cta);
	$obj->__destruct();
	return true;
}
function Traer_Coordenadas($lampara = null)
{
	/*
	 * DESACTUALIZADA, esta funcion ya no es usada
	 */
	$obj = new sql_query();
	$cta = "SELECT t1.id_lampara, t1.valor lat, t2.valor lng, t3.valor estado ";
	$cta.= "FROM t_registro_actual t1,t_registro_actual t2,t_registro_actual t3 ";
	$cta.= "WHERE t1.parametro = 'LAT' AND t2.parametro = 'LNG'	AND t3.parametro = 'ESTADO' AND t1.id_lampara = t2.id_lampara AND t3.id_lampara = t2.id_lampara";
	if ($lampara != null)
	{
		$lampara = $obj->Limpiar($lampara);
		$cta .= " AND id_lampara = '".$lampara."'";
	}
	$result = $obj->consulta($cta);
	$obj->__destruct();
	return $result;
}

function Cancelar_Todas_Ordenes($idLampara)
{	
	/*
	 * Como su nombre lo dice, cancela todas las ordenes de la lampara idLampara
	 */
	$obj = new sql_query();
	$id = $obj->Limpiar($idLampara);
	$cta = "DELETE FROM t_orden WHERE id_lampara = '".$id."' AND pendiente = true";
	$result = $obj->consulta($cta);
	$obj->__destruct();
	if(count($result)>0)
		return $result;
	else
		return "";
}

function Obtener_Parametros_Comun($lamparas)
{
	/*
	 * Interesante funcion que permite encontrar los parametros comunes entre todas las lamparas
	 * para luego mostrarlos en una tabla con todos los parametros encontrados y cada fila corresponde a cada lampara
	 */
	
	$obj = new sql_query();
	$matriz = array();
	foreach ($lamparas as $lamp) 
	{
		$id = $obj->Limpiar($lamp);
		$cta = "SELECT parametro,valor FROM t_registro_actual WHERE id_lampara = '".$id."'";
		$result = $obj->consulta($cta);
		foreach ($result as $fila) 
		{
			if ( !isset( $matriz[$fila["parametro"]] ) )
				$matriz[$fila["parametro"]] = array();
			$matriz[$fila["parametro"]][$id] = $fila["valor"];
		}
	}
	//guardamos lo que acabamos de crear para en un futuro no tener que volver a procesar
	$_SESSION["matriz"] = $matriz;
	//ahora retornamos el html de las cabezas
	$ret = "<th>ID</th>";
	foreach ($matriz as $key => $value) 
	{
		$ret .= "<th>$key</th>";
	}
	$obj->__destruct();
	return $ret;
}
function  Crear_Matriz_Params_Lamps($lamparas)
{
	/*
	 * Con esta funcion mostramos todos los parametros comunes encontrados usando la funcion
	 * Obtener_Parametros_Comun(), la cual guarda en sesion la matriz encontrada, acá se convierte en una tabla
	 */
	$ret = "";
	$matriz = $_SESSION["matriz"];
	$cont = 0;
	unset($_SESSION["matriz"]);
	foreach ($lamparas as $id) {
		if ($cont % 2 == 0)	$ret.= "<tr>";
		else $ret.= "<tr class='alt'>";
		$ret .= "<td>$id</td>";
		foreach ($matriz as $param => $noiporta) 
		{
			$ret .= "<td>";
			if ( isset( $matriz[$param][$id] ) )
				$ret.= $matriz[$param][$id];
			else 
				$ret.= Cargar_Plantilla_Imagen("imagen","icons/x.ico","No posee ese parametro");
			$ret .= "</td>";
		}
		$ret .= "</tr>";
		$cont++;
	}
	//echo var_dump($matriz);
	return $ret;
}
?>