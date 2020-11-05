var grid;

var data = dataDB;

var IDfilaTotales;
var tipoCliente; //A,AA,AAA,B1,B2,B3,C

var inputJsonCarteras;

//Campos del front que se requieren cambiar en tiempo real
var spantipocliente = $('span#valor-tipo-cliente');
var inputcosto_certificadosTR = document.getElementById("costo_certificados");
var selectcostoservicio = document.getElementById('costo_servicio_tr_ptg');
var inputcarteras_comprarTR = $('input#carteras_comprar');
var inputtotal_servicioTR = $('input#total_servicio');
var inputservicio_impuestosTR = $('input#servicio_impuestos');

var inputaliadof1 = document.getElementById("aliadof1");
var inputAF1_tasa = document.getElementById("AF1_tasa");
var inputAF1_plazo = document.getElementById("AF1_plazo");
var inputAF1_costos = document.getElementById("AF1_costos");
var inputAF1_intereses_anticipados = document.getElementById("AF1_intereses_anticipados");
var inputAF1_saldo_refinanciacion = document.getElementById("AF1_saldo_refinanciacion");
var inputAF1_cuota_mensual = document.getElementById("AF1_cuota_mensual");
var inputcarteras_a_comprar_af1 = $('input#carteras_a_comprar_af1');
var inputAF1_costos_valor = $('input#AF1_costos_valor');
var inputAF1_intereses_anticipados_valor = $('input#AF1_intereses_anticipados_valor');
var inputAF1_seguro = $('input#AF1_seguro');
var inputAF1_GMF = $('input#AF1_GMF');
var inputAF1_iva = $('input#AF1_iva');
var inputAF1_valor_credito = $('input#AF1_valor_credito');
var inputAF1_cupo_max = $('input#AF1_cupo_max');
var inputAF1_cuota = $('input#AF1_cuota');

var inputaliadof2 = document.getElementById("aliadof2");
var inputAF2_factor_x_millon = document.getElementById("AF2_factor_x_millon");
var inputAF2_factor_x_millon_valor = $('input#AF2_factor_x_millon');
var inputAF2_plazo = document.getElementById("AF2_plazo");
var inputcarteras_a_comprar_af2 = $('input#carteras_a_comprar_af2');
var inputAF2_cuota = document.getElementById("AF2_cuota");
var inputAF2_cupo_max = $('input#AF2_cupo_max');
var inputAF2_monto_prestar = $('input#AF2_monto_prestar');
var inputAF2_monto_max = $('input#AF2_monto_max');
var inputAF2_saldo = $('input#AF2_saldo');

const dataEstructura = {
  ID: "0",
  EnDesprendible: false,
  Entidad: "",
  SoloEfectivo: false,
  Data: "",
  Cifin: "",
  Estado: "",
  CompraAF1: "",
  CompraAF2: "",
  CalificacionWAB: "",
  Cuota: 0,
  SaldoCarteraCentrales: 0,
  VlrInicioNegociacion: 0,
  DescuentoLogrado: 0,
  SaldoCarteraNegociada: 0,
  PctjeNegociacion: 0,
  FechaVencimiento: ""
};

//Funciones auxiliares
borrarElementoData = elemID => data.filter(e => e.ID !== elemID);

/**
 * Se asume que cuando se ejecuta calcular totales en 'data', no se encuentra la fila totales
 */
