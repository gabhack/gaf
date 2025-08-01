$(function(){
	
	$("#btnConsulta").click(function(){
		$.post('public/api/reportes/consultas', { 
				'documento'	: $("#documento").val(), 
				'pagaduria'	: $("#pagaduria").val(),
				'consulta'	: $("#consulta").val(),
			}, function(data){
				$("#reporte").html('').html(data)
		})
	})
	
})