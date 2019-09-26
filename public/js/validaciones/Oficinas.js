$(function(){
	
	if($("#ciudad").val() != "")
	{		
		getOficinas($("#ciudad").val())		
	}
	
	$("#ciudad").change(function(){
		getOficinas($(this).val())
	})
	
})


function getOficinas(ciudad)
{
	$.post('public/api/oficinas/xCiudad', {'ciudad': ciudad}, function(data){
		$('#oficina').html('<option value="">-Seleccione-</option>');
		$.each(data, function(index, elem){
			$('#oficina').append('<option value="'+elem.id+'">'+elem.oficina+'</option>');
		})
		
		if($("#office").val() != "")
		{
			$('#oficina').val( $("#office").val() )
		}
	})	
}