calcularTotales = () => {
  let filaTotalesCalculada;
  let es_saneamiento = false;
  let cantCarteras = 0;
  let cantCarterasEnMora = 0;
  let cantCarterasSoloEfectivo = 0;
  let cantCarterasCastigadas = 0;
  let cantEmbargos = 0;
  //
  let cuotasDuplicadas = 0;
  //
  let carterasTR = 0;
  let costoservicioTR = 0;
  var costoservicioTR_min = 0;
  var costoservicioTR_max = 0;
  //
  let totalCarteraAF1 = 0;
  let totalCarteraAF2 = 0;
  let totalCarteraSinComprar = 0;
  //
  let cuotaCarteraAF1 = 0;
  let cuotaCarteraAF2 = 0;
  let cuotaCarteraSinComprar = 0;

  filaTotalesCalculada = data.reduce(
    (acum, currentValue, index, arr) => {
      //CÁLCULO DE TOTALES
      //Le asigna el último ID disponible
      if (index === arr.length - 1) {
        acum.ID = `${parseInt(currentValue.ID) + 1}`;
        IDfilaTotales = acum.ID;
      }

      acum.Cuota += parseInt(currentValue.Cuota);
      acum.SaldoCarteraCentrales += parseInt(currentValue.SaldoCarteraCentrales);
      acum.VlrInicioNegociacion += parseInt(currentValue.VlrInicioNegociacion);
      acum.DescuentoLogrado += parseInt(currentValue.DescuentoLogrado);
      acum.SaldoCarteraNegociada += parseInt(currentValue.SaldoCarteraNegociada);

      //CÁLCULO DE INFORMACIÓN ADICIONAL
      if (!es_saneamiento) {
        es_saneamiento =
          (
            currentValue.Estado === "MORA 60 A 90" || 
            currentValue.Estado === "MORA 90 A 120" || 
            currentValue.Estado === "MORA 120 A 150" || 
            currentValue.Estado === "MORA 150 A 180" || 
            currentValue.Estado === "MORA > 180" ||
            currentValue.Estado === "CASTIGADA" ||
            currentValue.Estado === "EMBARGO"
          )
            ? true
            : false;
      }
      cantCarteras = arr.length;
      cantCarterasSoloEfectivo = currentValue.SoloEfectivo
        ? cantCarterasSoloEfectivo + 1
        : cantCarterasSoloEfectivo;
      cantCarterasEnMora = currentValue.Estado.includes("MORA")
        ? cantCarterasEnMora + 1
        : cantCarterasEnMora;
      cantCarterasCastigadas =
        currentValue.Estado === "CASTIGADA"
          ? cantCarterasCastigadas + 1
          : cantCarterasCastigadas;
      cantEmbargos =
        currentValue.Estado === "EMBARGO" ? cantEmbargos + 1 : cantEmbargos;
      //
      cuotasDuplicadas = 
        currentValue.EnDesprendible === true ? parseInt(cuotasDuplicadas) + parseInt(currentValue.Cuota) : parseInt(cuotasDuplicadas);
      //
      totalCarteraAF1 =
        currentValue.CompraAF1 === "SI" ? parseInt(totalCarteraAF1) + parseInt(currentValue.SaldoCarteraNegociada) : parseInt(totalCarteraAF1);
      totalCarteraAF2 =
        currentValue.CompraAF2 === "SI" ? parseInt(totalCarteraAF2) + parseInt(currentValue.SaldoCarteraNegociada) : parseInt(totalCarteraAF2);
      totalCarteraSinComprar =
        (currentValue.CompraAF1 === "NO" && currentValue.CompraAF2 === "NO") ? parseInt(totalCarteraSinComprar) + parseInt(currentValue.SaldoCarteraNegociada) : parseInt(totalCarteraSinComprar);
      //
      cuotaCarteraAF1 =
        currentValue.CompraAF1 === "SI" ? parseInt(cuotaCarteraAF1) + parseInt(currentValue.Cuota) : parseInt(cuotaCarteraAF1);
      cuotaCarteraAF2 =
        currentValue.CompraAF2 === "SI" ? parseInt(cuotaCarteraAF2) + parseInt(currentValue.Cuota) : parseInt(cuotaCarteraAF2);
      cuotaCarteraSinComprar =
        (currentValue.CompraAF1 === "NO" && currentValue.CompraAF2 === "NO") ? parseInt(cuotaCarteraSinComprar) + parseInt(currentValue.Cuota) : parseInt(cuotaCarteraSinComprar);
        return acum;
    },
    { ...dataEstructura }
  );

  //Cálculo de tipo de cliente
  // Cliente tipo A   : No tiene NINGUNA deuda
  // Cliente tipo AA  : TIENE deudas, NINGUNA en mora y en NINGUNA deuda manejan solo efectivo
  // Cliente tipo AAA : TIENE deudas, NINGUNA en mora y al menos UNA deuda SOLO maneja efectivo
  // Cliente tipo B1  : TIENE deudas, SOLO UNA está en mora
  // Cliente tipo B2  : TIENE deudas, MAS DE UNA está en mora
  // Cliente tipo B3  : TIENE deudas, MAS DE UNA está en mora y SOLO UNA en cartera castigada
  // Cliente tipo C   : TIENE deudas, MÁS DE UNA en cartera castigada...   O...  AL MENOS UN embargo
  if (cantCarteras === 0) {
    tipoCliente = "A";
  } else {
    if (cantCarterasCastigadas >= 1 || cantEmbargos >= 1) {
      tipoCliente = "C";
    } else {
      if (cantCarterasEnMora === 0 && cantCarterasCastigadas === 0) {
          tipoCliente = "A";
      } else {
        tipoCliente = "B";
      }
    }
  }

  //Aliados:
  if (totalCarteraAF1 > 0) {
    carterasTR = totalCarteraAF1;
  } else {
    carterasTR = totalCarteraAF2;
  }

  //Extraer los valores de servicio dependiendo del tipo de cliente
  tiposcliente.forEach(function(entry) {
    if (entry.tipo === tipoCliente) {
      costoservicioTR_min = parseFloat(entry.range_min).toFixed(0);
      costoservicioTR_max = parseFloat(entry.range_max).toFixed(0);
    }
  }, this);
  //Actualización de opciones de costos de servicios
  var length = selectcostoservicio.options.length;
  for (i = 1; i < length; i++) {
    if (parseInt(selectcostoservicio.options[i].text , 10) >= costoservicioTR_min && parseInt(selectcostoservicio.options[i].text , 10) <= costoservicioTR_max) {
      selectcostoservicio.options[i].disabled = false;
    } else {
      selectcostoservicio.options[i].disabled = true;
    }
  }
  if (selectcostoservicio.value !== '') { 
    if (document.getElementById('costo_s_tr_option_'+selectcostoservicio.value).disabled) {
      selectcostoservicio.value = '';
      costoservicioTR = 0;
    } else {
      costoservicioTR = selectcostoservicio.value;
    }
  } else {
    costoservicioTR = selectcostoservicio.value;
  }
  //Capacidad de pago
  cupolibreinversion = parseInt(cupos.libreInversion.valor);
  //TR
  costo_certificados = parseInt($('input#costo_certificados').autoNumeric('get'), 10);
  totalservicio = (carterasTR*costoservicioTR/100)+costo_certificados;
  servicioimpuestoscalc = carterasTR+(totalservicio*1.19);
  servicioimpuestos = (parseFloat(servicioimpuestoscalc).toFixed(0));
  //AF1
  let aliado_seleccionado = aliadosCompleto.find(aliado => aliado.id == inputaliadof1.value);
  if (aliado_seleccionado.aliado == 'CK COMERCIALIZADORA') {
    // Habilitar/Deshabilitar funciones del aliado
    if ( !document.getElementById("item-desembolso-cliente").classList.contains('hidden') ){
      document.getElementById("item-desembolso-cliente").classList.add('hidden');
      document.getElementById("item-cuota-mensual").classList.add('hidden');
    }
    inputAF1_cuota_mensual.required = false;
    document.getElementById("item-saldo-ref").classList.remove('hidden');
    document.getElementById("item-cuota-seguro").classList.remove('hidden');
    inputAF1_saldo_refinanciacion.required = true;
    inputAF1_tasa.required = true;
    // Cálculos
    cupomaxAF1 = cupolibreinversion-filaTotalesCalculada.Cuota+cuotaCarteraAF1+cuotasDuplicadas;
    carteras_comprar_base = servicioimpuestos;
    intereses_anticipados_af1 = parseInt(inputAF1_intereses_anticipados.value, 10);
    intereses_anticipados_af1_valor = Math.trunc(carteras_comprar_base*intereses_anticipados_af1/100);
    costos_af1 = parseInt(inputAF1_costos.value, 10);
    costos_af1_valor = Math.trunc(carteras_comprar_base*costos_af1/100);
    seguro_calc = p_x_millon*(1.+(extraprima)/100);
    seguro_af1_valor = parseFloat(((Math.trunc(servicioimpuestos)+costos_af1_valor)/1000000)*seguro_calc).toFixed(0);
    masseguro = parseInt(carteras_comprar_base, 10)+parseInt(costos_af1_valor, 10)+parseInt(seguro_af1_valor, 10);
    cuatroxmil = parseFloat((4/1000)*carteras_comprar_base).toFixed(0);
    ivacalc = parseInt(costos_af1_valor*(0.+iva)/100, 10);
    if (totalCarteraAF1 === 0) {
      valorcreditocalc = 0;
    } else {
      valorcreditocalc = parseInt(servicioimpuestos)+parseInt(costos_af1_valor)+parseInt(inputAF1_saldo_refinanciacion.value, 10)+parseInt(cuatroxmil)+parseInt(ivacalc);
    }
    tasa_af1 = parseFloat(inputAF1_tasa.value);
    periodos_af1 = parseInt(inputAF1_plazo.value);
    seguro_cuota = parseInt(seguro_calc*(valorcreditocalc/1000000));
    cuotacalc = getValorDeCuotaFija(valorcreditocalc,tasa_af1,periodos_af1);
    cuotafinal = (parseInt(cuotacalc)+seguro_cuota).toFixed(0);
  } else {
    // Habilitar/Deshabilitar funciones del aliado
    if ( !document.getElementById("item-saldo-ref").classList.contains('hidden') ){
      document.getElementById("item-saldo-ref").classList.add('hidden');
      document.getElementById("item-cuota-seguro").classList.add('hidden');
      document.getElementById("item-cuota-seguro").classList.add('hidden');
    }
    inputAF1_saldo_refinanciacion.required = false;
    inputAF1_tasa.required = false;
    document.getElementById("item-desembolso-cliente").classList.remove('hidden');
    document.getElementById("item-cuota-mensual").classList.remove('hidden');
    inputAF1_cuota_mensual.required = true;
    // Cálculos
    cupomaxAF1 = cupolibreinversion-filaTotalesCalculada.Cuota+cuotaCarteraAF1+cuotasDuplicadas;
    if ($('input#AF1_cuota_mensual').autoNumeric('get') > cupomaxAF1) {
      if (parseInt(cupomaxAF1) > 0) {
        $('input#AF1_cuota_mensual').autoNumeric('set', cupomaxAF1);
      } else {
        $('input#AF1_cuota_mensual').autoNumeric('set', 0);
      }
    }
    carteras_comprar_base = servicioimpuestos;
    intereses_anticipados_af1 = parseFloat(inputAF1_intereses_anticipados.value);
    cuota_mensual_af1 = parseInt($('input#AF1_cuota_mensual').autoNumeric('get'), 10);
    periodos_af1 = parseInt(inputAF1_plazo.value);
    periodos = Math.round(periodos_af1/12)*12;
    costos_af1 = parseInt(inputAF1_costos.value, 10);
    seguro_calc = p_x_millon*(1.+(extraprima)/100);
    if (totalCarteraAF1 === 0) {
      valorcreditocalc = 0;
    } else {
      valorcreditocalc = parseInt(cuota_mensual_af1/factores_x_millon_kredit[periodos]);
    }
    costos_af1_valor = Math.trunc(valorcreditocalc*(costos_af1/100));
    intereses_anticipados_af1_valor = Math.trunc(valorcreditocalc*(intereses_anticipados_af1/100));
    ivacalc = parseInt(costos_af1_valor*(0.+iva)/100, 10);
    cuatroxmil = parseFloat((4/1000)*valorcreditocalc).toFixed(0);
    costos_total = parseInt(costos_af1_valor)+parseInt(intereses_anticipados_af1_valor)+parseInt(ivacalc)+parseInt(cuatroxmil);
    seguro_cuota = parseInt(seguro_calc*(valorcreditocalc/1000000));
    if (valorcreditocalc === 0) {
      cuotafinal = 0;
    } else {
      cuotafinal = parseInt(valorcreditocalc)-parseInt(carteras_comprar_base)-parseInt(costos_total);
    }
  }
  //AF2
  let aliado_seleccionado2 = aliadosCompleto.find(aliado => aliado.id == inputaliadof2.value);
  af2_factor_x_millon = 0;
  pagaduria_text = 'ACTIVOS';
  if (pagaduria == 'COLPENSIONES' || pagaduria == 'FIDUPREVISORA' || pagaduria == 'FOPEP' || pagaduria == 'CASUR' || pagaduria == 'CREMIL') {
    pagaduria_text = pagaduria;
  }
  if (aliado_seleccionado2.aliado == 'GNB SUDAMERIS') {
    inputAF2_factor_x_millon.readOnly = true;
    if (es_saneamiento) {
      //FRONT
      document.getElementById("label-saneamiento").classList.remove('hidden');
      //DATA
      af2_factor_x_millon = factores_x_millon_gnb[pagaduria_text][inputAF2_plazo.value]['saneamiento'];
    } else {
      //FRONT
      if ( !document.getElementById("label-saneamiento").classList.contains('hidden') ){
        document.getElementById("label-saneamiento").classList.add('hidden');
      }
      //DATA
      af2_factor_x_millon = factores_x_millon_gnb[pagaduria_text][inputAF2_plazo.value]['normal'];
    }
  } else {
    inputAF2_factor_x_millon.readOnly = false;
  }
  total_carteras_aliado_2 = (parseFloat(totalCarteraAF2+servicioimpuestoscalc)).toFixed(0);
  cupomaxAF2 = cupomaxAF1+cuotaCarteraAF2;
  monto_maxAF2 = (parseInt($('input#AF2_cuota').autoNumeric('get'))/parseFloat(af2_factor_x_millon)).toFixed(0);
  monto_prestarAF2 = valorcreditocalc+totalCarteraAF2;
  saldo_AF2 = monto_maxAF2-monto_prestarAF2;

  //Asignación de variables en el frontend
  //TR
  spantipocliente.text(tipoCliente);
  inputcarteras_comprarTR.val(moneyFormatter.format(carterasTR));
  inputtotal_servicioTR.val(moneyFormatter.format(totalservicio));
  inputservicio_impuestosTR.val(moneyFormatter.format(servicioimpuestos));
  //AF1
  inputcarteras_a_comprar_af1.val(moneyFormatter.format(servicioimpuestos));
  inputAF1_intereses_anticipados_valor.val(moneyFormatter.format(intereses_anticipados_af1_valor));
  inputAF1_costos_valor.val(moneyFormatter.format(costos_af1_valor));
  inputAF1_seguro.val(moneyFormatter.format(seguro_cuota));
  inputAF1_GMF.val(moneyFormatter.format(cuatroxmil));
  inputAF1_iva.val(moneyFormatter.format(ivacalc));
  inputAF1_valor_credito.val(moneyFormatter.format(valorcreditocalc));
  inputAF1_cupo_max.val(moneyFormatter.format(cupomaxAF1));
  inputAF1_cuota.val(moneyFormatter.format(cuotafinal));
  //AF2
  inputcarteras_a_comprar_af2.val(moneyFormatter.format(total_carteras_aliado_2));
  inputAF2_cupo_max.val(moneyFormatter.format(cupomaxAF2));
  inputAF2_monto_prestar.val(moneyFormatter.format(monto_prestarAF2));
  inputAF2_monto_max.val(moneyFormatter.format(monto_maxAF2));
  inputAF2_saldo.val(moneyFormatter.format(saldo_AF2));
  inputAF2_factor_x_millon_valor.val(af2_factor_x_millon);
  
  /////// Mostrar y ocultar datos y panales
  //Panel TR
  if (totalCarteraSinComprar === filaTotalesCalculada.SaldoCarteraNegociada) {
    //Ocultar
		if ( !document.getElementById("panel-aliados").classList.contains('hidden') ){
			document.getElementById("panel-aliados").classList.add('hidden');
		}
  } else {
    document.getElementById("panel-aliados").classList.remove('hidden');
  }
  //Panel aliados
  if (cantCarteras === 0) {
    //Ocultar
		if ( !document.getElementById("panel_tr").classList.contains('hidden') ){
			document.getElementById("panel_tr").classList.add('hidden');
      document.getElementById("costo_servicio_tr_ptg").required = false;
		}
  } else {
    document.getElementById("panel_tr").classList.remove('hidden');
    document.getElementById("costo_servicio_tr_ptg").required = true;
  }
  //Aliado 1
  if (totalCarteraAF1 > 0) {
    document.getElementById("panel-AF1").classList.remove('hidden');
    document.getElementById("aliadof1").required = true;
    document.getElementById("AF1_tasa").required = true;
    document.getElementById("AF1_plazo").required = true;
    document.getElementById("AF1_costos").required = true;
  } else {
    //Ocultar
		if ( !document.getElementById("panel-AF1").classList.contains('hidden') ){
			document.getElementById("panel-AF1").classList.add('hidden');
    }
    document.getElementById("aliadof1").required = false;
    document.getElementById("AF1_tasa").required = false;
    document.getElementById("AF1_plazo").required = false;
    document.getElementById("AF1_costos").required = false;
  }
  document.getElementById("AF1_cuota").classList.remove('input-verde');
  document.getElementById("AF1_cuota").classList.remove('input-amarillo');
  document.getElementById("AF1_cuota").classList.remove('input-rojo');
  if (aliado_seleccionado.aliado == 'CK COMERCIALIZADORA') {
    if (cuotafinal === cupomaxAF1) {
      document.getElementById("AF1_cuota").classList.add('input-amarillo');
    } else if (cuotafinal < cupomaxAF1) {
      document.getElementById("AF1_cuota").classList.add('input-verde');
    } else if (cuotafinal > cupomaxAF1) {
      document.getElementById("AF1_cuota").classList.add('input-rojo');
    }
  } else {
    if (cuotafinal === 0) {
      document.getElementById("AF1_cuota").classList.add('input-amarillo');
    } else if (cuotafinal > 0) {
      document.getElementById("AF1_cuota").classList.add('input-verde');
    } else if (cuotafinal < 0) {
      document.getElementById("AF1_cuota").classList.add('input-rojo');
    }
  }
  //Aliado 2
  if (totalCarteraAF1 > 0 || totalCarteraAF2 > 0) {
    document.getElementById("panel-AF2").classList.remove('hidden');
    document.getElementById("aliadof2").required = true;
    document.getElementById("AF2_factor_x_millon").required = true;
    document.getElementById("AF2_plazo").required = true;
    document.getElementById("AF2_cuota").required = true;
  } else {
    //Ocultar
		if ( !document.getElementById("panel-AF2").classList.contains('hidden') ){
			document.getElementById("panel-AF2").classList.add('hidden');
    }
    document.getElementById("aliadof2").required = false;
    document.getElementById("AF2_factor_x_millon").required = false;
    document.getElementById("AF2_plazo").required = false;
    document.getElementById("AF2_cuota").required = false;
  }
  document.getElementById("AF2_saldo").classList.remove('input-verde');
  document.getElementById("AF2_saldo").classList.remove('input-amarillo');
  document.getElementById("AF2_saldo").classList.remove('input-rojo');
  if (saldo_AF2 === 0) {
		document.getElementById("AF2_saldo").classList.add('input-amarillo');
  } else if (saldo_AF2 > 0) {
		document.getElementById("AF2_saldo").classList.add('input-verde');
  } else if (saldo_AF2 < 0) {
		document.getElementById("AF2_saldo").classList.add('input-rojo');
  }
  
  return filaTotalesCalculada;
};

