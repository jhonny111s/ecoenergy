/*******************************************************************************
 * GLOBALES
 * JAVASCRIPT general del panel de control
 ******************************************************************************/
var marcadores = [];
var map = null;
var infowindow = null;
var oTable = null;

/*******************************************************************************
 * FUNCIONES DATATABLES
 ******************************************************************************/
function fnCreateSelect(aData)
{
	var r = '<select><option value=""></option>', i, iLen = aData.length;
	for (i = 0; i < iLen; i++)
	{
		r += '<option value="' + aData[i] + '">' + aData[i] + '</option>';
	}
	return r + '</select>';
}

function fnCreateSelectMultiSelect(aData)
{
	var r = '<select multiple>', i, iLen = aData.length;
	for (i = 0; i < iLen; i++)
	{
		r += '<option selected value="' + aData[i] + '">' + aData[i] + '</option>';
	}
	return r + '</select>';
}
$(document).ready(function() {
    	oTable = $('#tabla_lamparas').dataTable({
    		"aoColumns": [
          /* identificador */ null,
          /* Nombre */ null,
          /* Tipo */   null,
          /* Acciones */   { "bSearchable": false, "bSortable": false }
    		      ],
		// "iDisplayLength": 50,
		"bPaginate": true,
		// "sDom": 'Tlfrtip',
		"oLanguage": {
			"oAria": {
				"sSortAscending": " - Clic/Enter para ordenar de forma ascendente",
				"sSortDescending": " - Clic/Enter para ordenar de forma descendente"
			},
			"oPaginate": {
				"sFirst": " Primera página ",
				"sLast": " Última página ",
				"sNext": " Siguiente página ",
				"sPrevious": " Página anterior "
			},
			"sEmptyTable": "No hay datos disponibles en la tabla",
			"sInfo": "Hay un total de _TOTAL_ entradas para mostrar (_START_ a _END_)",
			"sInfoEmpty": "No hay entradas para mostrar.",
			"sInfoFiltered": " - filtrando entre _MAX_ registros.",
			"sInfoPostFix": " Todos los registros derivan de información real.",
			"sInfoThousands": "",
			"sLengthMenu": "Mostrar _MENU_ registros",
			"sLoadingRecords": "Cargando - Por favor espere...",
			"sProcessing": "El plugin de tablas está ocupado",
			"sSearch": "Filtrar por:",
			"sZeroRecords": "No hay registros para mostrar"
		}
	});
	$("thead th.filtrable").each(function(i) {
		this.innerHTML += fnCreateSelectMultiSelect(oTable.fnGetColumnData(i));
		$('select', this).multiselect({
			label: "Filtrado",
			placeholder: "Filtros",
			checkAllText: 'Marca todas',
			uncheckAllText: 'Desmarque todas',
			noneSelectedText: 'Filtrar',
			selectedText: 'Filtrado'
		});
		$('select', this).change(function() {
			var $filtros = $(this).val();
			if ($filtros !== null) {
				var cadFiltrado = '^' + $filtros.join('$|^') + '$';
				oTable.fnFilter(cadFiltrado, i, true, false);
			} else {
				oTable.fnFilter('', i);
			}

		});
		$('button.ui-multiselect').attr('width', '200px').css('width', '200px');
	});	
	/***************************************************************************
	 * REPETICION ACTUALIZADOR MARCADORES EN MAPA
	 **************************************************************************/

	$(document).everyTime("15s", function() {
		actualizaMarcadores();
		 });
	/***************************************************************************
	 * GOOGLE MAPS
	 **************************************************************************/
	google.maps.event.addDomListener(window, 'load', initialize);
	/***************************************************************************
	 * LISTENER CUANDO SE CAMBIA LOS ICONOS QUE SE QUIEREN VER
	 ***************************************************************************/
	$( "#iconos" ).change(function() {
		actualizarIconos();			
	});
	$( "#limite" ).change(function() {
		actualizarIconos();			
	});
	
});
function actualizarIconos()
{
	var icono = "";
	// Math.round(Math.random()*4)
	var caso = parseFloat($( "#iconos" ).val() ); 
	var limite = parseFloat( $( "#limite" ).val() );
	if(isNaN( limite ))
		{
		$("#limite" ).val("0");
		limite = 0;
		}
	else
		$("#limite" ).val(limite);
	
	switch( caso ){
	case 1:
		icono = 'icons/mapa/lamp';
		for (var i = 0, j = marcadores.length; i < j; i++) {
			if(marcadores[i].estado != undefined | marcadores[i].estado != null)
			if(marcadores[i].estado < limite )
				marcadores[i].marca.setIcon(icono+"1.png");
			else
				marcadores[i].marca.setIcon(icono+"3.png");
		}
		break;
	case 2:
		icono = 'icons/mapa/temp';
		for (var i = 0, j = marcadores.length; i < j; i++) {
			if(marcadores[i].temperatura != undefined | marcadores[i].temperatura != null)
			if(marcadores[i].temperatura < limite )
				marcadores[i].marca.setIcon(icono+"1.png");
			else
				marcadores[i].marca.setIcon(icono+"3.png");
		}
		break;
	case 3:
		icono = 'icons/mapa/gota';
		for (var i = 0, j = marcadores.length; i < j; i++) {
			if(marcadores[i].humedad != undefined | marcadores[i].humedad != null)
			if(marcadores[i].humedad < limite )
				marcadores[i].marca.setIcon(icono+"1.png");
			else
				marcadores[i].marca.setIcon(icono+"3.png");
		}
		break;
	case 4:
		icono =	 'icons/mapa/conta';
		for (var i = 0, j = marcadores.length; i < j; i++) {
			if(marcadores[i].monoxgas != undefined | marcadores[i].monoxgas != null)
			if(marcadores[i].monoxgas < limite )
				marcadores[i].marca.setIcon(icono+"1.png");
			else
				marcadores[i].marca.setIcon(icono+"3.png");
		}
		break;
	case 5: 
		icono = 'icons/mapa/luz';
		for (var i = 0, j = marcadores.length; i < j; i++) {
			if(marcadores[i].luz != undefined | marcadores[i].luz != null)
			if(marcadores[i].luz < limite )
				marcadores[i].marca.setIcon(icono+"1.png");
			else
				marcadores[i].marca.setIcon(icono+"3.png");
		}
		break;
	case 6: 
		icono = 'icons/mapa/sonido';
		for (var i = 0, j = marcadores.length; i < j; i++) {
			if(marcadores[i].sonido != undefined | marcadores[i].sonido != null)
			if(marcadores[i].sonido < limite )
				marcadores[i].marca.setIcon(icono+"1.png");
			else
				marcadores[i].marca.setIcon(icono+"3.png");
		}
		break;
	case 7: 
		icono = 'icons/mapa/radar';
		for (var i = 0, j = marcadores.length; i < j; i++) {
			if(marcadores[i].presencia != undefined | marcadores[i].presencia != null)
			if(marcadores[i].presencia < limite )
				marcadores[i].marca.setIcon(icono+"1.png");
			else
				marcadores[i].marca.setIcon(icono+"3.png");
		}
		break;
	case 8: 
		icono = 'icons/mapa/energia';
		for (var i = 0, j = marcadores.length; i < j; i++) {
			if(marcadores[i].energia != undefined | marcadores[i].energia != null)
			if(marcadores[i].energia < limite )
				marcadores[i].marca.setIcon(icono+"1.png");
			else
				marcadores[i].marca.setIcon(icono+"3.png");
		}
		break;
	default:
		icono = 'images/lamp_active.png';
	break;
	}
}
(function($) {
	/***************************************************************************
	 * DATATABLES
	 **************************************************************************/
	$.fn.dataTableExt.oApi.fnGetColumnData = function(oSettings, iColumn, bUnique, bFiltered, bIgnoreEmpty) {
		// check that we have a column id
		if (typeof iColumn == "undefined")
			return new Array();

		// by default we only wany unique data
		if (typeof bUnique == "undefined")
			bUnique = true;

		// by default we do want to only look at filtered data
		if (typeof bFiltered == "undefined")
			bFiltered = true;

		// by default we do not wany to include empty values
		if (typeof bIgnoreEmpty == "undefined")
			bIgnoreEmpty = true;

		// list of rows which we're going to loop through
		var aiRows;

		// use only filtered rows
		if (bFiltered == true)
			aiRows = oSettings.aiDisplay;
		// use all rows
		else
			aiRows = oSettings.aiDisplayMaster; // all row numbers

		// set up data array
		var asResultData = new Array();

		for (var i = 0, c = aiRows.length; i < c; i++) {
			iRow = aiRows[i];
			var aData = this.fnGetData(iRow);
			var sValue = aData[iColumn];

			// ignore empty values?
			if (bIgnoreEmpty == true && sValue.length == 0)
				continue;

			// ignore unique values?
			else if (bUnique == true && jQuery.inArray(sValue, asResultData) > -1)
				continue;

			// else push the value onto the result data array
			else
				asResultData.push(sValue);
		}

		return asResultData;
	}
}(jQuery));
/*******************************************************************************
 * Funcion inicializadora de GOOGLE MAPS y otras funciones
 ******************************************************************************/
