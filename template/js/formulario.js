$('#fromLunes').timepicker('setTime', '8:30 PM');
		$('#toLunes').timepicker('setTime', '5:00 PM');
		$('#fromMartes').timepicker('setTime', '8:30 PM');
		$('#toMartes').timepicker('setTime', '5:00 PM');
		$('#fromMiercoles').timepicker('setTime', '8:30 PM');
		$('#toMiercoles').timepicker('setTime', '5:00 PM');
		$('#fromJueves').timepicker('setTime', '8:30 PM');
		$('#toJueves').timepicker('setTime', '5:00 PM');
		$('#fromViernes').timepicker('setTime', '8:30 PM');
		$('#toViernes').timepicker('setTime', '5:00 PM');

		document.getElementsByClassName("trabajoLunes").onchange = switchLunes;
		document.getElementsByClassName("trabajoMartes").onchange = switchMartes;
		document.getElementsByClassName("trabajoMiercoles").onchange = switchMiercoles;
		document.getElementsByClassName("trabajoJueves").onchange = switchJueves;
		document.getElementsByClassName("trabajoViernes").onchange = switchViernes;

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
			}
		}

		function switchMiercoles() {
			if(document.getElementById("miercoles").style.opacity == 100){
				document.getElementById("miercoles").style.opacity = 0;
			}else{
				document.getElementById("miercoles").style.opacity = 100;
			}
		}

		function switchJueves() {
			if(document.getElementById("jueves").style.opacity == 100){
				document.getElementById("jueves").style.opacity = 0;
			}else{
				document.getElementById("jueves").style.opacity = 100;
			}
		}

		function switchViernes() {
			if(document.getElementById("viernes").style.opacity == 100){
				document.getElementById("viernes").style.opacity = 0;
			}else{
				document.getElementById("viernes").style.opacity = 100;
			}
		}