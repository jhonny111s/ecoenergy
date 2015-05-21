/**
 * Ths window.onload function initiates the AJAX request. The AJAX page is:
 * http://www.rgraph.net/getdata.html?json If you view this in your browser
 * you'll see that all it does is output a JSON object (a JavaScript object).
 */
$(document).ready(function() {

	cargar();
	$(document).everyTime("5s", function() {
		$("#mensajes").html("Cargando...");
		cargar();
		var fecha = new Date();
		$("#mensajes").html("Cargado "+fecha);
	});

});
/**
 * This is the AJAX callback function. It splits up the response, converts it to
 * numbers and then creates the chart.
 */
function dibujar(canvas, data, labels, parametro) {
	// Set the JSON on the window object so that the button below can show it to
	// the user.
	// window.__json__ = json;
	// Now draw the chart
	var datos = [];
	var etiqs = [];
	var max = 0;
	if (data.length > 20) {
		for ( var i = data.length - 10; i < data.length; i++) {
			datos.push(parseFloat(data[i]));
			etiqs.push(labels[i]);
			if (max < parseFloat(data[i])) {
				max = parseFloat(data[i]);
			}
		}
	} else {
		etiqs = labels;
		for ( var i = 0; i < data.length; i++) {
			datos.push(parseFloat(data[i]));
			if (max < parseFloat(data[i])) {
				max = parseFloat(data[i]);
			}
		}

	}
	RGraph.Reset(canvas);
	var line = new RGraph.Line(canvas, datos)
			//.Set('hmargin', 2)
			//.Set('ymax', max)
			.Set('labels', etiqs)
			.Set('tooltips', etiqs)
			.Set('linewidth', 10)
			.Set('chart.title', parametro)
			.Set('chart.text.angle', 30)
			.Set('chart.gutter.bottom', 150)
			.Set('chart.gutter.left', 100)
			.Set('chart.fillstyle', 'rgba(255,0,0,0.3)')
			.Set('chart.colors', 'red')
			.Set('chart.filled', true)
			.Set('chart.filled.accumulative', false)
			// .Draw();
	RGraph.Effects.Line.jQuery.Trace(line);
	/*
    window.onload = function ()
    {
        line = new RGraph.Line('cvs', [4,3,6,7,8,4,9,5,1,3,4,3], [4,5,1,6,2,3,4,5,8,1,9,7]);
        line.Set('chart.labels', ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']);
        line.Set('chart.linewidth', 5);
        line.Set('chart.fillstyle', ['rgba(255,0,0,0.3)', 'rgba(0,0,255,0.3)']);
        line.Set('chart.colors', ['red', 'blue']);
        line.Set('chart.filled', true);
        line.Set('chart.filled.accumulative', true);
        line.Set('chart.title', 'A filled line with chart.filled.accumulative=true');
        line.Set('chart.gutter.right', 15);
        line.Set('chart.gutter.bottom', 35);
        line.Set('chart.background.grid.autofit', true);
        line.Draw();

        line2 = new RGraph.Line('cvs2', [4,3,6,7,8,4,9,5,1,3,4,3], [4,5,1,6,2,3,4,5,8,1,9,7]);
        line2.Set('chart.labels', ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']);
        line2.Set('chart.linewidth', 5);
        line2.Set('chart.fillstyle', ['rgba(255,0,0,0.3)', 'rgba(0,0,255,0.3)']);
        line2.Set('chart.colors', ['red', 'blue']);
        line2.Set('chart.filled', true);
        line2.Set('chart.filled.accumulative', false);
        line2.Set('chart.title', 'A filled line with chart.filled.accumulative=false');
        line2.Set('chart.gutter.right', 15);
        line2.Set('chart.gutter.bottom', 35);
        line2.Set('chart.background.grid.autofit', true);
        line2.Draw();
	*/
}
/*******************************************************************************
 * Seccion de consultas ajax
 ******************************************************************************/
function cargar() {
	var id = $("#id").html();
	
	$.post("ajaxGrafica.php", {
		accion : "datos",
		id : id
	}, function(data) {
		try {
			var resultado = jQuery.parseJSON(data);
		} catch (e) {
			$("#mensajes").html(data);
			console.log("error al parsear json");
			console.log(data);
			return;
		}
		if (resultado.error === "") {
			// aqui va lo correcto
			var canvas = "cvs";
			var cont = 1;
			for (x in resultado) {
				if (x != "error") {
					
					if( document.getElementById(canvas + "" + cont) == null ) {
						var htmla = '<canvas id="cvs'+cont+'" width="900" height="400">[No soporta canvas]</canvas>';
						$("#canvas").append(htmla);
						}
					
					dibujar(canvas + "" + cont, resultado[x].datos,
							resultado[x].labels, x);
					cont++;
				}

			}
		} else {
			// aqui va cuando existe error
			$("#mensajes").html(data);
			console.log(data);
		}
	});
}