function initialize()
{
	var pereira = new google.maps.LatLng(4.81333,-75.69611);
	var opciones = {
	    zoom : 14,
	    center: pereira,
	    // mapTypeId: google.maps.MapTypeId.ROADMAP
	    mapTypeId: google.maps.MapTypeId.SATELLITE 
	};
	var div = document.getElementById('mapa');
	map = new google.maps.Map(div, opciones);
	infowindow = new google.maps.InfoWindow({
	    content: ''
	});
	/*
	 * Carga marcadores
	 */
	$.post("admin/ajaxMarcadores.php", {
		accion : "marcadores",
	}, function(data) {
		var resultado = []
		try{
			resultado = jQuery.parseJSON(data);
		}catch (e) {
			$("#mensajes").html("Error al convertir JSON<br>");
			$("#mensajes").append(data);
			console.log(data);
			return;
		}
		$("#mensajes").html(resultado.error);
		if (resultado.error === "")
		{
			// console.log(data);
			$("#mensajes").html("Cargando marcadores"+data);
			marcadores = resultado.marcadores;
			mostrarMarcadores(0);
			$("#mensajes").html("Marcadores cargados "+marcadores.length);
		}
		else
			$("#mensajes").html(resultado.error);
	});
	/*
	 * Pendientes cuando un marcador se mueva
	 */
	 
}
function mostrarMarcadores(inicio)
{
	//Muestra los marcadores agregados a google maps
	for (var i = inicio, j = marcadores.length; i < j; i++) {
	    var contenido = marcadores[i].contenido;
	     
	    var icono = marcadores[i].icono;
	    var marker = new google.maps.Marker({
	    	position: new google.maps.LatLng(marcadores[i].lat, marcadores[i].lng),
		    map: map,
		    // animation: google.maps.Animation.BOUNCE,
		    icon:icono,
		    title:marcadores[i].titulo,
		    draggable : true,
		    animation: google.maps.Animation.DROP,
		});
	    marcadores[i].marca = marker;
	    (function(marker, contenido){                       
	    	google.maps.event.addListener(marker, 'click', function() {
	    		infowindow.setContent(contenido);
	    		infowindow.open(map, marker);
	    		marker.setAnimation(null);
	    	});
	    })(marker,contenido);
	    (function(marker){                       
	    	google.maps.event.addListener(marker, 'dragend', function() {
		        var lat = marker.position.lat();
		        var lng = marker.position.lng();
		        console.log(lat+" "+lng);
		    });
	    })(marker);

	}	
	actualizarIconos();
}
function actualizaMarcadores()
{
	//Actualliza la informacion de los marcadores agregados a google maps
	$("#mensajes").html("Cargando Marcadores");
	$.post("admin/ajaxMarcadores.php", {
		accion : "marcadores",
	}, function(data) {
		var resultado = []
		try{
			resultado = jQuery.parseJSON(data);
		}catch (e) {
			$("#mensajes").html("Error al convertir JSON");
			return;
		}
		if (resultado.error === "")
		{
			var inicio =  marcadores.length;
			$("#mensajes").html("Cargando marcadores"+data);
			if(marcadores.length == 0)
				marcadores = resultado.marcadores;
			else
			{
				for (var i = 0, j = resultado.marcadores.length; i < j; i++){
					var esta = false;
					for (m in marcadores) {
						if (marcadores[m].titulo === resultado.marcadores[i].titulo)
						{
							esta = true;
							if (marcadores[m].estado != resultado.marcadores[i].estado)
							{
								if (marcadores[m].marca.getAnimation() == null)
									marcadores[m].marca.setAnimation(google.maps.Animation.BOUNCE);
								marcadores[m].marca.setIcon(resultado.marcadores[i].icono);
								marcadores[m].estado = resultado.marcadores[i].estado;
								marcadores[m].tiempo = 0;
							}
							else
							{
								if (marcadores[m].marca.getAnimation() != null)
								{	marcadores[m].tiempo++;
									if (marcadores[m].tiempo > 5)
									{	
										marcadores[m].marca.setAnimation(null);
										marcadores[m].tiempo = 0;
									}
								}
							}
							break;
						}
				    }
					if ( !esta )	
						marcadores.push(resultado.marcadores[i]);
				}
			}
			if (resultado.marcadores.length > 0)
				mostrarMarcadores(inicio);
			var fecha = new Date();
			$("#mensajes").html( (marcadores.length-inicio) +" Marcadores nuevos" + fecha);
		}
		else
			$("#mensajes").html(resultado.error);
	});
}
function cancelaAnimacion(marca)
{
	marca.setAnimation(null);
}
/*******************************************************************************
 * ABRIR VENTANA ORDENES INDIVIDUALES
 ******************************************************************************/
