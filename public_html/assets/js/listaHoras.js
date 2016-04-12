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


$(document).ajaxStart(function() {
	$('#ajaxLoader').show();
});

$(document).ajaxStop(function() {
	$('#ajaxLoader').hide();
});

$( ".months" ).change(function() {
	fillWeeks();
	document.getElementById("month").innerHTML = monthNames[$('.months').val()-1];
	document.getElementById("week").innerHTML = $(".weeks :selected").text();
});

$(".years").change(function(){
	fillWeeks();
	document.getElementById("year").innerHTML = $('.years').val();
	document.getElementById("month").innerHTML = monthNames[$('.months').val()-1];
	document.getElementById("week").innerHTML = $(".weeks :selected").text();
});

$('.weeks').change(function(){
	document.getElementById("week").innerHTML = $(".weeks :selected").text();
})



$(".filter").change(function(){
	if($(this).val() == "week"){
		$(".weeks").removeAttr('disabled');
		$(".months").removeAttr('disabled');
	}else if($(this).val() == "month"){
		$(".weeks").attr('disabled', 'disabled');
		$(".months").removeAttr('disabled');
	}else if($(this).val() == "year"){
		$(".weeks").attr('disabled', 'disabled');
		$(".months").attr('disabled', 'disabled');
	}

});

$(window).load(function() {
	fillWeeks();
	document.getElementById("year").innerHTML = $('.years').val();
	document.getElementById("month").innerHTML = monthNames[$('.months').val()-1];
	document.getElementById("week").innerHTML = $(".weeks :selected").text();
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

	function fillErUp() {
		//$('#divToBeFilled').empty();

		var postData = 
		{	
			"voluntario" : $('#voluntarioId').text(),
			"filter" : $('.filter').val(),
			"year" : $('.years').val(),
			"month" : $('.months').val(),
			"week" : $( ".weeks" ).val()
		}
		var dataString = JSON.stringify(postData);
		$.ajax({
			method: "POST",
			data: {action:dataString},
			url: '../../ajax/getListaTrabaja',
			success: function(data){
				var json = JSON.parse(data);
				//console.log(json);
				
				
				
				$("#fillMeUp").empty(); 

				counter = 0;
				for (var i = 0; i < json.length; i++) {
					if(!json[i]){
						continue;
					}
					//console.log(json[i]);
					var row = document.getElementById("fillMeUp").insertRow(-1);

					$dia  = new Date(json[i]['dia']);
					row.insertCell(0).innerHTML = ($dia.getUTCDate()	) + '/'+ ($dia.getMonth()+1) + '/' + 	$dia.getUTCFullYear();


					row.insertCell(1).innerHTML = json[i]['horaInicio'];

					if(json[i]['horaFinal'] == null){
						row.insertCell(2).innerHTML = "/";
					}else{
						row.insertCell(2).innerHTML = json[i]['horaFinal'];
					}

					if(json[i]['tiempo'] == null){
						row.insertCell(3).innerHTML = "/";
					}else{
						row.insertCell(3).innerHTML = json[i]['tiempo'] + " horas";

					}
				}
			},
			error: function(e){
				console.log('error: ' + e);
			}
		});
	}

	function printPage() {
		splitTable($(".splitTable"), 650);
		var prtContent = document.getElementById("toPrint");
		// window.open('', '', 'left=0,top=0,'height=' + screen.height + ',width=' + screen.width + ',toolbar=0,scrollbars=0,status=0');
		var WinPrint =	window.open('', '','height=' + screen.height + ',width=' + screen.width + ',resizable=yes,scrollbars=yes,toolbar=yes,menubar=yes,location=yes')
		WinPrint.document.write('<link rel="stylesheet" href="../../assets/css/bootstrap.css"/>');
		WinPrint.document.write(prtContent.innerHTML);
		setTimeout(100);
		WinPrint.document.close();
		WinPrint.focus();
		WinPrint.print();
		WinPrint.close();
		fillErUp();
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
			$('<tbody id="fillMeUp"	 />').appendTo(newTable);
			rows.slice(splitIndices[i], splitIndices[i+1]).appendTo(newTable.children('tbody'));
			newTable.appendTo("#_split_table_wrapper");
			if (splitIndices[i+1] !== undefined) {
				$('<div style="page-break-after: always; margin:0; padding:0; border: none;"></div>').appendTo("#_split_table_wrapper");
			}
		}
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
		  	  pdf.save($('#voluntarioName').text() + '.pdf');
		  	}, 
		  	margins
		  	)

		//fillErUp();
	}