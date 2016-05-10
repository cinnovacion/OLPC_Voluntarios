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
var counter = 1;
function printPage() {
	document.getElementById("toRemove").style.opacity = 0;
	document.getElementById("toRemove").style.height = 0;
	window.print();
	setTimeout(100);
	document.getElementById("toRemove").style.opacity = 100;
	document.getElementById("toRemove").style.height = "100%";
}

function savePage() {
	var pdf = new jsPDF('p', 'pt', 'letter')

// source can be HTML-formatted string, or a reference
// to an actual DOM element from which the text will be scraped.
, source = document.getElementById("toPrint");


margins = {
	top: 10,
	bottom: 10,
	left: 40,
	width: 522
};

// all coords and widths are in jsPDF instance's declared units
// 'inches' in this case
pdf.fromHTML(
source // HTML string or DOM elem ref.
, margins.left // x coord
, margins.top // y coord
, {
'width': margins.width // max width of content on PDF
},
function (dispose) {
// dispose: object with X, Y of the last line add to the PDF
//          this allow the insertion of new lines after html
pdf.save($('#nombre').text()  + '.pdf');
},
margins
)
}

function addWork(){
	
	
	if(counter != 1){
		if(document.getElementById("textbox" + (counter - 1)).value == "") return;
		addTextdiv(counter-1);
	} 
	
	
    var newTextBoxDiv = $(document.createElement('div'))
    .attr("id", 'TextBoxDiv' + counter);

    newTextBoxDiv.after().html(
    	'<button type="button" class="btn btn-info" data-type="plus"  onclick="removediv(' + counter + ')">' +
    	'<span class="glyphicon glyphicon-remove">' +
    	'</span></button>' + 
    	'<label style="width:75px;margin-left:15px">Actividad:</label>' +
    	'<input type="text" class="form-control" name="textbox" id="textbox' + counter + '" value="" style="width:600px;display:initial;margin-right:15px;" > ' + 
    	'<button name="plusbutton" type="button" class="btn btn-info" data-type="plus" onclick="addTextdiv(' + counter + ')">' + 
    	'<span class="glyphicon glyphicon-plus">' + 
    	'</span></button>'
    	);

    newTextBoxDiv.appendTo("#addwork");
    counter++;
}

function removediv(removeMe){
	$("#TextBoxDiv" + removeMe).remove();
	document.getElementById("element" + (removeMe+1)).remove();
}

function addTextdiv(number){
	if(document.getElementById("textbox" + number).value == "") return;
	if(document.getElementById("element" + counter) == null){
		var ul = document.getElementById("worklist");
		var li = document.createElement("li");
		li.appendChild(document.createTextNode($("#textbox" + number).val()));
		li.setAttribute("id", "element" + counter); // added line
		ul.appendChild(li);
	}else{
		document.getElementById("element" + (number+1)).innerHTML = $("#textbox" + number).val();
	}
}	