function orden(obj, idLamp) {
// alert(idLamp);
	window.open("admin/orden.php?id="+idLamp,'','width=600,height=400,toolbar=no,location=no,left=200,top=200');
};
/*******************************************************************************
 * ABRIR VENTANA REPORTES INDIVIDUALES
 ******************************************************************************/
function reporte(obj, idLamp) {
// alert(idLamp);
	window.open("admin/reporte.php?id="+idLamp,'','width=1000,height=400,toolbar=no,location=no,left=200,top=200');
};
/*******************************************************************************
 * ABRIR VENTANA CONFIGURACION GRUPAL
 ******************************************************************************/
function configurar()
{
	alert ("en proceso...");
	return;
	var rows = oTable._('tr', {"filter": "applied"});// oTable.fnGetNodes();
	var total =  rows.length;
	var ids= [];
	for (m in marcadores) {
		marcadores[m].marca.map = null;
    }
	for (var i = 0; i < rows.length; i++)
	{
		// cells.push($(rows[i]).find("td:eq(8)" ).html());
		var texto = rows[i];
		ids.push( texto[0] );
		for (m in marcadores) {
			if (marcadores[m].titulo === texto[0])
			{
				marcadores[m].marca.map = map;
				break;
			}
	    }
		
	}
	// alert( ids );
	window.open("admin/orden.php?id="+ids,'','width=600,height=400,toolbar=no,location=no,left=200,top=200');
}
function ordenes()
{
	// alert ("en proceso...");
	// return;
	var rows = oTable._('tr', {"filter": "applied"});// oTable.fnGetNodes();
	var txtget = "";
	var ids= [];
	var ancho = 600;
	for (var i = 0; i < rows.length; i++)
	{
		// cells.push($(rows[i]).find("td:eq(8)" ).html());
		var texto = rows[i];
		txtget += "ids[]="+texto[0]+"&";
		ancho = ancho + 50;
	}
	txtget += "tipo=orden";
	// alert( ids );
	window.open("admin/orden.php?"+txtget,'','width='+ancho+',height=400,toolbar=no,location=no,left=200,top=200');
}
function visualizar()
{
	var rows = oTable._('tr', {"filter": "applied"});// oTable.fnGetNodes();
	var total =  rows.length;
	for (var i = 0, j = marcadores.length; i < j; i++) {
		marcadores[i].marca.setMap(null);
    }
	for (var i = 0; i < rows.length; i++)
	{
		// cells.push($(rows[i]).find("td:eq(8)" ).html());
		var texto = rows[i];
		console.log(texto[0]+"=");
		for (var m = 0, n = marcadores.length; m < n; m++) {
			console.log(marcadores[m].titulo);
			if (marcadores[m].titulo === texto[0])
			{
				marcadores[m].marca.setMap(map);
				break;
			}
	    }
	}	
}