var grid;

//Datos para pruebas
const data = [
  {
    ID: 1,
    Entidad: "BBVA 0261",
    Sector: "FINAN/DATA Y CIFIN",
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
    ID: 2,
    Entidad: "EMBARGO - ANA CARLINA MONTOYA",
    Sector: "",
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

const dataEstructura = {
  ID: 0,
  Entidad: "",
  Sector: "",
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

calcularTotales = () => {
  return data.reduce(
    (acum, currentValue, index, arr) => {
      //Le asigna el último ID disponible
      if (index === arr.length - 1) {
        acum.ID = currentValue.ID + 1;
        lastID = acum.ID;
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

obtenerUltimoRegistro = grid => grid.get(grid.count(true));

asignarSiguienteID = (grid, dataEstructura) => {
  let ultimoRegistro = obtenerUltimoRegistro(grid);
  dataEstructura.ID = ultimoRegistro.ID + 1;
  return dataEstructura;
};

eliminarTotales = grid => {
  let ultimoRegistro = obtenerUltimoRegistro(grid);
  grid.removeRow(`${ultimoRegistro.ID}`);
};

//Opciones para los dropdown (o select)
const SI_NO_Opts = ["SI", "NO"];

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
  let porcentajeCalculado = 0;

  porcentajeCalculado = record.DescuentoLogrado / record.VlrInicioNegociacion;

  //Formatear resultado
  $cell.css("font-style", "italic");
  return porcentajeFormatter.format(porcentajeCalculado);
};

//Renders para calculos de opreaciones
const render_calcularSaldoCarteraNegociada = (
  value,
  record,
  $cell,
  $displayEl
) => {
  //TODO: Validar que si es un total no haga el cálculo

  value = record.VlrInicioNegociacion - record.DescuentoLogrado; //Cálculo del campo
  return render_convertirNumberAMoney(value, record, $cell, $displayEl);
};

const render_calcularPorcentaje = (value, record, $cell, $displayEl) => {
  value = record.DescuentoLogrado / record.VlrInicioNegociacion; //Cálculo del campo
  return render_convertirNumberAPorcentaje(value, record, $cell, $displayEl);
};

//Funciones para CRUD de la grilla
btnAgregarFila_clickHandler = () => {
  eliminarTotales(grid);
  var nuevaFila = asignarSiguienteID(grid, { ...dataEstructura });
  grid.addRow(nuevaFila);
  grid.addRow(calcularTotales());
};

Delete = e => {
  if (confirm("Are you sure?")) {
    grid.removeRow(e.data.id);
  }
};

// Declaración de las columnas
const columnas = [
  { field: "ID", width: 25, hidden: true },
  {
    field: "Entidad",
    editField: "",
    width: 150,
    type: "text",
    title: "Entidad",
    editor: true,
    mode: "EditOnly"
  },
  {
    field: "Sector",
    type: "text",
    title: "Sector",
    editor: true,
    mode: "EditOnly"
  },
  {
    field: "Estado",
    type: "text",
    width: 60,
    title: "Estado",
    editor: true,
    mode: "EditOnly"
  },
  {
    field: "CompraTR",
    type: "dropdown",
    width: 55,
    title: "Compra TR",
    editor: { dataSource: SI_NO_Opts },
    mode: "EditOnly"
  },
  {
    field: "CompraCKoAliado",
    type: "dropdown",
    title: "Compra CK o Aliado",
    editor: { dataSource: CompraCKoAliadoOpts },
    mode: "EditOnly"
  },
  {
    field: "CalificacionWAB",
    type: "dropdown",
    title: "Calificacion WAB",
    editor: { dataSource: CalificacionWABOpts },
    mode: "EditOnly"
  },
  {
    field: "Cuota",
    type: "text",
    title: "Cuota",
    align: "rigth",
    renderer: render_convertirNumberAMoney,
    editor: true,
    mode: "EditOnly"
  },
  {
    field: "SaldoCarteraCentrales",
    type: "text",
    title: "Saldo Cartera Centrales",
    align: "rigth",
    renderer: render_convertirNumberAMoney,
    editor: true,
    mode: "EditOnly"
  },
  {
    field: "VlrInicioNegociacion",
    type: "text",
    title: "Vlr. inicio negociación",
    align: "rigth",
    renderer: render_convertirNumberAMoney,
    editor: true,
    mode: "EditOnly"
  },
  {
    field: "DescuentoLogrado",
    type: "text",
    title: "Descuento logrado",
    align: "rigth",
    renderer: render_convertirNumberAMoney,
    editor: true,
    mode: "EditOnly"
  },
  {
    field: "SaldoCarteraNegociada",
    type: "text",
    title: "Saldo cartera negociada",
    align: "rigth",
    renderer: render_calcularSaldoCarteraNegociada,
    editor: true,
    mode: "EditOnly"
  },
  {
    field: "PctjeNegociacion",
    width: 60,
    type: "text",
    title: "% Neg",
    renderer: render_calcularPorcentaje
  },
  {
    field: "FechaVencimiento",
    type: "date",
    title: "Fecha Ven.",
    editor: true,
    mode: "EditOnly"
  },
  {
    width: 25,
    align: "center",
    type: "icon",
    icon: "fa fa-remove",
    tooltip: "Delete",
    events: { click: Delete }
  }
];

$(document).ready(function() {
  data.push(calcularTotales());
  grid = $("#grid").grid({
    dataSource: data,
    uiLibrary: "bootstrap4",
    iconsLibrary: "fontawesome",
    primaryKey: "ID",
    resizableColumns: true,
    inlineEditing: true,
    columns: columnas
  });
  grid.on("rowDataChanged", (e, id, record) => {
    console.log(e.data);

    // // Clone the record in new object where you can format the data to format that is supported by the backend.
    // var data = $.extend(true, {}, record);
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
