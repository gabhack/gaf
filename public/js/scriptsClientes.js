$('.datepicker').datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
	toggleActive: true,
	language: "es"
});

//FUNCIONES PARA REGISTROS FINANCIEROS
function addRowRegistros() {
	var content_div = document.getElementById("content-registros");
	var cont_childs = content_div.childElementCount;

	const div = document.createElement('div');
	div.className = 'form-row d-inline m-0';

	div.innerHTML = `
		<div class="panel panel-primary">
			<div class="panel-heading font-weight-bold">
				<span class="text-panel-heading"> Registro #`+ (cont_childs+1) +`</span>
				<a class="btn btn-link eliminar-registro" type="button" value="-" onclick="removeRowRegistros(this)">
					<i class="fa fa-trash"></i>
				</a>
			</div>
			<div class="panel-body">
				<div class="form-group col-md-12">
					<input required placeholder="Periodo (Ej. 202001)" type="number" id="input_periodo_`+ cont_childs +`" name="periodo[`+ cont_childs +`]">
				</div>
			</div>
		</div>
	`;
	
	if (cont_childs <= 30) {
		document.getElementById('content-registros').appendChild(div);
	} else {
		alert('No se puede agregar más de 30 registros.');
	}
}

function removeRowRegistros(input) {
	document.getElementById('content-registros').removeChild(input.parentNode.parentNode.parentNode);
	reloadRowsRegistros();  
}

function reloadRowsRegistros() {
	var content_div = document.getElementById("content-registros");
	var content_childs = [].slice.call(content_div.children);

	for (var i = 0; i < content_childs.length; i++) {
		var elemento = [].slice.call(content_childs[i].children[0].children);

		console.log(elemento);

		//Label
		elemento[0].children[0].innerHTML = `Registro #`+ (i+1);
		//Periodo Input
		$(elemento[1].children[0].children[0]).attr("id", `input_periodo_`+i);
		$(elemento[1].children[0].children[0]).attr("name", `periodo[`+ i +`]` );

		console.log(elemento[1].children[0].children[0]);
	}
}

//FUNCIONES PARA DESCUENTOS NO APLICADOS
function addRowDescNoAplicado() {
	var content_div = document.getElementById("content-desc-no-aplicados");
	var cont_childs = content_div.childElementCount;

	const div = document.createElement('tr');
	div.className = 'row_desc_no_aplicados';

	div.innerHTML = `
		<th scope="row">`+ (cont_childs+1) +`</th>
		<td><input class="form-control" type="text" name="desc_na_cod_concepto[0][`+ (cont_childs+1) +`]" id="desc_na_cod_concepto_`+ (cont_childs+1) +`" placeholder="Opcional"></td>
		<td><input class="form-control" type="text" name="desc_na_concepto[0][`+ (cont_childs+1) +`]" id="desc_na_concepto_`+ (cont_childs+1) +`" required></td>
		<td><input class="form-control" type="number" name="desc_na_periodo[0][`+ (cont_childs+1) +`]" id="desc_na_periodo_`+ (cont_childs+1) +`" placeholder="Ej: 201907" required></td>
		<td>
			<select class="form-control" name="desc_na_pagaduria[0][`+ (cont_childs+1) +`]" id="desc_na_pagaduria_`+ (cont_childs+1) +`" required>
				<option value="" selected disabled hidden>Seleccione una</option>
				`+ htmlpagadurias +`
			</select>
		</td>
		<td><input class="form-control" type="text" name="desc_na_inconsistencia[0][`+ (cont_childs+1) +`]" id="desc_na_inconsistencia_`+ (cont_childs+1) +`" required></td>
		<td style="width: 15%;"><input class="form-control" type="number" name="desc_na_valor_fijo[0][`+ (cont_childs+1) +`]" id="desc_na_valor_fijo_`+ (cont_childs+1) +`" required></td>
		<td style="width: 15%;"><input class="form-control" type="number" name="desc_na_valor_total[0][`+ (cont_childs+1) +`]" id="desc_na_valor_total_`+ (cont_childs+1) +`" placeholder="Opcional"></td>
		<td style="width: 15%;"><input class="form-control" type="number" name="desc_na_saldo[0][`+ (cont_childs+1) +`]" id="desc_na_saldo_`+ (cont_childs+1) +`" placeholder="Opcional"></td>
		<td style="width: 5%;">
			<a class="btn btn-link eliminar-registro" type="button" value="-" onclick="removeRowDescNoAplicado(this)">
				<i class="fa fa-trash"></i>
			</a>
		</td>
	`;
	
	if (cont_childs <= 30) {
		document.getElementById('content-desc-no-aplicados').appendChild(div);
	} else {
		alert('No se puede agregar más de 30 descuentos.');
	}
}

