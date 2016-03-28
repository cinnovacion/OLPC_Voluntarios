var overlay = false;
var ok = false
var idPersona;
var monthNames = [
"Enero", 
"Febrero", 
"Marzo",
"Abril", 
"Mayo", 
"Junio", 
"Julio",
"Agosto", 
"Septiembre",
"Octubre",
"Noviembre", 
"Diciembre"
];


$(window).keydown(function(event){
	if(event.keyCode == 13) {
		if(!overlay){
			event.preventDefault();
			$('#loginform_barcode').blur();
			checkVolunteer();
		}else{
			if(ok){
				document.getElementById('loginform_barcode').value = '';
				closeNav();
				$('#loginform_barcode').focus();
			}else{
				postForm();
			}
		}
	}
});

$('#loginform_barcode').keyup(function () {
    if (this.value.length == 16) {
    	this.blur();
        $('#proceder').click();
    }
});

$(window).load(function() {
	$('#loginform_barcode').focus();
});

$("#no").click(function() {
	closeNav();
});

$('#si').click(function(){
	postForm();
})

$('#ok').click(function(){
	document.getElementById('loginform_barcode').value = '';
	closeNav();
	$('#loginform_barcode').focus();
})

$('#proceder').click(function(){
	checkVolunteer()
})

function checkVolunteer() {
	var postData = 
	{
		"cedula": document.getElementById('loginform_barcode').value
	}
	var dataString = JSON.stringify(postData);
	$.ajax({
		method: "POST",
		data: {action:dataString},
		url: '../ajax/getVolunteer',
		success: function(data){
			var json = JSON.parse(data);
			if(!json){
				document.getElementById("error").style.opacity = 100;
				overlay = false;
				$('#loginform_barcode').focus();
			}else{
				idPersona = json['idPersona'];
				document.getElementById("error").style.opacity = 0;
				openNav();
				showYesNo();
				document.getElementById('line1').innerHTML = '&iquest;Usted es ' + json['Nombre'] +'?';
				document.getElementById('line2').innerHTML = '';
				document.getElementById('line3').innerHTML = '';
			}
		},
		error: function(e){
			console.log('error: ' + e);
		}
	});
}

function postForm() {
	var postData = 
	{
		"idPersona": idPersona
	}
	var dataString = JSON.stringify(postData);
	$.ajax({
		method: "POST",
		data: {action:dataString},
		url: '../ajax/logVolunteer',
		success: function(data){
			var json = JSON.parse(data);
			var date = new Date();
			var day = date.getDate();
			var monthIndex = date.getMonth();
			var year = date.getFullYear();
			if(typeof json['horaFinal'] === 'undefined'){
				hideYesNo();
				document.getElementById('line1').innerHTML = 'Fecha: ' + day + ' de ' + monthNames[monthIndex] + ' ' + year;
				document.getElementById('line2').innerHTML = 'Hora de entrada: ' + new Date().toLocaleTimeString('en-US');
				document.getElementById('line3').innerHTML = '';
				showOk();
			}else{
				hideYesNo();
				document.getElementById('line1').innerHTML = 'Fecha: ' + day + ' de ' + monthNames[monthIndex] + ' ' + year;
				document.getElementById('line2').innerHTML = 'Hora de salida: ' + new Date().toLocaleTimeString('en-US');
				document.getElementById('line3').innerHTML = 'Horas accumuladas: ' + json['tiempo'] + ' horas';
				showOk();
			}
		},
		error: function(e){
			console.log('error: ' + e);
		}
	});
}

function openNav() {
	overlay = true;
	document.getElementById("myNav").style.width = "100%";
	document.getElementById("si").style.opacity = "100";
	document.getElementById("no").style.opacity = "100";
}

function closeNav() {
	overlay = false;
	document.getElementById("myNav").style.width = "0%";
	showYesNo();
}

function showYesNo(){
	document.getElementById("si").style.opacity = "100";
	document.getElementById("no").style.opacity = "100";

	document.getElementById("si").style.width = "100%";
	document.getElementById("no").style.width = "100%";

	document.getElementById("si").style.height = "100%";
	document.getElementById("no").style.height = "100%";
	hideOk();
}

function hideYesNo(){
	document.getElementById("si").style.opacity = "0";
	document.getElementById("no").style.opacity = "0";

	document.getElementById("si").style.width = "0";
	document.getElementById("no").style.width = "0";

	document.getElementById("si").style.height = "0";
	document.getElementById("no").style.height = "0";
}

function showOk(){
	document.getElementById("ok").style.opacity = "100";
	document.getElementById("ok").style.width = "100%";
	document.getElementById("ok").style.height = "100%";
	ok = true;
}

function hideOk(){
	document.getElementById("ok").style.opacity = "0";
	document.getElementById("ok").style.width = "0";
	document.getElementById("ok").style.height = "0";
	ok = false;
}