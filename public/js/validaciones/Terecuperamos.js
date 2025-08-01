$(function(){
	
	$("#ingresos, #total_dctos").on('keyup blur paste change', function(){		
		calcularPaso2();
	})
	
	
	// Pasos
		$("#btn_paso1, #btn_paso2, #btn_paso3, #btn_paso4, #btn_paso5, #btn_paso6, #btn_paso7").click(function(){
			savePasos();
		})
	
	// Paso 2
		$("#valor_inicial, #dcto_logrado").on('keyup blur change paste', function(){
			saldoCartera = $("#valor_inicial").val() - $("#dcto_logrado").val()
			$("#spanSaldoNegociado").html('').html( format(saldoCartera) )
			$("#saldoNegociado").val( saldoCartera )
		})
	
	// Paso 5
		$("#costos, #valor_certificado, #gmf").on('keyup blur change paste', function(){
			calcularCk();
		})
		
		$("#costo_juridico").change(function(){
			if($(this).val() == 'S')
			{
				$("#span_juridico").html('').html( format($("#valor_juridico").val()) )
			}
			else
			{
				$("#span_juridico").html('')
			}
			
			calcularCk();
		})
	
	// Paso 6
		$("#tasa_ck, #plazo, #anticipo").on('keyup blur change paste', function(){
			calcularPaso6()
		})
	
	// Paso 7
		$("#aliado_af").change(function(){
			if( $(this).val() == '' )
			{
				$("#maxPlazo").val('')
				$("#plazo_af").val('').attr('readonly', 'readonly')
			}
			else
			{
				$.post('public/api/aliados/show', {aliado : $(this).val()}, function(data){
					$("#maxPlazo").val(data.max_plazo)
					$("#plazo_af").val(data.max_plazo)
					
					$("#plazo_af").removeAttr('readonly')
				})
			}
		})
		
		$("#cuotaAliado, #plazo_af").on('keyup blur change paste', function(){
			if( $("#cuotaAliado").val() > parseInt($("#maxCupo").val()) )
			{
				alert('La cuota no puede ser mayor a ' + $("#maxCupo").val())
				$(this).val('')
			}
			else if( $("#plazo_af").val() > parseInt($("#maxPlazo").val()) )
			{
				alert('El plazo no puede ser mayor a ' + $("#maxPlazo").val())
				$(this).val($("#maxPlazo").val())
			}
			else
			{
				calcularPaso7();
			}
		})
		
		$("#valorTitulos").on('keyup blur change paste', function(){
			
			saldoClienteTotal = parseInt( $("#saldoAlCliente").val() ) + parseInt( $(this).val() || 0 )
			$("#saldoClienteTotal").html('').html( format(saldoClienteTotal) )
		})
})

function savePasos()
{
	// Paso 1
		forma = $("#frm_paso1").serialize();		
		$(".loader").show();
		
		$.post('public/api/terecuperamos/paso1', {'form': forma}, function(data){
			
			// Paso 2
			forma = $("#frm_paso2").serialize();
			
			$.post('public/api/terecuperamos/paso2', {'form': forma}, function(data){			
		
				// Paso 3
				forma = $("#frm_paso3").serialize();
				
				$.post('public/api/terecuperamos/paso3', {'form': forma}, function(data){
					$("#spanSaldoNegociado").html('')
					$(".paso3").val('');
					$('#carteras').html('').html(data)
					
					
					// Paso 4
					forma = $("#frm_paso4").serialize();
					
					$.post('public/api/terecuperamos/paso4', {'form': forma}, function(data){
					
						// Paso 5
						forma = $("#frm_paso5").serialize();
						
						$.post('public/api/terecuperamos/paso5', {'form': forma}, function(data){
							
							cupoMaximo();
							calcularCarteras($("#estudio").val())
							
						})
					})
				})
			})
		})
} // savePasos 


