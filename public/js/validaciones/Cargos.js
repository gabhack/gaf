$(function(){
	
	// Cargos
	if($("#vinculacion").val() != "")
	{		
		getCargos($("#vinculacion").val())		
		getAportes($("#vinculacion").val())		
	}
	
	$("#vinculacion").change(function(){
		getCargos($(this).val())
		getAportes($(this).val())
	})
	
	// Asignación Adicional
	if($("#cargo").val() != "")
	{		
		getAsignacion($("#cargo").val())
	}
	
	$("#cargo").change(function(){
		getAsignacion($(this).val())
	})
	
})


function getCargos(vinculacion)
{
	$.post('public/api/cargos/xVinculacion', {'vinculacion': vinculacion}, function(data){
		$('#asignacion_adicional').val(0);
		
		$('#cargo').html('<option value="">-Seleccione-</option>');
		$.each(data, function(index, elem){
			$('#cargo').append('<option value="'+elem.id+'">'+elem.cargo+'</option>');
		})
		
		if($("#position").val() != "")
		{
			$('#cargo').val( $("#position").val() )
			getAsignacion( $("#position").val() )
		}
	})
}

function getAsignacion(cargo)
{
	$.post('public/api/cargos/xCargo', {'cargo': cargo}, function(data){
		$('#asignacion').val(data.asignacion_adicional);				
		calcularPaso2();
	})
}

function getAportes(vinculacion)
{
	$.post('public/api/parametros/xVinculacion', {'vinculacion': vinculacion}, function(data){
		$('#aportes').val(data.valor);				
		calcularPaso2();
	})
}

function calcularPaso2()
{
	if( $("#total_dctos").length > 0 ){
		
		$("#spanAdicional").html('').html( format( $("#asignacion").val() * $("#ingresos").val() ) )
		$("#adicional").val( $("#asignacion").val() * $("#ingresos").val() )
		
		$("#span_aportes").html('').html( format( parseInt( $("#aportes").val() * ( parseInt($("#ingresos").val()) + parseInt($("#adicional").val()) ) ) ) )
		
		$("#asignacion_adicional").val( $("#asignacion").val() * $("#ingresos").val() )
		$("#valor_aportes").val( $("#aportes").val() * ( parseInt($("#ingresos").val()) + parseInt($("#adicional").val()) ) )
		
		
		$.post('public/api/terecuperamos/calcularDecision', 
			{
				'vinculacion': 	$("#vinculacion").val(),
				'ingresos': 	$("#ingresos").val(),
				'valor_aportes':$("#valor_aportes").val(),
				'total_dctos': 	$("#total_dctos").val(),
				'asignacion_adicional': $("#asignacion_adicional").val(),
			}, 
			function(data){
				$('#libreInversion').html( format(data.libreInversion) );
				$('#libre_inversion').val(data.libreInversion);
				
				$('#compraCartera').html( format(data.compraCartera) );				
				$('#compra_cartera').val(data.compraCartera);				
			}
		)		
	}
}