var counter = 1;
var persons = [];

$( "#printButton" ).click(function() {
	printPage();
});

$('#searchButton').click(function(){
	searchPeople();
});

$( "#busqueda" ).keypress(function( event ) {
	if ( event.which == 13 ) {
		searchPeople(); 
	}

});



function searchPeople() {
	if(document.getElementById('busqueda').value == "") return;
	document.getElementById('personasAdd').innerHTML = "";
	var postData = 
	{
		"searchstring" : document.getElementById('busqueda').value
	}
	var dataString = JSON.stringify(postData);
	$.ajax({
		method: "POST",
		data: {action:dataString},
		url: '../ajax/getVoluntarios',
		success: function(data){
			var json = JSON.parse(data);
			persons = [];
			var newDiv = $(document.createElement('div'))
			.attr("id", 'TextBoxDiv');

			newDiv.after().html(
				'<i>clic en un nombre para agregar</i><br><a onclick="addAll()">anadir todo</a>');

			newDiv.appendTo("#personasAdd");


			for (var key in json) {

				var newDiv = $(document.createElement('div'))
				.attr("id", 'TextBoxDiv');
				newDiv.after().html(
					'<input type="checkbox" hidden value="' + json[key]["idPersona"] + '" style="width: 15px;" id="choosePerson' + 
					json[key]["idPersona"] + '" onclick="addName(' + json[key]["idPersona"] + ')"/><label for="choosePerson' + 
					json[key]["idPersona"] + '">' + 
					json[key]["Nombre"] +  '</label>');

				newDiv.appendTo("#personasAdd");

				persons.push(json[key]["idPersona"]);
			}
		},
		error: function(e){
			console.log('error: ' + e);
		}
	});
}

function addName(id){
	var postData = 
	{
		"id" : id
	}
	var dataString = JSON.stringify(postData);
	$.ajax({
		method: "POST",
		data: {action:dataString},
		url: '../ajax/getVolunteerById',
		success: function(data){
			var json = JSON.parse(data);

			var newDiv = $(document.createElement('div'))
			.attr("id", 'Nombre' +  json['idPersona']);

			newDiv.after().html(
				'<p style="height: 25px;"> ' +  json['Nombre'] +' <a href="javascript:void(0)" onclick="deleteDiv(' + json['idPersona'] + ')" class="btn btn-danger pull-right"  role="button" > <span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></p>' );
			newDiv.appendTo("#personasAdded");

			if(counter % 3 == 0){
				var newDiv = $(document.createElement('div'))
				.attr("id", 'Carnet' +  json['idPersona']).attr("class",'col-md-4 carnets').attr("style",'padding: 0; width:335px;').attr("name",counter);
			}else{
				var newDiv = $(document.createElement('div'))
				.attr("id", 'Carnet' +  json['idPersona']).attr("class",'col-md-4 carnets').attr("style",'padding: 0; width:335px;').attr("name",counter);
			}


			newDiv.after().html(
				'<div style="border: 1px solid black; width: 330px; height: 275px;    margin: 5px 5px 20px 0px; padding: 0;"><img src="'+ window.location +'/../../assets/img/carnetlogo.jpg" style="width:40px; margin-top: 40px; margin-left: 20px; position: absolute"><p style="margin-top: 20px; margin-left: 65px; max-width: 200px; text-align: center; height: 40px;">' + json['Nombre']  + '</p><p style="margin-left: 65px;  max-width: 200px; text-align: center;">Voluntario</p><p style="margin-left: 65px;  max-width: 200px; text-align: center;">Fundación Zamora Terán</p><div class="col-md-12" style="padding:0;"><p ><img id="barcode' + counter + '" style="display: block;margin-left: auto;margin-right: auto;"></p></div></div>');
			newDiv.appendTo("#carnets");


			JsBarcode("#barcode" + counter , " " + json['NoDeCedula'] +"" , {
				format:"CODE39",
				displayValue:true,
				width:1
			});

			counter++;
		},
		error: function(e){
			console.log('error: ' + e);
		}
	});
}

function deleteDiv(id){
	document.getElementById('Nombre' +  id).remove();
	document.getElementById('Carnet' +  id).remove();
	counter--;
}

function printPage() {
	for (var i = 1; i <= counter; i++) {
		if(i%3==0) $( "#carnets div:nth-child(" + i + ")").addClass("newPage");
		
	}
	window.print();

	setTimeout(100);
	for (var i = 1; i <= counter; i++) {
		if(i%3==0)$( "#carnets div:nth-child(" + i + ")").removeClass("newPage");
	}
}


function addAll(){
	for (var key in persons) {
		addName(persons[key]);
	}
}