function savePaso6()
{	
	// Paso 6
	forma = $("#frm_paso6").serialize();		

	$.post('public/api/terecuperamos/paso6', {'form': forma}, function(data){
		
		//Amortizacion
		$("#amortizacion").html('')
		i = 1
		$.each(data, function(index, elem){
			$("#amortizacion").append('<tr>\
				<td>'+i+'</td> \
				<td>'+format(elem.capital)+'</td> \
				<td>0</td> \
				<td>'+format(elem.seguros)+'</td> \
				<td>'+format(elem.interes)+'</td> \
				<td>'+format(elem.cuota)+'</td> \
				<td>'+format(elem.saldo)+'</td> \
			</tr>')
			i++;
		})
		
		calcularAliado( $("#estudio").val() )
	})
}


function savePaso7()
{		
	// Paso 7
	forma = $("#frm_paso7").serialize();		
	
	$.post('public/api/terecuperamos/paso7', {'form': forma}, function(data){
		
		$(".loader").hide();									
	})	
}


function calcularAliado(estudio)
{
	$.post('public/api/carteras/total', {'estudio' : estudio}, function(data){
		
		$("#valorCarteraAF").html('').html( format(data['alia']));
		$("#valorCompra").val( data['alia'] );
		
		calcularPaso7();		
		savePaso7();
	})
}

function updateCartera(e, id)
{
	e.preventDefault();
	
	$(".loader").show();
	
	$.post('public/api/carteras/show', {'cartera' : id}, function(data){
		$("#entidad").val(data.entidades_id);
		$("#sector").val(data.sectores_id);
		$("#estado_cartera").val(data.estadoscarteras_id);
		$("#comprado_por").val(data.comprado_por);
		$("#cuota").val(data.cuota);
		$("#cartera").val(data.id);
		$("#saldoCartera").val(data.saldo);
		$("#valor_inicial").val(data.valor_ini);
		$("#dcto_logrado").val(data.dcto_logrado);		
		$("#porc_negociado").val(data.porc_negociado);		
		
		$("#spanSaldoNegociado").html('').html( format(data.saldo_negociado) );
		$("#saldoNegociado").val(data.saldo_negociado);
		
		$("#fecha_vence").val(data.fecha_vence);
		
		$(".loader").hide();
	})	
}


function deleteCartera(e, item, id)
{
	e.preventDefault();
	conf = confirm('Seguro que desea eliminar este item y su informacion relacionada?')
	if(conf)
	{
		$(".loader").show();
		$.post('public/api/carteras/delete', {'cartera' : id}, function(data){
			calcularCarteras($("#estudio").val())
			$(".loader").hide();			
		})
		$(item).parent().parent().remove();
	}
}


function getCarteras(estudio)
{
	$.post('public/api/carteras/total', {'estudio' : estudio}, function(data){
		// CK
		$("#valor_carteras").val(data['ck']);
		$("#total_carteras").html('').html( format(data['ck']) );
		
		$("#total_carteras").html('').html( format(data['ck']));
		$("#totaltr").val( data['ck'] );
		
		// Aliado
		$("#valorCompra").val(data['alia']);
		$("#valorCarteraAF").html('').html( format(data['alia']) );
	})
}

function calcularCarteras(estudio)
{
	$.post('public/api/carteras/total', {'estudio' : estudio}, function(data){
		// CK
		$("#valor_carteras").val(data['ck']);
		$("#total_carteras").html('').html( format(data['ck']) );
		
		$("#total_carteras").html('').html( format(data['ck']));
		$("#totaltr").val( data['ck'] );
		
		// Aliado
		$("#valorCompra").val(data['alia']);
		$("#valorCarteraAF").html('').html( format(data['alia']) );
		calcularCk();
	})
}


function calcularCk()
{	
	costos = parseInt( $('#costos').val()  || 0 ) * parseInt( $("#valor_carteras").val() || 0 ) / 100 ;
	
	if($("#costo_juridico").val() == 'S')
	{
		juridicos = parseInt( $("#valor_juridico").val() )
	}
	else
	{
		juridicos = 0
	}
	
	IVA = parseInt((costos + juridicos) * $('#iva').val() / 100);
	$("#valor_costos").html('').html( format(costos) )
	$("#costostr").val( costos )
	
	
	$("#impuestos").html('').html( format(IVA) )	
	$("#imptostr").val( IVA )	

	total = juridicos + parseFloat($("#totaltr").val() || 0) + parseInt($("#costostr").val() || 0) + parseInt($("#imptostr").val() || 0) + parseInt($("#valor_certificado").val() || 0) + parseInt($("#gmf").val() || 0)
	$("#span_terecuperamos").html('').html( format(total) )
	
	$("#precio_carteras").html('').html( format(total) )
	$("#total_a_comprar").val(total)
	
	calcularPaso6()
}


