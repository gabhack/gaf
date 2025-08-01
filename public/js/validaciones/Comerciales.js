$(function(){
	$("#consultar").click(function(){
		$.post('public/api/comerciales/infoCliente', {'pagaduria': $("#pagad").val(), 'documento': $('#documento').val()}, function(data){
			$('#result').html('').html(data)
		})
	})
})