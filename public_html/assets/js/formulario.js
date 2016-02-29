$('#datetimepicker1').datetimepicker({ format: 'DD/MM/YYYY'}); 
$("#datetimepicker2").datetimepicker({ format: 'DD/MM/YYYY'	});

document.getElementsByClassName("trabajoLunes").onchange = switchLunes;
document.getElementsByClassName("trabajoMartes").onchange = switchMartes;
document.getElementsByClassName("trabajoMiercoles").onchange = switchMiercoles;
document.getElementsByClassName("trabajoJueves").onchange = switchJueves;
document.getElementsByClassName("trabajoViernes").onchange = switchViernes;

if(document.getElementById("nuevoform_trabajoLunes").checked){
	document.getElementById("lunes").style.opacity = 100;
	$('#nuevoform_fromLunes').timepicker('setTime', document.getElementById("nuevoform_fromLunes").value);
	$('#nuevoform_toLunes').timepicker('setTime', document.getElementById("nuevoform_toLunes").value);
}else if(!document.getElementById("nuevoform_trabajoLunes").checked){
	$('#nuevoform_fromLunes').timepicker('setTime', '8:30 AM');
	$('#nuevoform_toLunes').timepicker('setTime', '5:00 PM');
}
if(document.getElementById("nuevoform_trabajoMartes").checked){
	document.getElementById("martes").style.opacity = 100;
	$('#nuevoform_fromMartes').timepicker('setTime', document.getElementById("nuevoform_fromMartes").value);
	$('#nuevoform_toMartes').timepicker('setTime', document.getElementById("nuevoform_toMartes").value);
}else if(!document.getElementById("nuevoform_trabajoMartes").checked){
	$('#nuevoform_fromMartes').timepicker('setTime', '8:30 AM');
	$('#nuevoform_toMartes').timepicker('setTime', '5:00 PM');
}
if(document.getElementById("nuevoform_trabajoMiercoles").checked){
	document.getElementById("miercoles").style.opacity = 100;
	$('#nuevoform_fromMiercoles').timepicker('setTime', document.getElementById("nuevoform_fromMiercoles").value);
	$('#nuevoform_toMiercoles').timepicker('setTime', document.getElementById("nuevoform_toMiercoles").value);
}else if(!document.getElementById("nuevoform_trabajoMiercoles").checked){
	$('#nuevoform_fromMiercoles').timepicker('setTime', '8:30 AM');
	$('#nuevoform_toMiercoles').timepicker('setTime', '5:00 PM');
}
if(document.getElementById("nuevoform_trabajoJueves").checked){
	document.getElementById("jueves").style.opacity = 100;
	$('#nuevoform_fromJueves').timepicker('setTime', document.getElementById("nuevoform_fromJueves").value);
	$('#nuevoform_toJueves').timepicker('setTime', document.getElementById("nuevoform_toJueves").value);
}else if(!document.getElementById("nuevoform_trabajoJueves").checked){
	$('#nuevoform_fromJueves').timepicker('setTime', '8:30 AM');
	$('#nuevoform_toJueves').timepicker('setTime', '5:00 PM');
}
if(document.getElementById("nuevoform_trabajoViernes").checked){
	document.getElementById("viernes").style.opacity = 100;
	$('#nuevoform_fromViernes').timepicker('setTime', document.getElementById("nuevoform_fromViernes").value);
	$('#nuevoform_toViernes').timepicker('setTime', document.getElementById("nuevoform_toViernes").value);
}else if(!document.getElementById("nuevoform_trabajoViernes").checked){
	$('#nuevoform_fromViernes').timepicker('setTime', '8:30 AM');
	$('#nuevoform_toViernes').timepicker('setTime', '5:00 PM');
}

function switchLunes() {
	if(document.getElementById("lunes").style.opacity == 100){
		document.getElementById("lunes").style.opacity = 0;
	}else{
		document.getElementById("lunes").style.opacity = 100;

	}
}

function switchMartes() {
	if(document.getElementById("martes").style.opacity == 100){
		document.getElementById("martes").style.opacity = 0;
	}else{
		document.getElementById("martes").style.opacity = 100;
		$('#nuevoform_fromMartes').timepicker('setTime', '8:30 AM');
		$('#nuevoform_toMartes').timepicker('setTime', '5:00 PM');
	}
}

function switchMiercoles() {
	if(document.getElementById("miercoles").style.opacity == 100){
		document.getElementById("miercoles").style.opacity = 0;
	}else{
		document.getElementById("miercoles").style.opacity = 100;
		$('#nuevoform_fromMiercoles').timepicker('setTime', '8:30 AM');
		$('#nuevoform_toMiercoles').timepicker('setTime', '5:00 AM');
	}
}

function switchJueves() {
	if(document.getElementById("jueves").style.opacity == 100){
		document.getElementById("jueves").style.opacity = 0;
	}else{
		document.getElementById("jueves").style.opacity = 100;
		$('#nuevoform_fromJueves').timepicker('setTime', '8:30 AM');
		$('#nuevoform_toJueves').timepicker('setTime', '5:00 PM');
	}
}

function switchViernes() {
	if(document.getElementById("viernes").style.opacity == 100){
		document.getElementById("viernes").style.opacity = 0;
	}else{
		document.getElementById("viernes").style.opacity = 100;
		$('#nuevoform_fromViernes').timepicker('setTime', '8:30 AM');
		$('#nuevoform_toViernes').timepicker('setTime', '5:00 PM');
	}
}