function cupoMaximo()
{
	
	$.post('public/api/carteras/compraCk', {'estudio' : $("#estudio").val()}, function(data){
		$("#cupo_maximo_ck").html( format(parseInt(data) + parseInt( $('#libre_inversion').val() )) ) ;
		$("#cupo_maximo").val(parseInt(data) + parseInt( $('#libre_inversion').val() ) );
	})
}

function calcularCuota()
{
	total 	= parseInt( $("#total_ck").val() );
	tasa 	= $("#tasa_ck").val() / 100;
	plazo 	= $("#plazo").val() == '' ? 0 : $("#plazo").val();
	
	cuota = (total * tasa) / ( 1 - Math.pow(1 + tasa, ( -1 * plazo )) )
	
	cuotaFinal = cuota + parseInt( $("#seguro_ck").val() );
	
	$("#span_cuota_ck").html('').html( format(Math.ceil(cuotaFinal)) )
	$("#cuota_ck").val( Math.ceil(cuotaFinal) )
	
	savePaso6();
}

function calcularPaso6()
{
	
	valor = $("#anticipo").val() == '' ? 0 : $("#anticipo").val();
	porc = $("#porc_anticipo").val();
	
	total = parseInt(valor) + parseInt(valor * porc / 100);
	$("#total_anticipo").html('').html( format(total) );
	
	if($("#totaltr").val() == '')
	{
		costos = ( parseInt ($("#total_a_comprar").val() ) + total ) * $("#porc_costos").val() / 100
		gmf = ( parseInt ($("#total_a_comprar").val() ) + total ) * $("#porc_gmf").val()
	}
	else
	{
		costos = ( parseInt ($("#totaltr").val() ) + total ) * $("#porc_costos").val() / 100		
		gmf = ( parseInt ($("#totaltr").val() ) + total ) * $("#porc_gmf").val()
	}
	
	imptos = Math.ceil( costos * $("#iva").val() / 100 )
	
	$("#span_costos").html('').html( format(parseInt(costos)) )
	$("#imptos").html('').html( format(imptos) )
	
	console.log( 'total: ' + $("#totaltr").val() )
	
	$("#span_gmf_ck").html('').html( format(parseInt( gmf )) )
	$("#gmf_ck").val( parseInt( gmf ) )
	
	total_ck = costos + imptos + gmf + parseInt ($("#total_a_comprar").val() )
	seguros = parseInt( total_ck * $("#porc_seguro").val() );
	
	$("#span_seguro_ck").html('').html( format(seguros) )
	$("#seguro_ck").val( seguros )
	
	$("#span_total_ck").html('').html( format(parseInt(total_ck)) )
	$("#total_ck").val( Math.ceil(total_ck) )
	
	calcularCuota()
}


function calcularPaso7()
{
	$.post('public/api/aliados/getFactor', 
		{ 	
			aliado : $("#aliado_af").val(), 
			plazo : $("#plazo_af").val(),
			pagaduria : $("#pagaduria").val(),
			fechaNto : $("#fecha_nto").val(),
			tasa : $("#tasa_af").val(),
		}, function(data){
			if(data != '')
			{
				montoAF = parseInt( $("#cuotaAliado").val() / data.factor )
				saldoAlCliente = montoAF - parseInt( $("#valorCompra").val() );
				saldoClienteTotal = saldoAlCliente + parseInt( $("#valorTitulos").val() || 0 )
				
				$("#factor").val( data.factor )
				$("#spanMontoAF").html('').html( format(montoAF) )
				
				$("#spanSaldoAlCliente").html('').html( format(saldoAlCliente) )
				$("#saldoAlCliente").val( saldoAlCliente )
				
				$("#saldoClienteTotal").html('').html( format(saldoClienteTotal) )
			}
			else
			{
				$("#factor").val( 0 )
				$("#spanMontoAF").html('').html('NO APLICA')
			}
	})
}