function removeRowDescNoAplicado(input) {
	document.getElementById('content-desc-no-aplicados').removeChild(input.parentNode.parentNode);
	reloadRowsDescNoAplicado();
}

function reloadRowsDescNoAplicado() {
	var content_div = document.getElementById("content-desc-no-aplicados");
	var content_childs = [].slice.call(content_div.children);

	for (var i = 0; i < content_childs.length; i++) {
		var elemento = [].slice.call(content_childs[i].children);

		//Id
		elemento[0].innerHTML = (i+1);
		//Cod. Concepto
		$(elemento[1].children[0]).attr("id", `desc_na_cod_concepto_`+(i+1));
		$(elemento[1].children[0]).attr("name", `desc_na_cod_concepto[0][`+ (i+1) +`]` );
		//Concepto
		$(elemento[2].children[0]).attr("id", `desc_na_concepto_`+(i+1));
		$(elemento[2].children[0]).attr("name", `desc_na_concepto[0][`+ (i+1) +`]` );
		//Periodo
		$(elemento[3].children[0]).attr("id", `desc_na_periodo_`+(i+1));
		$(elemento[3].children[0]).attr("name", `desc_na_periodo[0][`+ (i+1) +`]` );
		//Pagaduría
		$(elemento[4].children[0]).attr("id", `desc_na_pagaduria_`+(i+1));
		$(elemento[4].children[0]).attr("name", `desc_na_pagaduria[0][`+ (i+1) +`]` );
		//Inconsistencia
		$(elemento[5].children[0]).attr("id", `desc_na_inconsistencia_`+(i+1));
		$(elemento[5].children[0]).attr("name", `desc_na_inconsistencia[0][`+ (i+1) +`]` );
		//Valor fijo
		$(elemento[6].children[0]).attr("id", `desc_na_valor_fijo_`+(i+1));
		$(elemento[6].children[0]).attr("name", `desc_na_valor_fijo[0][`+ (i+1) +`]` );
		//Valor total
		$(elemento[7].children[0]).attr("id", `desc_na_valor_total_`+(i+1));
		$(elemento[7].children[0]).attr("name", `desc_na_valor_total[0][`+ (i+1) +`]` );
		//Saldo
		$(elemento[8].children[0]).attr("id", `desc_na_saldo_`+(i+1));
		$(elemento[8].children[0]).attr("name", `desc_na_saldo[0][`+ (i+1) +`]` );
	}
}

//FUNCIONES PARA INGRESOS APLICADOS EN REGISTROS
function addRowsIngresosWithData(ingresos) {
	var content_div = document.getElementById("content-ingr-aplicados");
	content_div.innerHTML = '';
	var cont_childs = content_div.childElementCount;

	ingresos.forEach(ingreso => {
		const div = document.createElement('tr');
		div.className = 'row_ingr_aplicados';

		div.innerHTML = `
			<th scope="row">`+ (cont_childs+1) +`</th>
			<td style="width: 25%;"><input class="form-control" type="text" name="ingr_cod_concepto[0][`+ (cont_childs+1) +`]" id="ingr_cod_concepto_0_`+ (cont_childs+1) +`" placeholder="Opcional"></ value="`+ ingreso.cod_concepto +`"td>
			<td style="width: 40%;"><input class="form-control" type="text" name="ingr_concepto[0][`+ (cont_childs+1) +`]" id="ingr_concepto_0_`+ (cont_childs+1) +`" required value="`+ ingreso.concepto +`"></td>
			<td style="width: 30%;"><input class="form-control" type="number" name="ingr_valor[0][`+ (cont_childs+1) +`]" id="ingr_valor_0_`+ (cont_childs+1) +`" required value="`+ ingreso.valor +`"></td>
			<td style="width: 5%;">
				<a class="btn btn-link eliminar-registro" type="button" value="-" onclick="removeRowIngresos(this)">
					<i class="fa fa-trash"></i>
				</a>
			</td>
		`;

		document.getElementById('content-ingr-aplicados').appendChild(div);
	});
	reloadRowsIngresos();
}

