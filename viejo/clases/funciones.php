<?php
	/*
	 * Archivo que contiene las funciones que son usadas por muchos otros archivos y NO tiene nada de funciones con bases de datos
	 * Inportante: en caso de modificar la direccion donde se aloja el programa, ir a la funcion Acomodar y modificar el texto por el que se reemplaza la palabra --root-- en todo el programa
	 */
	function Cargar_Plantilla($nombre)
	{			
		/*
		 * IMPORTANTE: cambiar esta funcion si se cambia el nombre de dominio desde donde se aloja esta pagina.
		 * En este momento se esta usando "http://".$_SERVER['SERVER_NAME']."/GreenLogy/"
		 */
		if (file_exists("../Vista/".$nombre))
			return str_replace("--root--", "http://".$_SERVER['SERVER_NAME']."/GreenLogy/", file_get_contents("../Vista/".$nombre));
		else
			if (file_exists("Vista/".$nombre))
				return str_replace("--root--", "http://".$_SERVER['SERVER_NAME']."/GreenLogy/", file_get_contents("Vista/".$nombre));
			else
				return "Plantilla no encontrada ".$nombre ;
	}
	function Cargar_Plantilla_Imagen($alt = "", $ruta, $title = "")
	{
		/*
		 * Funcion predeterminada que carga la plantilla imagen.html y modifica varios parametros dependiendo de como se llame esta funcion
		 */			
		$imagen = Cargar_Plantilla("imagen.html");
		$imagen = str_replace("--alt--", $alt, $imagen);
		$imagen = str_replace("--ruta--", $ruta, $imagen);
		$imagen = str_replace("--title--", $title, $imagen);
		return $imagen;
	}
	
	function Mensaje($texto)
	{
		/*
		 * FUNCION SIN USAR
		 */
		echo "<script type='text/javascript'>alert('".$texto."');</script>"; 
	}
	function Ir($link)
	{
		/*
		 * FUNCION SIN USAR
		 */
		echo "<script type='text/javascript'>document.location.href='$link';</script>";
	}
	function Redirec($url,$tiempo = -1)
	{
		/*
		 * FUNCION PARA REDIRECCIONAR AL NAVEGADOR
		 */
		if ($tiempo <= -1)
			header("Location:".$url);
		else
			header("refresh:$tiempo;url=".$url);
	}
	function Iniciar_Sesion()
	{
		/*
		 * Inicia la sesion de php, si no existe entonces redirecciona la pagina al inicio
		 */
	    session_start();
	    if(isset($_SESSION['autenticado']) && $_SESSION['autenticado'] == true)
	    	return true;
	    else
	    {
	    	session_destroy();
	    	Redirec('index.php');
	    	exit();
	    }
	    return false;
	}     
	
	function Fecha() 
	{
		return date("d-m-Y");//H:i:s
	}
	function Volver($n=1) 
	{
		/*
		 * NO USADA
		 */
		//simula presionar el boton atras del navegador
		echo "<script type=\"text/javascript\">history.go(-$n);</script>";
	}
	function Obtener_Tipo($archivo) 
	{
		/*
		 * NO USADA
		 */
		$partes = explode(".", $archivo);
		$tipo = $partes[ count($partes) - 1];
		return $tipo;
	}
	function es_Imagen($archivo) 
	{
		/*
		 * NO USADA
		 */
		return preg_match("/^jpe?g+$/i", Obtener_Tipo($archivo)) || preg_match("/^gif+$/i", Obtener_Tipo($archivo)) || preg_match("/^png+$/i", Obtener_Tipo($archivo)) ;
	}
	function Es_Fecha($fecha) 
	{
		/*
		 * NO USADA
		 */
		$fecha = str_replace("-", "/", $fecha);
		$partes = explode("/", $fecha);
		if(count($partes)!=3)
			return false;
		if(!is_numeric($partes[0]) || !is_numeric($partes[1]) || !is_numeric($partes[2]) )
			return false;	
		return true;
	}
	function Sin_Tildes($texto) 
	{
		/*
		 * NO USADA
		 */
		$tildes = array("á","é","í","ó","ú","ñ"," ","Á","É","Í","Ó","Ú","Ñ");
		$reempl = array("a","e","i","o","u","n","-","a","e","i","o","u","n");
		$texto = strtolower($texto);
		$texto = str_replace($tildes,$reempl,$texto);
		return $texto;
	}
?>