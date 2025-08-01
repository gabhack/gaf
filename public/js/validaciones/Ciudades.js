$(function(){
	
	if($("#depto").val() != "")
	{		
		getCiudades($("#depto").val())		
	}
	
	$("#depto").change(function(){
		getCiudades($(this).val())
	})
	
})


function getCiudades(depto)
{
	$.post('public/api/ciudades/xDepto', {'depto': depto}, function(data){
		$('#ciudad').html('<option value="">-Seleccione-</option>');
		$.each(data, function(index, elem){
			$('#ciudad').append('<option value="'+elem.id+'">'+elem.ciudad+'</option>');
		})
		
		if($("#city").val() != "")
		{
			$('#ciudad').val( $("#city").val() )
		}
	})
}