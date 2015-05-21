/**
 * JAVASCRIPT unico para ordenes.php
 */
var cargando = 0;
$(document).ready(function() {

	/*
	 * Apenas termina de cargar la pagina, se ejecuta todo lo siguiente
	 */
	$(document).everyTime("10s", function() {
		if (cargando == 0)
			verificarOrdenes();
	});

});
function verificarOrdenes() {
	/*
	 * recorre todas las filas de la tabla y revisa modificaiones de parametros
	 * actualiza si es necesario
	 */
	if (cargando > 0)
		return;
	$("tbody tr").each(function(index) {
		cargando++;
		// console.log("Cargando:" + cargando);
		var este = this;
		cosa = setTimeout(function() {
			verificarOrdenesEach(este)
		}, 1000 * cargando);
	});
	console.log("Actualizacion requerida");
}
function verificarOrdenesEach(este) {
	/*
	 * funcion individual que revisa si el parametro buscado tiene modificaiones
	 * de valor, "este" es un <TR>
	 */
	// primero verificar que no removamos el input que estan editando
	if ($(document.activeElement).is("input[type='text']")) {
		if ($(este).find("input[type='text']").attr("id") == $(
				document.activeElement).attr("id")) {
			console.log("No se modificara por focus");
			cargando--;
			return;
		}
	}
	// tampoco modificar los input que hayn sido modificados
	if ($(este).find("input[type='text']").val() != "") {
		if ($(este).find("input[type='text']").val() != undefined) {
			cargando--;

			console.log("Encontro que el texto esta modificado :"
					+ $(este).find("input[type='text']").val());
			return;
		}
	}
	$(este).children("td").last().children("#cargando").show();
	$(este).children("td").last().children("button").hide();
	/*
	 * Funcion ajax que permite la magia
	 */
	var parametro = $(este).children("td").first().html();
	var id = $("#id").html();
	$.post("orden.php", {
		accion : "tr",
		id : id,
		parametro : parametro
	}, function(data) {
		try {
			var resultado = jQuery.parseJSON(data);
		} catch (e) {
			$("#mensajes").html(data);
		}
		if (resultado.error === "") {
			if (resultado.tr !== "" & resultado.tr != null) {
				$(este).hide("slow", function() {
					$(este).html(resultado.tr);
					$(este).show("slow");
					console.log(parametro + ":Termino = '" + resultado + "'");
				});
				/*
				 * $(este).hide(); $(este).html(resultado.tr); $(este).show();
				 */
			} else {
				$(este).children("td").last().children("#cargando").hide();
				$(este).children("td").last().children("button").show();
				console.log(parametro + ":Termino error = '" + data + "'");
			}
		} else {
			$("#mensajes").html(data);
			console.log(data);
		}
		cargando--;
	});
}
function ordenar(este, parametro, id) {
	/*
	 * enviar una orden via ajax
	 * "este" es un boton
	 * "parametro" es un objeto html tipo input text
	 * "id" es el identificador unico de la lamapra
	 */
	var accion = "";
	var valor = "";
	if ($(este).val() === "enviar") {
		accion = "enviar";
		valor = $("#" + parametro).val();
		if ($.trim(valor) === "")
			return;
	} else if ($(este).val() === "cancelar") {
		accion = "cancelar";
	} else {
		alert("Funcion boton incorrecta, recarge la pagina");
		return;
	}
	var texto = $(este).text();
	$(este).html('<img src="../images/ajax-loader.gif" alt="cargando" />');
	$.post("orden.php", {
		accion : accion,
		id : id,
		parametro : parametro,
		valor : valor
	}, function(data) {
		var resultado = jQuery.parseJSON(data);
		if (resultado.error === "") {
			if (accion === "enviar") {

				$("#" + parametro).parent().children("span").html(
						"Orden enviada: Cambiar a " + valor);
				$(este).html("Cancelar Orden");
				$(este).val("cancelar");
				$("#" + parametro).hide();
			} else {

				$("#" + parametro).parent().children("span").html("");
				$(este).html("Enviar Orden");
				$(este).val("enviar");
				$("#" + parametro).show();
			}
		} else {
			// $("#mensajes").html(data);
			alert(resultado.error);
			$(este).html(texto);
			cargando++;
			verificarOrdenesEach($(este).parents("tr"));
		}
	});
}
function enviaTodo(este, idLampara) {
	/*
	 * Funcion que simula hacer clic en enviar ordenes de todos los parametros, envia simultaneamente todas las ordenes
	 */
	var mihtml = $(este).html();
	$(este).html("Cargando...");
	$(este).attr("disabled", "disabled");
	$("td button").each(function(index) {
		$("td button")[index].onclick();
	});
	$(este).removeAttr("disabled");
	$(este).html(mihtml);
}
function cancelaTodo(este, idLampara) {
	/*
	 * Funcion que simula hacer clic en cancelr ordenes de todos los parametros, cancela simultaneamente todas las ordenes
	 */
	var mihtml = $(este).html();
	$(este).html("Cargando...");
	$(este).attr("disabled", "disabled");

	$.post("orden.php", {
		accion : "cancelarTodo",
		id : idLampara
	}, function(data) {
		var resultado = jQuery.parseJSON(data);
		if (resultado.error === "") {
			location.reload(true);
		} else {
			// $("#mensajes").html(data);
			alert(data);
		}
	});

	$(este).removeAttr("disabled");
	$(este).html(mihtml);
}
/*
 * Funciones de rpeuba
 */
function alerta(msg, titulo, funcion) {
	/*
	 * Funcion para reemplazar el dialog de jquery, en proceso
	 */
	if (!titulo)
		titulo = 'Alert';

	if (!msg)
		msg = 'No Mensaje';

	$("<div></div>").html(msg).dialog({
		title : titulo,
		resizable : false,
		modal : true,
		buttons : {
			"Ok" : function() {
				if (funcion == null)
					$(this).dialog("close");
				else
					funcion;
			}
		}
	});
}

function prueba() {
	alerta("PRUEBA");
}