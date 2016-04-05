var monthNames = [
"enero", 
"febrero", 
"marzo",
"abril", 
"mayo", 
"junio", 
"julio",
"agosto", 
"septiembre",
"octubre",
"noviembre", 
"diciembre"
];

var areas=[
'Comunicación', 
'Mercadeo',
'Área Educativa',
'Programa de Voluntariado',
'Monitoreo y Evaluación',
'Soporte técnico'
];


$( ".months" ).change(function() {
	fillWeeks();
});

$(".years").change(function(){
	fillWeeks();
});

$(document).ajaxStart(function() {
  $('#ajaxLoader').show();
});

$(document).ajaxStop(function() {
$('#ajaxLoader').hide();
setTitles();
});

$(window).load(function() {
		//fillErUp();
		fillWeeks();
		//setTitles();
		//fillErUp();
		$('#ajaxLoader').hide();
	});

function getMondays(year, month_number) {
	var d = new Date(year, month_number-1),
	month = d.getMonth(),
	mondays = [];

	d.setDate(1);

	    // Get the first Monday in the month
	    while (d.getDay() !== 1) {
	    	d.setDate(d.getDate() + 1);
	    }

	    // Get all the other Mondays in the month
	    while (d.getMonth() === month) {
	    	mondays.push(new Date(d.getTime()));
	    	d.setDate(d.getDate() + 7);
	    }

	    return mondays;
	}

	function fillWeeks(){
		var mondays = getMondays($( ".years" ).val(),$( ".months" ).val());
		optionsHtml = '';
		for (var i = 0; i < mondays.length; i++) {
			var value = 'value=' +  mondays[i].getUTCFullYear() + '-' + (mondays[i].getMonth()+1) + '-' + mondays[i].getUTCDate();

			var datenow = new Date();
			var dateMonday = mondays[i];
			var firstDay = mondays[i].getUTCDate() + '/'+ (mondays[i].getMonth()+1) + '/' + mondays[i].getUTCFullYear();

			//set monday to friday
			mondays[i].setDate(mondays[i].getDate() + 4);

			var lastDay = (mondays[i].getUTCDate()	) + '/'+ (mondays[i].getMonth()+1) + '/' + 	mondays[i].getUTCFullYear(); 

			optionsHtml += '<option ' + value +'>Semana ' + (i+1) + ': ' + 	firstDay + ' - ' + lastDay + '</option>';
			
			
		}

		$('.weeks').html(optionsHtml);
	}

	function printPage() {
		splitTable($(".splitTable"), 500);
		var prtContent = document.getElementById("toPrint");
		// window.open('', '', 'left=0,top=0,'height=' + screen.height + ',width=' + screen.width + ',toolbar=0,scrollbars=0,status=0');
		var WinPrint =	window.open('', '','height=' + screen.height + ',width=' + screen.width + ',resizable=yes,scrollbars=yes,toolbar=yes,menubar=yes,location=yes')
		WinPrint.document.write('<link rel="stylesheet" href="../assets/css/bootstrap.css"/>');
		WinPrint.document.write(prtContent.innerHTML);
		WinPrint.document.close();
		WinPrint.focus();
		WinPrint.print();
		WinPrint.close();
		fillErUp();
	}

	function savePage() {
		//splitTable($(".splitTable"), 600);
		//document.getElementById("footer").style.width="522px";
		var pdf = new jsPDF('l', 'pt', 'a4')

		// source can be HTML-formatted string, or a reference
		// to an actual DOM element from which the text will be scraped.
		, source = document.getElementById("toPrint");

		margins = {
			top: 10,
			bottom: 10,
			left: 40,
			width: 750,
			height: 800
		};


		/**var columnsLong = [
			{title: "Lunes", dataKey: "id"},
			{title: "Martes", dataKey: "name"}, 
			{title: "Miercoles", dataKey: "country"}, 
			{title: "Jueves", dataKey: "country"}, 
			{title: "Viernes", dataKey: "country"}
			];

		pdf.autoTable(columnsLong, document.getElementById("toPrint"), {
			startY: pdf.autoTableEndPosY() + 45,
			margin: {horizontal: 10},
			styles: {overflow: 'linebreak'},
			bodyStyles: {valign: 'top'},
			columnStyles: {email: {columnWidth: 'wrap'}},
		});**/

		// all coords and widths are in jsPDF instance's declared units
		// 'inches' in this case
		pdf.fromHTML(
		  	source // HTML string or DOM elem ref.
		  	, margins.left // x coord
		  	, margins.top // y coord
		  	, {
		  		'width': margins.width, // max width of content on PDF
		  		'height': margins.height
		  	},
		  	function (dispose) {
		  	  // dispose: object with X, Y of the last line add to the PDF
		  	  //          this allow the insertion of new lines after html
		  	  pdf.save(document.getElementById("yearmonth").innerHTML  + ' ' + document.getElementById("week").innerHTML + '.pdf');
		  	}, 
		  	margins
		  	)

		//fillErUp();
	}

	function setTitles(){
		document.getElementById("yearmonth").innerHTML =   capitalizeFirstLetter(monthNames[$(".months").val()-1]) + ' ' + $(".years").val();
		document.getElementById("week").innerHTML  =  $(".weeks :selected").text();
	}


	function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

	function splitTable(table, maxHeight) {
		var header = table.children("thead"); 
		if (!header.length)
			return;

		var headerHeight = header.outerHeight();
		var header = header.detach();

		var splitIndices = [0];
		var rows = table.children("tbody").children();

		maxHeight -= headerHeight;
		var currHeight = 0;
		rows.each(function(i, row) {
			currHeight += $(rows[i]).outerHeight();
			if (currHeight > maxHeight) {
				splitIndices.push(i);
				currHeight = $(rows[i]).outerHeight();
			}
		});
		splitIndices.push(undefined);

		table = table.replaceWith('<div id="_split_table_wrapper"></div>');
		table.empty();

		for(var i=0; i<splitIndices.length-1; i++) {
			var newTable = table.clone();
			header.clone().appendTo(newTable);
			$('<tbody />').appendTo(newTable);
			rows.slice(splitIndices[i], splitIndices[i+1]).appendTo(newTable.children('tbody'));
			newTable.appendTo("#_split_table_wrapper");
			if (splitIndices[i+1] !== undefined) {
				$('<div style="page-break-after: always; margin:0; padding:0; border: none;"></div>').appendTo("#_split_table_wrapper");
			}
		}
	}
	
	function fillErUp() {

		
		$('#divToBeFilled').empty();
		
		var postData = 
		{
			"firstday" : $( ".weeks" ).val()
		}
		var dataString = JSON.stringify(postData);
		$.ajax({
			method: "POST",
			data: {action:dataString},
			url: '../ajax/getListaSemana',
			success: function(data){
				var json = JSON.parse(data);
				console.log(json);
				var maxVolunteers = Math.max(json['volunteers']['1'].length,json['volunteers']['2'].length,json['volunteers']['3'].length,json['volunteers']['4'].length,json['volunteers']['5'].length);

				for (var i = 0; i < maxVolunteers; i++) {
					var lunes = json['volunteers']['1'].slice(i, i+4);
				}$
				$('#divToBeFilled').append('<table class="table-bordered splitTable" style="margin: auto;" id="tableVolunteers"><thead><tr><th id="lunesTitle" style="width:210px" bgcolor="#ddd"></th><th id="martesTitle" style="width:210px"  bgcolor="#ddd"></th><th id="miercolesTitle"  style="width:210px" bgcolor="#ddd"></th><th id="juevesTitle"  style="width:210px" bgcolor="#ddd"></th><th id="viernesTitle"  style="width:210px" bgcolor="#ddd"></th></tr></thead><tbody  id="fillMeUp"></tbody></table>');
				
				// Find a <table> element with id="fillMeUp":
				var table = document.getElementById("fillMeUp");
				$("#fillMeUp tr").remove(); 

				
				document.getElementById("lunesTitle").innerHTML =  'Lunes (' + json['days'][1] + ')';
				document.getElementById("martesTitle").innerHTML =  'Martes (' + json['days'][2]+ ')';
				document.getElementById("miercolesTitle").innerHTML =  'Miercoles (' + json['days'][3]+ ')';
				document.getElementById("juevesTitle").innerHTML =  'Jueves (' + json['days'][4]+ ')';
				document.getElementById("viernesTitle").innerHTML =  'Viernes (' + json['days'][5]+ ')';

				while(json['volunteers']['1'].length != 0 || json['volunteers']['2'].length != 0 || json['volunteers']['3'].length != 0 || json['volunteers']['4'].length != 0 || json['volunteers']['5'].length != 0){
					

					// Create an empty <tr> element and add it to the 1st position of the table:
					var row = table.insertRow(-1);

					if(typeof json['volunteers']['1'][0] !== 'undefined'){
						row.insertCell(0).innerHTML = 
						"<p style='margin-top: 15px;margin-left: 5px;margin-right: 5px; width: 200px;'><b>Nombre: </b>" + json['volunteers']['1'][0]["Nombre"] + "</p>\n" + 
						"<p style='margin: 5px;margin-right: 5px; width: 200px;'><b>Organización: </b>" +json['volunteers']['1'][0]["Inst"]+ "</p>\n" + 
						"<p style='margin-bottom:15px;margin-top:5px;margin-left: 5px;margin-right: 5px; width: 200px;'><b>Area: </b>" +areas[json['volunteers']['1'][0]["Area"]-1]+ "</p>\n";
						

						json['volunteers']['1'].splice(0,1);
					}else{
						row.insertCell(0).innerHTML = "<p style='margin-left: 40%'>/</p>";
					}

					if(typeof json['volunteers']['2'][0] !== 'undefined'){
						row.insertCell(1).innerHTML = 
						"<p style='margin-top: 15px;margin-left: 5px;margin-right: 5px; width: 200px;'><b>Nombre: </b>" + json['volunteers']['2'][0]["Nombre"] + "</p>\n"+
						"<p style='margin: 5px;margin-right: 5px; width: 200px;'><b>Organización: </b>" +json['volunteers']['2'][0]["Inst"]+ "</p>\n"+
						"<p style='margin-bottom:15px;margin-top:5px;margin-left: 5px;margin-right: 5px; width: 200px;'><b>Area: </b>" +areas[json['volunteers']['2'][0]["Area"]-1]+ "</p>\n";
						
						json['volunteers']['2'].splice(0,1);
					}else{
						row.insertCell(1).innerHTML = "<p style='margin-left: 40%'>/</p>";
					}

					if(typeof json['volunteers']['3'][0] !== 'undefined'){
						row.insertCell(2).innerHTML = 
						"<p style='margin-top: 15px;margin-left: 5px;margin-right: 5px; width: 200px;'><b>Nombre: </b>" + json['volunteers']['3'][0]["Nombre"] + "</p>\n"+
						"<p style='margin: 5px;margin-right: 5px; width: 200px;'><b>Organización: </b>" +json['volunteers']['3'][0]["Inst"]+ "</p>\n"+
						"<p style='margin-bottom:15px;margin-top:5px;margin-left: 5px;margin-right: 5px; width: 200px;'><b>Area: </b>" +areas[json['volunteers']['3'][0]["Area"]-1]+ "</p>\n";

						json['volunteers']['3'].splice(0,1);
					}else{
						row.insertCell(2).innerHTML = "<p style='margin-left: 40%'>/</p>";
					}

					if(typeof json['volunteers']['4'][0] !== 'undefined'){
						row.insertCell(3).innerHTML = 
						"<p style='margin-top: 15px;margin-left: 5px;margin-right: 5px; width: 200px;'><b>Nombre: </b>" + json['volunteers']['4'][0]["Nombre"] + "</p>\n"+
						"<p style='margin: 5px;margin-right: 5px; width: 200px;'><b>Organización: </b>" +json['volunteers']['4'][0]["Inst"]+ "</p>\n"+
						"<p style='margin-bottom:15px;margin-top:5px;margin-left: 5px;margin-right: 5px; width: 200px;'><b>Area: </b>" +areas[json['volunteers']['4'][0]["Area"]-1]+ "</p>\n";

						json['volunteers']['4'].splice(0,1);
					}else{
						row.insertCell(3).innerHTML = "<p style='margin-left: 40%'>/</p>";
					}

					if(typeof json['volunteers']['5'][0] !== 'undefined'){
						row.insertCell(4).innerHTML = 

						"<p style='margin-top: 15px;margin-left: 5px;margin-right: 5px; width: 200px;'><b>Nombre: </b>" + json['volunteers']['5'][0]["Nombre"] + "</p>\n" + 
						"<p style='margin: 5px;margin-right: 5px; width: 200px;'><b>Organización: </b>" +json['volunteers']['5'][0]["Inst"]+ "</p>\n" + 
						"<p style='margin-bottom:15px;margin-top:5px;margin-left: 5px;margin-right: 5px; width: 200px;'><b>Area: </b>" +areas[json['volunteers']['5'][0]["Area"]-1]+ "</p>\n";

						json['volunteers']['5'].splice(0,1);
					}else{
						row.insertCell(4).innerHTML = "<p style='margin-left: 40%'>/</p>";
					}

				}
			},
			error: function(e){
				console.log('error: ' + e);
			}
		});




}