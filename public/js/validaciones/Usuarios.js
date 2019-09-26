$(function(){
	
	if($("#city").val() != "")
	{		
		getOficinas($("#city").val())		
	}
	
	$("#ciudad").change(function(){
		getOficinas($(this).val())
	})
	
})