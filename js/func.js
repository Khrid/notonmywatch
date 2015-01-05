$(function() {

});

function addSerie(id, name) {
	console.log(id+' -> '+name);
	var rows = ['addSerie',id,name];
	$.post( 
		'inc/ajax.php',   
		{values: rows},
		function(data, statusText) {
			console.log(statusText);
			switch(statusText) {
				case 'success':
					window.location.replace("index.php");
					break;
			}
		}
	);
}

function addFilm(id, name) {
	console.log(id+' -> '+name);
	var rows = ['addFilm',id,name];
	$.post( 
		'inc/ajax.php',   
		{values: rows},
		function(data, statusText) {
			console.log(statusText);
			switch(statusText) {
				case 'success':
					window.location.replace("index.php");
					break;
			}
		}
	);
}