function addRowIngresos() {
	var content_div = document.getElementById("content-ingr-aplicados");
	var cont_childs = content_div.childElementCount;

	const div = document.createElement('tr');
	div.className = 'row_ingr_aplicados';

	div.innerHTML = `
		<th scope="row">`+ (cont_childs+1) +`</th>
		<td style="width: 25%;"><input class="form-control" type="text" name="ingr_cod_concepto[0][`+ (cont_childs+1) +`]" id="ingr_cod_concepto_0_`+ (cont_childs+1) +`" placeholder="Opcional"></td>
		<td style="width: 40%;"><input class="form-control" type="text" name="ingr_concepto[0][`+ (cont_childs+1) +`]" id="ingr_concepto_0_`+ (cont_childs+1) +`" required></td>
		<td style="width: 30%;"><input class="form-control" type="number" name="ingr_valor[0][`+ (cont_childs+1) +`]" id="ingr_valor_0_`+ (cont_childs+1) +`" required></td>
		<td style="width: 5%;">
			<a class="btn btn-link eliminar-registro" type="button" value="-" onclick="removeRowIngresos(this)">
				<i class="fa fa-trash"></i>
			</a>
		</td>
	`;
	
	if (cont_childs <= 30) {
		document.getElementById('content-ingr-aplicados').appendChild(div);
	} else {
		alert('No se puede agregar más de 30 descuentos.');
	}
}

function removeRowIngresos(input) {
	document.getElementById('content-ingr-aplicados').removeChild(input.parentNode.parentNode);
	reloadRowsIngresos();  
}

function reloadRowsIngresos() {
	var content_div = document.getElementById("content-ingr-aplicados");
	var content_childs = [].slice.call(content_div.children);

	for (var i = 0; i < content_childs.length; i++) {
		var elemento = [].slice.call(content_childs[i].children);

		//Id
		elemento[0].innerHTML = (i+1);
		//Cod. Concepto
		$(elemento[1].children[0]).attr("id", `ingr_cod_concepto_0_`+(i+1));
		$(elemento[1].children[0]).attr("name", `ingr_cod_concepto[0][`+ (i+1) +`]` );
		//Concepto
		$(elemento[2].children[0]).attr("id", `ingr_concepto_0_`+(i+1));
		$(elemento[2].children[0]).attr("name", `ingr_concepto[0][`+ (i+1) +`]` );
		//Valor
		$(elemento[3].children[0]).attr("id", `ingr_valor_0_`+(i+1));
		$(elemento[3].children[0]).attr("name", `ingr_valor[0][`+ (i+1) +`]` );
	}
}

//FUNCIONES PARA DESCUENTOS APLICADOS EN REGISTROS
function addRowsDescuentosWithData(egresos) {
	var content_div = document.getElementById("content-desc-aplicados");
	content_div.innerHTML = '';
	var cont_childs = content_div.childElementCount;

	egresos.forEach(egreso => {
		const div = document.createElement('tr');
		div.className = 'row_desc_aplicados';

		div.innerHTML = `
			<th scope="row">`+ (cont_childs+1) +`</th>
			<td style="width: 25%;"><input class="form-control" type="text" name="desc_cod_concepto[0][`+ (cont_childs+1) +`]" id="desc_cod_concepto_0_`+ (cont_childs+1) +`" placeholder="Opcional"></ value="`+ egreso.cod_concepto +`"td>
			<td style="width: 40%;"><input class="form-control" type="text" name="desc_concepto[0][`+ (cont_childs+1) +`]" id="desc_concepto_0_`+ (cont_childs+1) +`" required value="`+ egreso.concepto +`"></td>
			<td style="width: 30%;"><input class="form-control" type="number" name="desc_valor[0][`+ (cont_childs+1) +`]" id="desc_valor_0_`+ (cont_childs+1) +`" required value="`+ egreso.valor +`"></td>
			<td style="width: 5%;">
				<a class="btn btn-link eliminar-registro" type="button" value="-" onclick="removeRowDescuentos(this)">
					<i class="fa fa-trash"></i>
				</a>
			</td>
		`;

		document.getElementById('content-desc-aplicados').appendChild(div);		
	});
	reloadRowsDescuentos();
}

function addRowDescuentos() {
	var content_div = document.getElementById("content-desc-aplicados");
	var cont_childs = content_div.childElementCount;

	const div = document.createElement('tr');
	div.className = 'row_desc_aplicados';

	div.innerHTML = `
		<th scope="row">`+ (cont_childs+1) +`</th>
		<td style="width: 25%;"><input class="form-control" type="text" name="desc_cod_concepto[0][`+ (cont_childs+1) +`]" id="desc_cod_concepto_0_`+ (cont_childs+1) +`" placeholder="Opcional"></td>
		<td style="width: 40%;"><input class="form-control" type="text" name="desc_concepto[0][`+ (cont_childs+1) +`]" id="desc_concepto_0_`+ (cont_childs+1) +`" required></td>
		<td style="width: 30%;"><input class="form-control" type="number" name="desc_valor[0][`+ (cont_childs+1) +`]" id="desc_valor_0_`+ (cont_childs+1) +`" required></td>
		<td style="width: 5%;">
			<a class="btn btn-link eliminar-registro" type="button" value="-" onclick="removeRowDescuentos(this)">
				<i class="fa fa-trash"></i>
			</a>
		</td>
	`;
	
	if (cont_childs <= 30) {
		document.getElementById('content-desc-aplicados').appendChild(div);
	} else {
		alert('No se puede agregar más de 30 descuentos.');
	}
}