asignarSiguienteID = dataEstructura => {
  let ultimoID = data.length === 0 ? 0 : parseInt(data[data.length - 1].ID);
  dataEstructura.ID = `${ultimoID + 1}`;
  return dataEstructura;
};

eliminarTotales = (blnRenderGrid = false) => {
  data.pop(); //Elimina el último elemento, el cual siempre son los totales
  if (blnRenderGrid) grid.render();
};

refrescarTotales = () => {
  eliminarTotales();
  if (data.length !== 0) {
    //Si no hay datos para refrescar el calculo de los totales, no se pone total
    data.push(calcularTotales());
    grid.render(data);
  }
};

getValorDeCuotaFija = (monto, tasa, cuotas) => {
  tasa = tasa/100;
  valor = monto *( (tasa * Math.pow(1 + tasa, cuotas)) / (Math.pow(1 + tasa, cuotas) - 1) );
  return valor.toFixed(0);
}

//Función para cambiar los campos numéricos que quedan como string y pasarlos nuevamente a numericos
formatearCamposParaIntegridadDATA = id => {
  data.map(value => {
    if (parseInt(value.ID) === id) {
      value.Cuota = parseInt(value.Cuota);
      value.SaldoCarteraCentrales = parseInt(value.SaldoCarteraCentrales);
      value.VlrInicioNegociacion = parseInt(value.VlrInicioNegociacion);
      value.DescuentoLogrado = parseInt(value.DescuentoLogrado);
      value.SaldoCarteraNegociada = parseInt(value.SaldoCarteraNegociada);
    }
  });
};

