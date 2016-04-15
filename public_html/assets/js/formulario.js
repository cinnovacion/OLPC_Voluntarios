$('#nuevoform_DiaInicio').datetimepicker({ format: 'DD/MM/YYYY'}); 

document.getElementsByClassName("trabajoLunes").onchange = switchLunes;
document.getElementsByClassName("trabajoMartes").onchange = switchMartes;
document.getElementsByClassName("trabajoMiercoles").onchange = switchMiercoles;
document.getElementsByClassName("trabajoJueves").onchange = switchJueves;
document.getElementsByClassName("trabajoViernes").onchange = switchViernes;

if(document.getElementById("nuevoform_trabajoLunes").checked){
	document.getElementById("lunes").style.opacity = 100;
	$('#nuevoform_fromLunes').timepicker('setTime', document.getElementById("nuevoform_fromLunes").value);
	$('#nuevoform_toLunes').timepicker('setTime', document.getElementById("nuevoform_toLunes").value);
}
if(document.getElementById("nuevoform_trabajoMartes").checked){
	document.getElementById("martes").style.opacity = 100;
	$('#nuevoform_fromMartes').timepicker('setTime', document.getElementById("nuevoform_fromMartes").value);
	$('#nuevoform_toMartes').timepicker('setTime', document.getElementById("nuevoform_toMartes").value);
}
if(document.getElementById("nuevoform_trabajoMiercoles").checked){
	document.getElementById("miercoles").style.opacity = 100;
	$('#nuevoform_fromMiercoles').timepicker('setTime', document.getElementById("nuevoform_fromMiercoles").value);
	$('#nuevoform_toMiercoles').timepicker('setTime', document.getElementById("nuevoform_toMiercoles").value);
}
if(document.getElementById("nuevoform_trabajoJueves").checked){
	document.getElementById("jueves").style.opacity = 100;
	$('#nuevoform_fromJueves').timepicker('setTime', document.getElementById("nuevoform_fromJueves").value);
	$('#nuevoform_toJueves').timepicker('setTime', document.getElementById("nuevoform_toJueves").value);
}
if(document.getElementById("nuevoform_trabajoViernes").checked){
	document.getElementById("viernes").style.opacity = 100;
	$('#nuevoform_fromViernes').timepicker('setTime', document.getElementById("nuevoform_fromViernes").value);
	$('#nuevoform_toViernes').timepicker('setTime', document.getElementById("nuevoform_toViernes").value);
}

function switchLunes() {
	if(document.getElementById("lunes").style.opacity == 100){
		document.getElementById("lunes").style.opacity = 0;
	}else{
		document.getElementById("lunes").style.opacity = 100;
		$('#nuevoform_fromLunes').timepicker('setTime', checkInicio("lunes"));
		$('#nuevoform_toLunes').timepicker('setTime', checkFinal("lunes"));
	}
}

function switchMartes() {
	if(document.getElementById("martes").style.opacity == 100){
		document.getElementById("martes").style.opacity = 0;
	}else{
		document.getElementById("martes").style.opacity = 100;
		$('#nuevoform_fromMartes').timepicker('setTime', checkInicio("martes"));
		$('#nuevoform_toMartes').timepicker('setTime', checkFinal("martes"));
	}
}

function switchMiercoles() {
	if(document.getElementById("miercoles").style.opacity == 100){
		document.getElementById("miercoles").style.opacity = 0;
	}else{
		document.getElementById("miercoles").style.opacity = 100;
		$('#nuevoform_fromMiercoles').timepicker('setTime', checkInicio("miercoles"));
		$('#nuevoform_toMiercoles').timepicker('setTime', checkFinal("miercoles"));
	}
}

function switchJueves() {
	if(document.getElementById("jueves").style.opacity == 100){
		document.getElementById("jueves").style.opacity = 0;
	}else{
		document.getElementById("jueves").style.opacity = 100;
		$('#nuevoform_fromJueves').timepicker('setTime', checkInicio("jueves"));
		$('#nuevoform_toJueves').timepicker('setTime', checkFinal("jueves"));
	}
}

function switchViernes() {
	if(document.getElementById("viernes").style.opacity == 100){
		document.getElementById("viernes").style.opacity = 0;
	}else{
		document.getElementById("viernes").style.opacity = 100;
		$('#nuevoform_fromViernes').timepicker('setTime', checkInicio("viernes"));
		$('#nuevoform_toViernes').timepicker('setTime', checkFinal("viernes"));
	}
}

function checkInicio(day) {
	if(document.getElementById("nuevoform_trabajoLunes").checked && day != "lunes"){
		return document.getElementById("nuevoform_fromLunes").value;
	}
	if(document.getElementById("nuevoform_trabajoMartes").checked && day != "martes"){
		return document.getElementById("nuevoform_fromMartes").value;
	}
	if(document.getElementById("nuevoform_trabajoMiercoles").checked  && day != "miercoles"){
		return document.getElementById("nuevoform_fromMiercoles").value;
	}
	if(document.getElementById("nuevoform_trabajoJueves").checked  && day != "jueves"){
		return document.getElementById("nuevoform_fromJueves").value;
	}
	if(document.getElementById("nuevoform_trabajoViernes").checked  && day != "viernes"){
		return document.getElementById("nuevoform_fromViernes").value;
	}
	return "8:00 AM";
}

function checkFinal(day) {
	if(document.getElementById("nuevoform_trabajoLunes").checked && day != "lunes"){
		return document.getElementById("nuevoform_toLunes").value;
	}
	if(document.getElementById("nuevoform_trabajoMartes").checked && day != "martes"){
		return document.getElementById("nuevoform_toMartes").value;
	}
	if(document.getElementById("nuevoform_trabajoMiercoles").checked  && day != "miercoles"){
		return document.getElementById("nuevoform_toMiercoles").value;
	}
	if(document.getElementById("nuevoform_trabajoJueves").checked  && day != "jueves"){
		return document.getElementById("nuevoform_toJueves").value;
	}
	if(document.getElementById("nuevoform_trabajoViernes").checked  && day != "viernes"){
		return document.getElementById("nuevoform_toViernes").value;
	}
	return "17:00 AM";
}


$( "#nuevoform_DiaInicio" ).focus(function() {
	$('#openCalendar').click()
});
$('input, select').keypress(function(event) { return event.keyCode != 13; });