// Find a <table> element with id="myTable":
	var table = document.getElementById("myTable");
	$('#fecha').datetimepicker({ 
		format: 'DD/MM/YYYY'
	}); 

	$('#entrada').timepicker('setTime', '8:00 AM');
	$('#salida').timepicker('setTime', '5:00 PM');

	

	$( "#entrar" ).click(addHours);
	$(document).keypress(function(e) {
		if(e.which == 13) {
			addHours();
		}
	});

	function addHours() {
		var d = $('#fecha').val();
		if(d == "") {return;}
		var entr = $('#entrada').val();
		var sal = $('#salida').val();
		var postData = 
		{	
			"id" : $('#idPersona').text(),
			"dia" : d,
			"entrada" : entr,
			"salida" : sal
		}
		var dataString = JSON.stringify(postData);
		$.ajax({
			method: "POST",
			data: {action:dataString},
			url: '../../ajax/insertHours',
			success: function(data){
				var json = JSON.parse(data);
				console.log(json);
				$('#fecha').remove();
				$('#entrada').remove();
				$('#salida').remove();

				table.deleteRow(0);

			// Create an empty <tr> element and add it to the 1st position of the table:
			var row = table.insertRow(0);

			// Add some text to the new cells:
			row.insertCell(0).innerHTML = d;
			row.insertCell(1).innerHTML = entr;
			row.insertCell(2).innerHTML = sal;

			// Create an empty <tr> element and add it to the 1st position of the table:
			var row = table.insertRow(0);

			// Add some text to the new cells:
			row.insertCell(0).innerHTML = '<input class="form-control" style="margin-left: 0;" id="fecha">';
			row.insertCell(1).innerHTML = '<input class="form-control" style="margin-left: 0;" id="entrada">';
			row.insertCell(2).innerHTML = '<input class="form-control" style="margin-left: 0;" id="salida">';

			$('#fecha').datetimepicker({ 
				format: 'DD/MM/YYYY'
			}); 
			$('#entrada').timepicker('setTime', entr);
			$('#salida').timepicker('setTime', sal);
		},
		error: function(e){
			console.log('error: ' + e);
		}
	});

		
	};