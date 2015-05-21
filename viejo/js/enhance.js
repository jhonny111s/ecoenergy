/**
 * 
 */
// Corre el script cuando el DOM se haya terminado de cargar:
enhance({
		loadScripts: [
			{src: 'js/excanvas.js', iecondition: 'all'},
			'https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js',
			'js/visualize.jQuery.js'
			//,'js/js.js'
			],
			loadStyles: [
				'Vista/visualize.css',
				'Vista/visualize-dark.css'
			]	
		});  