//Opciones para los dropdown (o select)
const SI_NO_Opts = ["SI", "NO"];
const Data_Cifin_Opts = data_cifin_opts;
const EstadoOpts = estado_opts;
const CompraCKoAliadoOpts = compra_ck_o_aliado_opts;
const CalificacionWABOpts = calificacion_wab_opts;

//RENDERS
//Renders para que convertir los valores numéricos en cadenas de moneda o de tasa
const moneyFormatter = new Intl.NumberFormat("es-CO", {
  style: "currency",
  currency: "COP",
  minimumFractionDigits: 0
});

const render_convertirNumberAMoney = (value, record, $cell) => {
  $cell.css("font-style", "italic");
  return moneyFormatter.format(value);
};

const porcentajeFormatter = new Intl.NumberFormat("es-CO", {
  style: "percent",
  currency: "COP",
  minimumFractionDigits: 2
});

const render_convertirNumberAPorcentaje = (
  value,
  record,
  $cell,
  $displayEl
) => {
  //Formatear resultado
  $cell.css("font-style", "italic");
  return porcentajeFormatter.format(value);
};

//Renders para calculos de opreaciones
const render_calcularSaldoCarteraNegociada = (
  value,
  record,
  $cell,
  $displayEl,
  id
) => {
  if (id !== IDfilaTotales) {
    //Si no es una fila totales se calcula el dato
    value = record.VlrInicioNegociacion - record.DescuentoLogrado; //Cálculo del campo
    //Sincronización del value en data
    data.map(dataRow => {
      if (dataRow.ID === id) dataRow.SaldoCarteraNegociada = value;
    });
  }
  return render_convertirNumberAMoney(value, record, $cell, $displayEl);
};

