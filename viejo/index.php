<?php
echo "Hola";
exit;
	include 'clases/funciones.php';
	session_start();
	$portal = Cargar_Plantilla("index.html");
	if (isset($_SESSION["autenticado"]) && isset($_SESSION["autenticado"])==true)
		$portal = str_replace("<!--form_inicio-->", "<strong>Ya iniciaste sesion ".$_SESSION["nombre"]." Puedes <a href='php/salir.php'>Salir</a> o <a href='usuarios.php'>Volver</a>.</strong>", $portal);
	else
		$portal = str_replace("<!--form_inicio-->", Cargar_Plantilla("forminiciosesion.html"), $portal);
	echo $portal;
?>
