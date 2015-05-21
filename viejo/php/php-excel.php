<?php
	include '../clases/funciones.php';
	include '../clases/accionesbd.php';
	Iniciar_Sesion();
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=reporte_lamparas.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	$pagina = Cargar_Plantilla("tablavacia.html");
	$pagina = str_replace("<!--tabla_registro-->", Listar_Registros(), $pagina);
	echo $pagina;
?>