function removeRowDescuentos(input) {
	document.getElementById('content-desc-aplicados').removeChild(input.parentNode.parentNode);
	reloadRowsDescuentos();
}

function reloadRowsDescuentos() {
	var content_div = document.getElementById("content-desc-aplicados");
	var content_childs = [].slice.call(content_div.children);

	for (var i = 0; i < content_childs.length; i++) {
		var elemento = [].slice.call(content_childs[i].children);

		//Id
		elemento[0].innerHTML = (i+1);
		//Cod. Concepto
		$(elemento[1].children[0]).attr("id", `desc_cod_concepto_0_`+(i+1));
		$(elemento[1].children[0]).attr("name", `desc_cod_concepto[0][`+ (i+1) +`]` );
		//Concepto
		$(elemento[2].children[0]).attr("id", `desc_concepto_0_`+(i+1));
		$(elemento[2].children[0]).attr("name", `desc_concepto[0][`+ (i+1) +`]` );
		//Valor
		$(elemento[3].children[0]).attr("id", `desc_valor_0_`+(i+1));
		$(elemento[3].children[0]).attr("name", `desc_valor[0][`+ (i+1) +`]` );
	}
}

function getPagaduriasXPeriodo(idcliente) {
	var periodo = document.getElementById("input_periodo").value;
	var select_pagaduria = document.getElementById("pagaduria_select");
	var content_childs = [].slice.call(select_pagaduria.children);

	//Show Pagaduría select
	if (periodo !== null) {
		select_pagaduria.disabled = false;
		document.getElementById("pagaduria_select").value = '';
		//
		document.getElementById("btn-submit-registros").disabled = true;
		//
		document.getElementById("btn-submit-registros").disabled = true;
		//
		document.getElementById("btn-add-ingr").disabled = true;
		document.getElementById("btn-add-desc").disabled = true;
		//
		document.getElementById("content-ingr-aplicados").innerHTML = '';
		//
		document.getElementById("content-desc-aplicados").innerHTML = '';
		//
	}

	//Hide All
	for (let i = 0; i < content_childs.length; i++) {
		var elemento = content_childs[i];
		elemento.hidden = true;
		elemento.disabled = true;
	}

	$.post('api/clientes/getPagaduriasXPeriodo', {'periodo' : periodo, 'idcliente' : idcliente}, function(data){
		for (let i = 0; i < data.length; i++) {
			var elemento = document.getElementById("option_pag_" + data[i]);
			elemento.hidden = false;
			elemento.disabled = false;
		}
	});
}

function getRegistrosXPagaduriayPeriodo(idcliente) {
	var periodo = document.getElementById("input_periodo").value;
	var pagaduria_select = document.getElementById("pagaduria_select").value;
	$.post('api/clientes/getRegistrosXPagaduriayPeriodo', {'periodo' : periodo, 'pagaduria_select' : pagaduria_select, 'idcliente' : idcliente}, function(data){
		addRowsIngresosWithData(data.ingresos);
		addRowsDescuentosWithData(data.egresos);
		//
		var submit_btn = document.getElementById("btn-submit-registros");
		submit_btn.disabled = false;
		//
		document.getElementById("btn-add-ingr").disabled = false;
		document.getElementById("btn-add-desc").disabled = false;
	});
}

$("#form-registros").submit(function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var form = $(this);
    var url = form.attr('action');
    $.ajax({
		type: "POST",
		url: url,
		data: form.serialize(), // serializes the form's elements.
		success: function(data)
		{
			alert(data); // show response from the php script.
			limpiarSeccionRegistros();
		},
		error: function(data)
		{
			alert('No se pudo actualizar el registro: ' + data);
		}
    });
});

function limpiarSeccionRegistros() {
	//
	document.getElementById("btn-submit-registros").disabled = true;
	//
	document.getElementById("btn-submit-registros").disabled = true;
	//
	document.getElementById("input_periodo").value = '';
	//
	document.getElementById("pagaduria_select").value = '';
	document.getElementById("pagaduria_select").disabled = true;
	//
	document.getElementById("btn-add-ingr").disabled = true;
	document.getElementById("btn-add-desc").disabled = true;
	//
	document.getElementById("content-ingr-aplicados").innerHTML = '';
	//
	document.getElementById("content-desc-aplicados").innerHTML = '';
	//
}