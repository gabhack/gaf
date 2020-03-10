var grid;

//Datos para pruebas
const dataOriginal = [
  {
    ID: "1",
    Entidad: "BBVA 0261",
    Data: "FINANCIERO",
    Cifin: "FINANCIERO",
    // Sector: "FINAN/DATA Y CIFIN",
    Estado: "AL DIA",
    CompraTR: "NO",
    CompraCKoAliado: "CK",
    CalificacionWAB: "A",
    Cuota: 918033,
    SaldoCarteraCentrales: 34158000,
    VlrInicioNegociacion: 34158000,
    DescuentoLogrado: 3415800,
    SaldoCarteraNegociada: 34158000,
    PctjeNegociacion: 0,
    FechaVencimiento: ""
  },
  {
    ID: "2",
    Entidad: "EMBARGO - ANA CARLINA MONTOYA",
    Data: "",
    Cifin: "",
    // Sector: "",
    Estado: "",
    CompraTR: "SI",
    CompraCKoAliado: "CK",
    CalificacionWAB: "A",
    Cuota: 562785,
    SaldoCarteraCentrales: 6000000,
    VlrInicioNegociacion: 6000000,
    DescuentoLogrado: 0,
    SaldoCarteraNegociada: 6000000,
    PctjeNegociacion: 0,
    FechaVencimiento: ""
  }
];

var data = dataOriginal;

const dataEstructura = {
  ID: "0",
  Entidad: "",
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

var IDfilaTotales;

//Funciones auxiliares
borrarElementoData = elemID => data.filter(e => e.ID !== elemID);

calcularTotales = () => {
  return data.reduce(
    (acum, currentValue, index, arr) => {
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
      return acum;
    },
    { ...dataEstructura }
  );
};

// TODO: Borrar esta función obtenerUltimoRegistro
// obtenerUltimoRegistro = () => grid.get(grid.count(true));

asignarSiguienteID = dataEstructura => {
  dataEstructura.ID = `${parseInt(data[data.length - 1].ID) + 1}`;
  return dataEstructura;
};

eliminarTotales = (blnRenderGrid = false) => {
  data.pop(); //Elimina el último elemento, el cual siempre son los totales
  if (blnRenderGrid) grid.render();
};

//Opciones para los dropdown (o select)
const SI_NO_Opts = ["SI", "NO"];

const Data_Cifin_Opts = ["REAL", "FINANCIERO", "COOPERATIVO"];

const EstadoOpts = [
  "AL DIA",
  "MORA 30-60",
  "MORA 60-90",
  "MORA 90-120",
  "MORA 120-180",
  "MORA >180",
  "DUDOSO RECAUDO",
  "CARTERA CASTIGADA"
];

const CompraCKoAliadoOpts = ["No", "CK", "Aliado 1", "Aliado 2"];

const CalificacionWABOpts = ["A", "B", "C", "D", "E", "F", "G", "K"];

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
  }
  return render_convertirNumberAMoney(value, record, $cell, $displayEl);
};

const render_calcularPorcentaje = (value, record, $cell, $displayEl, id) => {
  if (id !== IDfilaTotales) {
    //Si no es una fila totales se calcula el dato
    if (record.VlrInicioNegociacion !== 0) {
      value = record.DescuentoLogrado / record.VlrInicioNegociacion; //Cálculo del campo
    }
    return render_convertirNumberAPorcentaje(value, record, $cell, $displayEl);
  }
};

const render_activarIcono = (value, record, $cell, $displayEl, id) => {
  if (id !== IDfilaTotales) {
    $displayEl.html(`<span class="fa fa-remove"></span>`);
  }
};

//Funciones para CRUD de la grilla
btnAgregarFila_clickHandler = () => {
  eliminarTotales();
  var nuevaFila = asignarSiguienteID({ ...dataEstructura });
  data.push(nuevaFila);
  data.push(calcularTotales());
  grid.render(data);
};

