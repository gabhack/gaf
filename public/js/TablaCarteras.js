var grid;

//Datos para pruebas
const dataOriginal = [
  {
    ID: "1",
    Entidad: "BBVA 0261",
    SoloEfectivo: false,
    Data: "FINANCIERO",
    Cifin: "FINANCIERO",
    Estado: "AL DIA",
    CompraTR: "NO",
    CompraCKoAliado: "CK",
    CalificacionWAB: "A",
    Cuota: 918033,
    SaldoCarteraCentrales: 34158000,
    VlrInicioNegociacion: 34158000,
    DescuentoLogrado: 0,
    SaldoCarteraNegociada: 0,
    PctjeNegociacion: 0,
    FechaVencimiento: ""
  },
  // {
  //   ID: "2",
  //   Entidad: "EMBARGO - ANA CARLINA MONTOYA",
  //   SoloEfectivo: "",
  //   Data: "",
  //   Cifin: "",
  //   Estado: "",
  //   CompraTR: "SI",
  //   CompraCKoAliado: "CK",
  //   CalificacionWAB: "A",
  //   Cuota: 562785,
  //   SaldoCarteraCentrales: 6000000,
  //   VlrInicioNegociacion: 6000000,
  //   DescuentoLogrado: 0,
  //   SaldoCarteraNegociada: 0,
  //   PctjeNegociacion: 0,
  //   FechaVencimiento: ""
  // },
  {
    ID: "2",
    Entidad: "COOPSERV 0656-6217-6347-6427-6479-6628-68",
    SoloEfectivo: true,
    Data: "FINANCIERO",
    Cifin: "FINANCIERO",
    Estado: "AL DIA",
    CompraTR: "SI",
    CompraCKoAliado: "NO",
    CalificacionWAB: "A",
    Cuota: 652088,
    SaldoCarteraCentrales: 16427000,
    VlrInicioNegociacion: 16427000,
    DescuentoLogrado: 355925,
    SaldoCarteraNegociada: 0,
    PctjeNegociacion: 0,
    FechaVencimiento: ""
  }
];

var data = dataDB;

var IDfilaTotales;
var tipoCliente; //A,AA,AAA,B1,B2,B3,C

var inputJsonCarteras;

const dataEstructura = {
  ID: "0",
  Entidad: "",
  SoloEfectivo: false,
  Data: "",
  Cifin: "",
  Estado: "",
  CompraTR: "",
  CompraCKoAliado: "",
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
  let cantCarteras = 0;
  let cantCarterasEnMora = 0;
  let cantCarterasSoloEfectivo = 0;
  let cantCarterasCastigadas = 0;
  let cantEmbargos = 0;

  filaTotalesCalculada = data.reduce(
    (acum, currentValue, index, arr) => {
      //CÁLCULO DE TOTALES
      //Le asigna el último ID disponible
      if (index === arr.length - 1) {
        acum.ID = `${parseInt(currentValue.ID) + 1}`;
        IDfilaTotales = acum.ID;
      }

      acum.Cuota += currentValue.Cuota;
      acum.SaldoCarteraCentrales += currentValue.SaldoCarteraCentrales;
      acum.VlrInicioNegociacion += currentValue.VlrInicioNegociacion;
      acum.DescuentoLogrado += currentValue.DescuentoLogrado;
      acum.SaldoCarteraNegociada += currentValue.SaldoCarteraNegociada;

      //CÁLCULO DE INFORMACIÓN ADICIONAL
      cantCarteras = arr.length;
      cantCarterasSoloEfectivo = currentValue.SoloEfectivo
        ? cantCarterasSoloEfectivo + 1
        : cantCarterasSoloEfectivo;
      cantCarterasEnMora = currentValue.Estado.includes("MORA")
        ? cantCarterasEnMora + 1
        : cantCarterasEnMora;
      cantCarterasCastigadas =
        currentValue.Estado === "CARTERA CASTIGADA"
          ? cantCarterasCastigadas + 1
          : cantCarterasCastigadas;
      cantEmbargos =
        currentValue.Estado === "EMBARGO" ? cantEmbargos + 1 : cantEmbargos;
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
    tipoCliente = "AAA";
  } else {
    if (cantCarterasCastigadas > 1 || cantEmbargos >= 1) {
      tipoCliente = "C";
    } else {
      if (cantCarterasEnMora === 0) {
        if (cantCarterasSoloEfectivo === 0) {
          tipoCliente = "AA";
        } else {
          tipoCliente = "A";
        }
      } else {
        if (cantCarterasEnMora === 1) {
          tipoCliente = "B1";
        } else {
          tipoCliente = "B2";
          if (cantCarterasCastigadas === 1) {
            tipoCliente = "B3";
          }
        }
      }
    }
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

//Renders para que convertir los valores numéricos en cadenas de moneda o de tasa
const moneyFormatter = new Intl.NumberFormat("es-CO", {
  style: "currency",
  currency: "COP",
  minimumFractionDigits: 0
});

const render_convertirNumberAMoney = (value, record, $cell, $displayEl) => {
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
    refrescarTotales();
    grid.removeRow(id);
    grid.render(data);
    inputJsonCarteras.val(JSON.stringify(data.slice(0, data.length - 1)));
  }
};

//Event Handlers
rowDataChanged_handler = (e, id, record) => {
  // let columnasTotalizables = [
  //   "Cuota",
  //   "SaldoCarteraCentrales",
  //   "VlrInicioNegociacion",
  //   "DescuentoLogrado",
  //   "SaldoCarteraNegociada"
  // ];

  //Se requiere llamar una función para que los campos numéricos que quedaron como string, vuelvan a estar como numéricos
  //esto ocurre porque en el grid de GIJGO no tienen un campo tipo number
  formatearCamposParaIntegridadDATA(id);

  //TO DO: Agregar validación para que esta función solo se llame cuando se ha modificado una columna totalizable
  //       descomentar también la variable columnasTotalizables
  // if (columnasTotalizables.includes(column.field)) { //código };
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
    field: "Entidad",
    width: 100,
    type: "text",
    title: "Entidad",
    editor: true
  },
  {
    field: "SoloEfectivo",
    title: "Solo Efectivo?",
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
    field: "CompraTR",
    type: "dropdown",
    width: 55,
    title: "Compra TR",
    editor: { dataSource: SI_NO_Opts }
  },
  {
    field: "CompraCKoAliado",
    type: "dropdown",
    width: 40,
    title: "Compra CK o Aliado",
    editor: { dataSource: CompraCKoAliadoOpts }
  },
  {
    field: "CalificacionWAB",
    width: 50,
    type: "dropdown",
    title: "Calificacion WAB",
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
    width: 35,
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
});