const render_calcularPorcentaje = (value, record, $cell, $displayEl, id) => {
  if (id !== IDfilaTotales) {
    //Si no es una fila totales se calcula el dato
    if (record.VlrInicioNegociacion !== 0) {
      value = record.DescuentoLogrado / record.VlrInicioNegociacion; //Cálculo del campo
      data.map(dataRow => {
        if (dataRow.ID === id) dataRow.PctjeNegociacion = value;
      });
    }
    return render_convertirNumberAPorcentaje(value, record, $cell, $displayEl);
  }
};

const render_activarIcono = (value, record, $cell, $displayEl, id) => {
  if (id !== IDfilaTotales) {
    $displayEl.html(`<span class="fa fa-remove"></span>`);
  }
};

const editManager = (value, record, $cell, $displayEl, id, $grid) => {
  if (id !== IDfilaTotales) {
    let inlineBtnStyle =
      "background-color: transparent; border-color: transparent; box-shadow: none; padding: 0";
    var data = $grid.data(),
      $edit = $(
        `<button class="btn bg-transparent" type="button" style="${inlineBtnStyle}"> <span class="fa fa-pencil"></span> </button>`
      ).attr("data-key", id),
      $delete = $(
        `<button class="btn bg-transparent" type="button" style="${inlineBtnStyle}"> <span class="fa fa-remove"></span> </button>`
      ).attr("data-key", id),
      $update = $(
        `<button class="btn bg-transparent" type="button" style="${inlineBtnStyle}"> <span class="fa fa-check-circle"></span> </button>`
      )
        .attr("data-key", id)
        .hide(),
      $cancel = $(
        `<button class="btn bg-transparent" type="button" style="${inlineBtnStyle}"> <span class="fa fa-times-circle"></span> </button>`
      )
        .attr("data-key", id)
        .hide();
    $edit.on("click", function(e) {
      $grid.edit($(this).data("key"));
      $edit.hide();
      $delete.hide();
      $update.show();
      $cancel.show();
    });
    $delete.on("click", function(e) {
      // $grid.removeRow($(this).data("key"));
      deleteRow_ClickHandler($(this).data("key") + "");
    });
    $update.on("click", function(e) {
      $grid.update($(this).data("key"));
      $edit.show();
      $delete.show();
      $update.hide();
      $cancel.hide();
      refrescarTotales();
    });
    $cancel.on("click", function(e) {
      $grid.cancel($(this).data("key"));
      $edit.show();
      $delete.show();
      $update.hide();
      $cancel.hide();
    });
    $displayEl
      .empty()
      .append($edit)
      .append($delete)
      .append($update)
      .append($cancel);
  }
};
//Funciones para CRUD de la grilla
btnAgregarFila_clickHandler = () => {
  if (data.length !== 0) {
    eliminarTotales();
  }
  var nuevaFila = asignarSiguienteID({ ...dataEstructura });
  data.push(nuevaFila);
  data.push(calcularTotales());
  grid.render(data);
  inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
};