deleteRow_ClickHandler = e => {
  if (e.data.id !== IDfilaTotales) {
    if (confirm("Seguro que desea eliminar esta cartera?")) {
      data = borrarElementoData(e.data.id);
      grid.removeRow(e.data.id);
      grid.render(data);
    }
  }
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
    editField: "Entidad",
    width: 150,
    type: "text",
    title: "Entidad",
    editor: true,
    mode: "EditOnly"
  },
  {
    field: "Data",
    editField: "Data",
    type: "dropdown",
    width: 50,
    title: "DATA",
    editor: { dataSource: Data_Cifin_Opts },
    mode: "EditOnly"
  },
  {
    field: "Cifin",
    editField: "Cifin",
    type: "dropdown",
    width: 50,
    title: "CIFIN",
    editor: { dataSource: Data_Cifin_Opts },
    mode: "EditOnly"
  },
  {
    field: "Estado",
    editField: "Estado",
    type: "dropdown",
    width: 60,
    title: "Estado",
    editor: { dataSource: EstadoOpts },
    mode: "EditOnly"
  },
  {
    field: "CompraTR",
    editField: "CompraTR",
    type: "dropdown",
    width: 55,
    title: "Compra TR",
    editor: { dataSource: SI_NO_Opts },
    mode: "EditOnly"
  },
  {
    field: "CompraCKoAliado",
    editField: "CompraCKoAliado",
    type: "dropdown",
    width: 40,
    title: "Compra CK o Aliado",
    editor: { dataSource: CompraCKoAliadoOpts },
    mode: "EditOnly"
  },
  {
    field: "CalificacionWAB",
    editField: "CalificacionWAB",
    width: 50,
    type: "dropdown",
    title: "Calificacion WAB",
    editor: { dataSource: CalificacionWABOpts },
    mode: "EditOnly"
  },
  {
    field: "Cuota",
    editField: "Cuota",
    type: "text",
    title: "Cuota",
    align: "rigth",
    renderer: render_convertirNumberAMoney,
    editor: true,
    mode: "EditOnly"
  },
  {
    field: "SaldoCarteraCentrales",
    editField: "SaldoCarteraCentrales",
    type: "text",
    title: "Saldo Cartera Centrales",
    align: "rigth",
    renderer: render_convertirNumberAMoney,
    editor: true,
    mode: "EditOnly"
  },
  {
    field: "VlrInicioNegociacion",
    editField: "VlrInicioNegociacion",
    type: "text",
    title: "Vlr. inicio negociación",
    align: "rigth",
    renderer: render_convertirNumberAMoney,
    editor: true,
    mode: "EditOnly"
  },
  {
    field: "DescuentoLogrado",
    editField: "DescuentoLogrado",
    type: "text",
    title: "Descuento logrado",
    align: "rigth",
    renderer: render_convertirNumberAMoney,
    editor: true,
    mode: "EditOnly"
  },
  {
    field: "SaldoCarteraNegociada",
    editField: "SaldoCarteraNegociada",
    type: "text",
    title: "Saldo cartera negociada",
    align: "rigth",
    renderer: render_calcularSaldoCarteraNegociada,
    mode: "ReadOnly"
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
    editField: "FechaVencimiento",
    type: "date",
    title: "Fecha Ven.",
    editor: true,
    mode: "EditOnly"
  },
  {
    width: 25,
    align: "center",
    // type: "icon",
    // icon: "fa fa-remove",
    // tmpl: '<span class="fa fa-remove"></span>',
    tooltip: "Delete",
    renderer: render_activarIcono,
    events: { click: deleteRow_ClickHandler }
  }
];

$(document).ready(function() {
  data.push(calcularTotales());
  grid = $("#grid").grid({
    primaryKey: "ID",
    dataSource: data,
    uiLibrary: "bootstrap4",
    iconsLibrary: "fontawesome",
    resizableColumns: true,
    inlineEditing: true,
    columns: columnas
  });
  grid.on("rowDataChanged", (e, id, record) => {
    // Clone the record in new object where you can format the data to format that is supported by the backend.
    var gridData = $.extend(true, {}, record);
    // // Format the date to format that is supported by the backend.
    // data.DateOfBirth = gj.core
    //   .parseDate(record.DateOfBirth, "mm/dd/yyyy")
    //   .toISOString();
    // // Post the data to the server
    // $.ajax({
    //   url: "/Players/Save",
    //   data: { record: data },
    //   method: "POST"
    // }).fail(function() {
    //   alert("Failed to save.");
    // });
  });

  $("#btnAgregarFila").on("click", btnAgregarFila_clickHandler);

  $("#form_guardar").on("keyup keypress", e => {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  });
});
