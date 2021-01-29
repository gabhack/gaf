$(function(){
			
	$(".loader").hide();
	
	$( window ).on('beforeunload', function() {
	  $(".loader").show();
	});
	
	$("#sidebar-toggle").click(function(){
		if($("body").hasClass("sidebar-mini"))
			$("body").hasClass("sidebar-open") ? $("body").removeClass("sidebar-open").trigger("expanded.pushMenu") : $("body").addClass("sidebar-open").trigger("collapsed.pushMenu")
		$("body").hasClass("sidebar-collapse") ? $("body").removeClass("sidebar-collapse").trigger("expanded.pushMenu") : $("body").addClass("sidebar-collapse").trigger("collapsed.pushMenu")
	})
	
	$(".user-menu > a").click(function(){
		$(this).parent().hasClass("open") ? $(this).parent().removeClass("open") : $(this).parent().addClass("open")
	})
	
	$(".treeview > a").click(function(){
			$(this).parent().find($(".treeview-menu")).toggle("fast");
		// $(this).parent().hasClass("active") ? $(this).parent().removeClass("active") : $(this).parent().addClass("active")
	})			
				
});
		
		
function format(num)
{
	if(!isNaN(num))
	{
		num = num.toString().split("").reverse().join("").replace(/(?=\d*\.?)(\d{3})/g,'$1.');
		num = num.split("").reverse().join("").replace(/^[\.]/, "");
		return(num);
	}

	else
	{ 
		alert("Solo se permiten numeros");
		return( nunmber.replace(/[^\d\.]*/g,"") );
	}
}

$( "#tipo-archivo" ).change(function() {

	if ($( "#tipo-archivo" ).val() == '') {
		//Ocultar
		if ( !document.getElementById("archivos").classList.contains('hidden') ){
			document.getElementById("archivos").classList.add('hidden');
		}

	} else {
		document.getElementById("archivos").classList.remove('hidden');

		$(".input-archivos").attr('name', $( "#tipo-archivo" ).val());
		$(".label-archivos").text($( "#tipo-archivo option:selected" ).text());
	}

});

$( "#pagaduria" ).change(function() {

	if ($( "#pagaduria" ).val() == '') {
		//Ocultar
		if ( !document.getElementById("panel-tipo-archivo").classList.contains('hidden') ){
			document.getElementById("panel-tipo-archivo").classList.add('hidden');
		}

	} else {
		document.getElementById("panel-tipo-archivo").classList.remove('hidden');
	}

});

$( "#asesor" ).change(function() {

	if ($( "#asesor" ).val() !== 'nuevo') {
		//Ocultar
		if ( !document.getElementById("asesor_custom").classList.contains('hidden') ){
			document.getElementById("asesor_custom").classList.add('hidden');
			document.getElementById("asesor_custom").required = false;
		}

	} else {
		document.getElementById("asesor_custom").classList.remove('hidden');
		document.getElementById("asesor_custom").required = true;
	}

});

function addRow() {
	var cont_childs = document.getElementById("content-archivos").childElementCount;

	const div = document.createElement('div');
	div.className = 'form-row';
  
	div.innerHTML = `
		<div class="form-group col-md-6">
			<label class="label-archivos"></label>
			<input class="input-archivos" type="file" class="form-control-file" id="archivos_`+ cont_childs +`" name="archivos[`+ cont_childs +`]" required>
		</div>
		<input type="button" value="-" onclick="removeRow(this)" />
	`;
	
	if (cont_childs <= 30) {
		document.getElementById('content-archivos').appendChild(div);
	} else {
		alert('No se puede agregar más de 30 archivos.');
	}
}

function removeRow(input) {
  	document.getElementById('content-archivos').removeChild(input.parentNode);
}