deleteRow_ClickHandler = id => {
  if (confirm("Seguro que desea eliminar esta cartera?")) {
    data = borrarElementoData(id);
    grid.removeRow(id);
    refrescarTotales();
    grid.render(data);
    inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
  }
};

//Event Handlers
rowDataChanged_handler = (e, id, record) => {
  //Se requiere llamar una función para que los campos numéricos que quedaron como string, vuelvan a estar como numéricos
  //esto ocurre porque en el grid de GIJGO no tienen un campo tipo number
  formatearCamposParaIntegridadDATA(id);

  refrescarTotales();
  inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
};

// Declaración de las columnas
const columnas = [
  {
    field: "ID",
    width: 25,
    title: "ID        ",
    mode: "ReadOnly",
    hidden: true
  },
  {
    field: "EnDesprendible",
    title: "En desp.",
    type: "checkbox",
    editor: true,
    width: 60,
    align: "center",
    tooltip: "¿Se encuentra en desprendible de pago?"
  },
  {
    field: "Entidad",
    width: 80,
    type: "text",
    title: "Entidad",
    editor: true
  },
  {
    field: "SoloEfectivo",
    title: "Efectivo",
    type: "checkbox",
    editor: true,
    width: 60,
    align: "center"
  },
  {
    field: "Data",
    type: "dropdown",
    width: 50,
    title: "DATA",
    editor: { dataSource: Data_Cifin_Opts }
  },
  {
    field: "Cifin",
    type: "dropdown",
    width: 50,
    title: "CIFIN",
    editor: { dataSource: Data_Cifin_Opts }
  },
  {
    field: "Estado",
    type: "dropdown",
    width: 60,
    title: "Estado",
    editor: { dataSource: EstadoOpts }
  },
  {
    field: "CompraAF1",
    type: "dropdown",
    width: 40,
    title: "AF1",
    editor: { dataSource: SI_NO_Opts }
  },
  {
    field: "CompraAF2",
    type: "dropdown",
    width: 40,
    title: "AF2",
    editor: { dataSource: SI_NO_Opts }
  },
  {
    field: "CalificacionWAB",
    width: 40,
    type: "dropdown",
    title: "WAB",
    editor: { dataSource: CalificacionWABOpts }
  },
  {
    field: "Cuota",
    type: "text",
    title: "Cuota",
    align: "rigth",
    renderer: render_convertirNumberAMoney,
    editor: true
  },
  {
    field: "SaldoCarteraCentrales",
    type: "text",
    title: "Saldo Cartera Centrales",
    align: "rigth",
    renderer: render_convertirNumberAMoney,
    editor: true
  },
  {
    field: "VlrInicioNegociacion",
    type: "text",
    title: "Vlr. inicio negociación",
    align: "rigth",
    renderer: render_convertirNumberAMoney,
    editor: true
  },
  {
    field: "DescuentoLogrado",
    type: "text",
    title: "Descuento logrado",
    align: "rigth",
    renderer: render_convertirNumberAMoney,
    editor: true
  },
  {
    field: "SaldoCarteraNegociada",
    type: "text",
    title: "Saldo cartera negociada",
    align: "rigth",
    renderer: render_calcularSaldoCarteraNegociada,
    mode: "readOnly"
  },
  {
    field: "PctjeNegociacion",
    editField: "PctjeNegociacion",
    width: 60,
    type: "text",
    title: "% Neg",
    renderer: render_calcularPorcentaje
  },
  {
    field: "FechaVencimiento",
    type: "date",
    title: "Fecha Ven.",
    editor: true
  },
  {
    width: 25,
    align: "center",
    renderer: editManager
  }
];

$(document).ready(function() {
  grid = $("#grid").grid({
    primaryKey: "ID",
    dataSource: data,
    uiLibrary: "bootstrap4",
    iconsLibrary: "fontawesome",
    resizableColumns: true,
    inlineEditing: { mode: "command", managementColumn: false },
    columns: columnas
  });
  grid.on("rowDataChanged", rowDataChanged_handler);

  inputJsonCarteras = $("#json_carteras");
  inputJsonCarteras.val(JSON.stringify(data));
  if (data.length !== 0) {
    //Si no hay datos no se calcula total
    data.push(calcularTotales());
  }
  grid.render(data); //Necesario para hacer un binding entre grid y data

  $("#btnAgregarFila").on("click", btnAgregarFila_clickHandler);

  $("#form_guardar").on("keyup keypress", e => {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  });

  $("input#costo_certificados").change(function() {
    inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
    refrescarTotales();
  });

  $("#costo_servicio_tr_ptg").change(function() {
    inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
    refrescarTotales();
  });

  $("#aliadof1").change(function() {
    inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
    refrescarTotales();
  });

  $("#AF1_tasa").change(function() {
    inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
    refrescarTotales();
  });

  $("#AF1_plazo").change(function() {
    inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
    refrescarTotales();
  });

  $("#AF1_intereses_anticipados").change(function() {
    inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
    refrescarTotales();
  });

  $("#AF1_saldo_refinanciacion").change(function() {
    inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
    refrescarTotales();
  });

  $("#AF1_cuota_mensual").change(function() {
    inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
    refrescarTotales();
  });

  $("#AF1_costos").change(function() {
    inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
    refrescarTotales();
  });

  $("#aliadof2").change(function() {
    inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
    refrescarTotales();
  });

  $("#AF2_cuota").change(function() {
    inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
    refrescarTotales();
  });

  $("#AF2_factor_x_millon").change(function() {
    inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
    refrescarTotales();
  });

  $("#AF2_plazo").change(function() {
    inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
    refrescarTotales();
  });
});
