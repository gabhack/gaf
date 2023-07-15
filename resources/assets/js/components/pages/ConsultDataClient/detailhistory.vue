<template>
  <div class="container-fluid">
    <div>
      <div class="row mb-5">
        <div class="col-12 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-end">
            <img src="/img/avatar-img.svg" width="90" class="mr-3" />
            <h2 class="h3 text-black-pearl font-weight-exbold d-inline-block mb-0">{{ detalleConsulta.nombre }}</h2>
          </div>
          <button type="button" v-on:click="print" class="btn btn-black-pearl px-3">
            <span>Descargar PDF</span>
            <download-icon></download-icon>
          </button>
        </div>
      </div>

      <div id="consulta-container" class="row">
        <div class="panel mb-3">
          <div class="panel-heading">
            <b>Información básica</b>
          </div>
          <div class="panel-body" v-if="detalleConsulta.nombre">
            <div class="row">
              <div class="col-6">
                <b class="panel-label">Cedula:</b>
                <input disabled class="form-control text-center" type="number" v-model="detalleConsulta.ced" />
              </div>
              <div class="col-6">
                <b class="panel-label">Nombre:</b>
                <input disabled class="form-control text-center" type="text" v-model="detalleConsulta.nombre" />
              </div>
              <div class="col-6">
                <b class="panel-label">PAGADURIA:</b>
                <select disabled class="form-control" v-model="detalleConsulta.pagaduria">
                  <option value="FOPEP">FOPEP</option>
                  <option value="FIDUPREVISORA">FIDUPREVISORA</option>
                  <option value="SEDVALLE">SED VALLE</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6" v-if="datames !== null">
          <div class="panel mb-3">
            <div class="panel-heading">
              <b>INFORMACIÓN PERSONAL</b>
            </div>
            <div class="panel-body">
              <div class="row">
                <div
                  class="col-6"
                  v-if="
                    user.roles_id === 1 ||
                    user.roles_id === '1' ||
                    user.roles_id === 4 ||
                    user.roles_id === '4' ||
                    user.roles_id === 5 ||
                    user.roles_id === '5'
                  "
                >
                  <b class="panel-label">FONDO:</b>
                  <div>
                    <p class="panel-value">{{ datames.fondo }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">TIPO DE DOCUMENTO:</b>
                  <div>
                    <p class="panel-value">{{ datames.td }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">NUMERO DE DOCUMENTO:</b>
                  <div>
                    <p class="panel-value">{{ datames.doc }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">NOMBRE Y APELLIDO:</b>
                  <div>
                    <p class="panel-value">{{ datames.nomp }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">FECHA DE NACIMIENTO:</b>
                  <div>
                    <p class="panel-value">{{ datames.fecnacimient }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">DIRECCIÃ“N:</b>
                  <div>
                    <p class="panel-value">{{ datames.dir }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">DEPARTAMENTO:</b>
                  <div>
                    <p class="panel-value">{{ datames.dpto }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">MUNICIPIO:</b>
                  <div>
                    <p class="panel-value">{{ datames.mnpio }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">TIPO PENSION:</b>
                  <div>
                    <p class="panel-value">{{ datames.tp }}</p>
                  </div>
                </div>
                <div
                  class="col-md-6"
                  v-if="
                    user.roles_id === 1 ||
                    user.roles_id === '1' ||
                    user.roles_id === 4 ||
                    user.roles_id === '4' ||
                    user.roles_id === 5 ||
                    user.roles_id === '5'
                  "
                >
                  <b class="panel-label">NOMBRE DEL BANCO DONDE LE CONSIGNAN:</b>
                  <div>
                    <p class="panel-value">{{ datames.nbanco }}</p>
                  </div>
                </div>
                <div
                  class="col-md-6"
                  v-if="
                    user.roles_id === 1 ||
                    user.roles_id === '1' ||
                    user.roles_id === 4 ||
                    user.roles_id === '4' ||
                    user.roles_id === 5 ||
                    user.roles_id === '5'
                  "
                >
                  <b class="panel-label">SUCURSAL BANCO:</b>
                  <div>
                    <p class="panel-value">{{ datames.sucursal }}</p>
                  </div>
                </div>
                <div class="col-md-6">
                  <b class="panel-label">TELEFONO:</b>
                  <div>
                    <p class="panel-value">{{ datames.tel }}</p>
                  </div>
                </div>
                <div class="col-md-6">
                  <b class="panel-label">CELULAR:</b>
                  <div>
                    <p class="panel-value">{{ datames.cel }}</p>
                  </div>
                </div>
                <div class="col-md-6">
                  <b class="panel-label">CORREO:</b>
                  <div>
                    <p class="panel-value">{{ datames.correo }}</p>
                  </div>
                </div>
                <div class="col-md-6">
                  <b class="panel-label">VALOR PENSION:</b>
                  <div>
                    <p class="panel-value">{{ datames.vpension }}</p>
                  </div>
                </div>
                <div class="col-md-6">
                  <b class="panel-label">VALOR SALUD:</b>
                  <div>
                    <p class="panel-value">{{ datames.vsalud }}</p>
                  </div>
                </div>
                <div class="col-md-6">
                  <b class="panel-label">VALOR EMBARGOS APROXIMADO:</b>
                  <div>
                    <p class="panel-value">{{ datames.venbargos }}</p>
                  </div>
                </div>
                <div class="col-md-6">
                  <b class="panel-label">VALOR DESCUENTOS:</b>
                  <div>
                    <p class="panel-value">{{ datames.vdesc }}</p>
                  </div>
                </div>
                <div class="col-md-6">
                  <b class="panel-label">VALOR CUPO:</b>
                  <div>
                    <p class="panel-value">{{ datames.cupo }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6" v-if="fechaVinc !== null">
          <div class="panel mb-3">
            <div class="panel-heading">
              <b>HISTORIAL LABORAL</b>
            </div>
            <div class="panel-body">
              <div class="row">
                <!--<div class="col-6">
                    <b class="panel-label">Documento de identidad Pensionado:</b>
                    <div>
                        <p class="panel-value">{{fechaVinc.doc}}</p>
                    </div>
                </div> -->
                <div class="col-6">
                  <b class="panel-label">ANTIGUEDAD LABORAL:</b>
                  <div>
                    <p class="panel-value">{{ fechaVinc.vinc }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">TIPO PENSION:</b>
                  <div>
                    <p class="panel-value">{{ fechaVinc.tp }}</p>
                  </div>
                </div>
                <div
                  class="col-6"
                  v-if="
                    user.roles_id === 1 ||
                    user.roles_id === '1' ||
                    user.roles_id === 4 ||
                    user.roles_id === '4' ||
                    user.roles_id === 5 ||
                    user.roles_id === '5'
                  "
                >
                  <b class="panel-label">FECHA CARGA DATA:</b>
                  <div>
                    <p class="panel-value">{{ fechaVinc.fecdata }}</p>
                  </div>
                </div>
                <div
                  class="col-6"
                  v-if="
                    user.roles_id === 1 ||
                    user.roles_id === '1' ||
                    user.roles_id === 4 ||
                    user.roles_id === '4' ||
                    user.roles_id === 5 ||
                    user.roles_id === '5'
                  "
                >
                  <b class="panel-label">MES CARGA DATA:</b>
                  <div>
                    <p class="panel-value">{{ fechaVinc.mesdata }}</p>
                  </div>
                </div>
                <div
                  class="col-6"
                  v-if="
                    user.roles_id === 1 ||
                    user.roles_id === '1' ||
                    user.roles_id === 4 ||
                    user.roles_id === '4' ||
                    user.roles_id === 5 ||
                    user.roles_id === '5'
                  "
                >
                  <b class="panel-label">AÃ‘O CARGA DATA:</b>
                  <div>
                    <p class="panel-value">{{ fechaVinc.anodata }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12" v-if="obligacionSelected !== null">
          <div class="panel panel-primary mb-3">
            <div class="panel-heading"><b>OBLIGACIONES VIGENTES AL DIA</b></div>
            <div class="panel-body">
              <div class="row">
                <!-- <div class="col-md-2" v-if="user.roles_id === 1 || user.roles_id === '1' || user.roles_id === 4 || user.roles_id === '4' || user.roles_id === 5 || user.roles_id === '5'">
                    <b class="panel-label table-text">SELECCIONE PERIODO DE DATA:</b>
                    <div>
                        <input type="checkbox" class="mr-2" v-on:click="(e)=>vAplicado(e.target.checked, obligacionSelected && obligacionSelected, obligacionSelected && obligacionSelected.pagare, obligacionSelected && obligacionSelected.nomtercero)"/><p class="panel-value">{{obligacionSelected && obligacionSelected.periodo}}</p>
                    </div>
                </div> -->
                <div class="col-md-3">
                  <b class="panel-label table-text">TIPO ENTIDAD:</b>
                  <div>
                    <p class="panel-value">{{ obligacionSelected && obligacionSelected.clase }}</p>
                  </div>
                </div>
                <div class="col-md-3">
                  <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                  <div>
                    <p class="panel-value">{{ obligacionSelected && obligacionSelected.nomtercero }}</p>
                  </div>
                </div>
                <div class="col-md-3">
                  <b class="panel-label table-text">NUMERO PAGARE:</b>
                  <div>
                    <p class="panel-value">{{ obligacionSelected && obligacionSelected.pagare }}</p>
                  </div>
                </div>
                <!-- <div class="col-6">
                    <b class="panel-label">Consecutivo (B):</b>
                    <div>
                        <p class="panel-value">{{obligacionSelected && obligacionSelected.concecutivo}}</p>
                    </div>
                </div> -->

                <!-- <div class="col-6">
                    <b class="panel-label">Entidad (B):</b>
                    <div>
                        <p class="panel-value">{{obligacionSelected && obligacionSelected.tercero}}</p>
                    </div>
                </div> -->

                <!-- <div class="col-6">
                        <b class="panel-label">TIPO DE DOCUMENTO:</b>
                        <div>
                            <p class="panel-value">{{obligacionSelected && obligacionSelected.td}}</p>
                        </div>
                    </div>

                    <div class="col-6">
                        <b class="panel-label">DOCUMENTO DE IDENTIDAD PENSIONADO:</b>
                        <div>
                            <p class="panel-value">{{obligacionSelected && obligacionSelected.doc}}</p>
                        </div>
                    </div>

                    <div class="col-6">
                        <b class="panel-label">Nombre y apellido pensionado:</b>
                        <div>
                            <p class="panel-value">{{obligacionSelected && obligacionSelected.nomp}}</p>
                        </div>
                    </div> -->

                <!-- <div class="col-6">
                    <b class="panel-label">Porcentaje (B):</b>
                    <div>
                        <p class="panel-value">{{obligacionSelected && obligacionSelected.porcentaje}}</p>
                    </div>
                </div> -->

                <div class="col-md-3">
                  <b class="panel-label table-text">NOMBRE ENTIDAD CEDIENTE:</b>
                  <div>
                    <p class="panel-value">{{ obligacionSelected && obligacionSelected.nonentant }}</p>
                  </div>
                </div>

                <div
                  class="col-md-3"
                  v-if="
                    user.roles_id === 1 ||
                    user.roles_id === '1' ||
                    user.roles_id === 4 ||
                    user.roles_id === '4' ||
                    user.roles_id === 5 ||
                    user.roles_id === '5'
                  "
                >
                  <b class="panel-label table-text">VALOR TOTAL DEUDA:</b>
                  <div>
                    <p class="panel-value">{{ obligacionSelected && obligacionSelected.vtotal }}</p>
                  </div>
                </div>

                <div
                  class="col-md-3"
                  v-if="
                    user.roles_id === 1 ||
                    user.roles_id === '1' ||
                    user.roles_id === 4 ||
                    user.roles_id === '4' ||
                    user.roles_id === 5 ||
                    user.roles_id === '5'
                  "
                >
                  <b class="panel-label table-text">VALOR PAGADO DEUDA:</b>
                  <div>
                    <p class="panel-value">{{ obligacionSelected && obligacionSelected.vpagado }}</p>
                  </div>
                </div>

                <div
                  class="col-md-3"
                  v-if="
                    user.roles_id === 1 ||
                    user.roles_id === '1' ||
                    user.roles_id === 4 ||
                    user.roles_id === '4' ||
                    user.roles_id === 5 ||
                    user.roles_id === '5'
                  "
                >
                  <b class="panel-label table-text">FECHA INICIO DEUDA:</b>
                  <div>
                    <p class="panel-value">{{ obligacionSelected && obligacionSelected.fgrab }}</p>
                  </div>
                </div>

                <div class="col-md-3">
                  <b class="panel-label table-text">CUOTA:</b>
                  <div>
                    <p class="panel-value">{{ obligacionSelected && obligacionSelected.vaplicado }}</p>
                  </div>
                </div>

                <!--<div class="col-6">
                    <b class="panel-label">SALDO DEUDA:</b>
                    <div>
                        <p class="panel-value">{{obligacionSelected && obligacionSelected.saldo}}</p>
                    </div>
                </div>-->

                <!-- <div class="col-6">
                    <b class="panel-label">Forma (B):</b>
                    <div>
                        <p class="panel-value">{{obligacionSelected && obligacionSelected.forma}}</p>
                    </div>
                </div> -->

                <!-- <div class="col-6">
                    <b class="panel-label">Codigo entidad anterior (B):</b>
                    <div>
                        <p class="panel-value">{{obligacionSelected && obligacionSelected.codentiant}}</p>
                    </div>
                </div> -->

                <!-- <div class="col-6">
                    <b class="panel-label">Fecha de cesion a entidad actual:</b>
                    <div>
                        <p class="panel-value">{{obligacionSelected && obligacionSelected.fechacesion}}</p>
                    </div>
                </div>

                <div class="col-6">
                    <b class="panel-label">Tipo de descuento:</b>
                    <div>
                        <p class="panel-value">{{obligacionSelected && obligacionSelected.tdesc}}</p>
                    </div>
                </div>

                <div class="col-6">
                    <b class="panel-label">PAGARE5DIG:</b>
                    <div>
                        <p class="panel-value">{{obligacionSelected && obligacionSelected.p5d}}</p>
                    </div>
                </div>

                <div class="col-6">
                    <b class="panel-label">PAGARE4DIGCON0:</b>
                    <div>
                        <p class="panel-value">{{obligacionSelected && obligacionSelected.p4d}}</p>
                    </div>
                </div>

                <div class="col-6">
                    <b class="panel-label">Numero pagare:</b>
                    <div>
                        <p class="panel-value">{{obligacionSelected && obligacionSelected.numpagopt}}</p>
                    </div>
                </div>

                <div class="col-6">
                    <b class="panel-label">Fecha carga data:</b>
                    <div>
                        <p class="panel-value">{{obligacionSelected && obligacionSelected.fecdata}}</p>
                    </div>
                </div>

                <div class="col-6">
                    <b class="panel-label">Mes carga data:</b>
                    <div>
                        <p class="panel-value">{{obligacionSelected && obligacionSelected.mesdata}}</p>
                    </div>
                </div>

                <div class="col-6">
                    <b class="panel-label">AÃ±o carga data:</b>
                    <div>
                        <p class="panel-value">{{obligacionSelected && obligacionSelected.anodata}}</p>
                    </div>
                </div> -->
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12" v-if="datamesfidu.length > 0">
          <div class="panel panel-primary mb-3">
            <div class="panel-heading"><b>OTROS POSIBLES INGRESOS Y DEDUCCIONES</b></div>
            <div class="panel-body">
              <div class="row">
                <div class="col-6">
                  <b class="panel-label">MUNICIPIO:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.mnpio }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">DOCUMENTO DE IDENTIDAD:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.doc }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">TIPO DE DOCUMENTO:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.td }}</p>
                  </div>
                </div>
                <!-- <div class="col-6">
                    <b class="panel-label">Descripcion tipo documento (B):</b>
                    <div v-for="(datamesfidu, key) in datamesfidu"  :key="key">
                        <p class="panel-value">{{datamesfidu.tdd}}</p>
                    </div>
                </div> -->
                <div class="col-6">
                  <b class="panel-label">NOMBRES:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.solonomp }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">APELLIDOS:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.soloapellp }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">GENERO:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.genero }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">ESTADO CIVIL:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.estcivil }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">FECHA DE NACIMIENTO:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.fecnacimient }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">EDAD PENSIONADO:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.edad }}</p>
                  </div>
                </div>

                <!-- <div class="col-6">
                    <b class="panel-label">Tipo de Vinculacion (B):</b>
                    <div v-for="(datamesfidu, key) in datamesfidu"  :key="key">
                        <p class="panel-value">{{datamesfidu.tipvinc}}</p>
                    </div>
                </div> -->

                <div
                  class="col-6"
                  v-if="
                    user.roles_id === 1 ||
                    user.roles_id === '1' ||
                    user.roles_id === 4 ||
                    user.roles_id === '4' ||
                    user.roles_id === 5 ||
                    user.roles_id === '5'
                  "
                >
                  <b class="panel-label">DESCRIPCION:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.desctipvinc }}</p>
                  </div>
                </div>

                <!-- <div class="col-6">
                    <b class="panel-label">Codigo Fuente recurso (B):</b>
                    <div v-for="(datamesfidu, key) in datamesfidu"  :key="key">
                        <p class="panel-value">{{datamesfidu.fuenrecurso}}</p>
                    </div>
                </div> -->

                <div
                  class="col-6"
                  v-if="
                    user.roles_id === 1 ||
                    user.roles_id === '1' ||
                    user.roles_id === 4 ||
                    user.roles_id === '4' ||
                    user.roles_id === 5 ||
                    user.roles_id === '5'
                  "
                >
                  <b class="panel-label">RECURSOS:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.descfuenrecurso }}</p>
                  </div>
                </div>

                <!-- <div class="col-6">
                    <b class="panel-label">Codigo dane departamento (B):</b>
                    <div v-for="(datamesfidu, key) in datamesfidu"  :key="key">
                        <p class="panel-value">{{datamesfidu.numdep}}</p>
                    </div>
                </div> -->

                <div class="col-6">
                  <b class="panel-label">DEPARTAMENTO:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.dpto }}</p>
                  </div>
                </div>

                <!-- <div class="col-6">
                    <b class="panel-label">Resolucion (B):</b>
                    <div v-for="(datamesfidu, key) in datamesfidu"  :key="key">
                        <p class="panel-value">{{datamesfidu.resol}}</p>
                    </div>
                </div> -->

                <div class="col-6">
                  <b class="panel-label">VINCULACION:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.vinc }}</p>
                  </div>
                </div>

                <!-- <div class="col-6">
                    <b class="panel-label">Fecha expedicion resolucion (B):</b>
                    <div v-for="(datamesfidu, key) in datamesfidu"  :key="key">
                        <p class="panel-value">{{datamesfidu.fechefect}}</p>
                    </div>
                </div> -->

                <div class="col-6">
                  <b class="panel-label">VALOR PENSION:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.vpension }}</p>
                  </div>
                </div>

                <!-- <div class="col-6">
                    <b class="panel-label">Estado Pension (B):</b>
                    <div v-for="(datamesfidu, key) in datamesfidu"  :key="key">
                        <p class="panel-value">{{datamesfidu.estpens}}</p>
                    </div>
                </div> -->

                <div class="col-6">
                  <b class="panel-label">TIPO DOCUMENTO BENEFICIARIO:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.td }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">DOCUMENTO IDENTIDAD BENEFICIARIO:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.docbenef }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">NOMBRE BENEFICIARIO:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.nombenef }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">TELEFONO:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.tel }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">DIRECCIÃ“N:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.dir }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">CORREO:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.correo }}</p>
                  </div>
                </div>

                <!-- <div class="col-6">
                    <b class="panel-label">Numero comprobante (B):</b>
                    <div v-for="(datamesfidu, key) in datamesfidu"  :key="key">
                        <p class="panel-value">{{datamesfidu.nomcomprob}}</p>
                    </div>
                </div> -->

                <div class="col-6">
                  <b class="panel-label">PERIODO DE DATA:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.periodo }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">TIPO PENSION CODIGO:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.tipvinc }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">FECHA DE PAGO PENSION:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.fechpago }}</p>
                  </div>
                </div>

                <!-- <div class="col-6">
                    <b class="panel-label">Sucursal Banco (B):</b>
                    <div v-for="(datamesfidu, key) in datamesfidu"  :key="key">
                        <p class="panel-value">{{datamesfidu.sucursal}}</p>
                    </div>
                </div> -->

                <!-- <div class="col-6">
                    <b class="panel-label">Valor pension Con retroactivo (B):</b>
                    <div v-for="(datamesfidu, key) in datamesfidu"  :key="key">
                        <p class="panel-value">{{datamesfidu.vpension}}</p>
                    </div>
                </div> -->

                <!-- <div class="col-6">
                    <b class="panel-label">Valor descuentos en bruto Retroactivo (B):</b>
                    <div v-for="(datamesfidu, key) in datamesfidu"  :key="key">
                        <p class="panel-value">{{datamesfidu.vdescbruto}}</p>
                    </div>
                </div> -->

                <!-- <div class="col-6">
                    <b class="panel-label">Valor pago en bruto Retroactivo (B):</b>
                    <div v-for="(datamesfidu, key) in datamesfidu"  :key="key">
                        <p class="panel-value">{{datamesfidu.pagonetbruto}}</p>
                    </div>
                </div> -->

                <div class="col-6">
                  <b class="panel-label">FECHA DE CARGA DATA:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.fecdata }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">MES DE CARGA DATA:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.mesdata }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">AÃ‘O DE CARGA DATA:</b>
                  <div v-for="(datamesfidu, key) in datamesfidu" :key="key">
                    <p class="panel-value">{{ datamesfidu.anodata }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div
          class="col-md-6"
          v-if="
            user.roles_id === 1 ||
            user.roles_id === '1' ||
            user.roles_id === 4 ||
            user.roles_id === '4' ||
            user.roles_id === 5 ||
            user.roles_id === '5'
          "
        >
          <div class="panel panel-primary mb-3">
            <div class="panel-heading"><b>DATAMESSECEDUC</b></div>
            <div class="panel-body">
              <div class="row">
                <div class="col-6">
                  <b class="panel-label">Documento de identidad docente:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.doc }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">Nombre y apellido docente:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.nomp }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">Fecha Ingreso:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.fechingr }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">AntigÃ¼edad Vinculacion (B) :</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.antiguedad }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">Fecha de nacimiento:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.fecnacimient }}</p>
                  </div>
                </div>
                <div class="col-6">
                  <b class="panel-label">Edad Pensionado:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.edad }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">Area de desempeÃ±o:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.esquema }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">Cargo:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.cargo }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">Valor ingreso Aproximado:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.vpension }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">Fecha Vinculacion:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.fecnombr }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">Fecha de posesion (B):</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.fecposesion }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">Tipo Vinculacion:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.nivcontr }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">Estado laboral:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.estlaboral }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">Centro de educacion:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.centrocosto }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">Municipio y Departamento:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.mnpioydep }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">Telefono:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.tel }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">Direccion:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.dir }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">Correo:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.correo }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">Sede en que presta el servicio:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.sedecoleg }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">Fecha carga data:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.fecdata }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">Mes carga data:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.mesdata }}</p>
                  </div>
                </div>

                <div class="col-6">
                  <b class="panel-label">AÃ±o carga data:</b>
                  <div>
                    <p class="panel-value">{{ datamesseceduc.anodata }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- <div class="col-md-12" v-if="resultPagare.cuota_compra && resultPagare.cuota_compra.length > 0">
            <div class="panel panel-primary">
                <div class="panel-heading"><b>CALCULO DE COMPRA DE CARTERA</b></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-6">
                            <b class="panel-label">Consecutivo:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.consecutivo}}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <b class="panel-label">Estado:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.estado}}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <b class="panel-label">Fecha consulta:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.fecha_consulta}}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <b class="panel-label">Cedula:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.doc}}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <b class="panel-label">Nombre:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.nombre}}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <b class="panel-label">Tipo de credito:</b>
                            <div>
                                <p class="panel-value">{{dataclient.tipo_credito}}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">Cupo Lib Inversion:</b>
                            <div>
                                <p class="panel-value">{{dataclient.clibinv}}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">Cuota Compra:</b>
                            <div>
                                <p class="panel-value">{{libInv.vaplicado}}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">Entidad:</b>
                            <div>
                                <p class="panel-value">{{row}}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">Pagaduria:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.pagaduria}}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">Vr. Credito:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.vr_credito}}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">Vr. Desembolso:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.vr_desembolso}}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">Plazo:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.plazo}}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">Cuota Cred:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.cuota_cred}}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">Aprobado:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.aprobado}}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">% de incorporacion:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.pct_incorporacion}}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">Cuota Maxima de incorporacion:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.max_incorporacion}}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">Fecha respuesta consulta:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.fec_rta_consulta}}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">Fecha Vinculacion:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.fecha_vinculacion}}</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <b class="panel-label">Tipo Vinculacion:</b>
                            <div>
                                <p class="panel-value">{{resultPagare.tipo_vinculacion}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ['id', 'user'],
  data() {
    return {
      detalleConsulta: {},
      datames: null,
      fechaVinc: null,
      obligacionSelected: null,
      datamesfidu: [],
      datamesseceduc: []
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    getData() {
      axios.post('detalleConsulta', { id: this.id }).then(result => {
        console.log(result.data.data);

        this.detalleConsulta = result.data.data.detalle_consulta;
        this.datames = result.data.data.info_datames;
        this.fechaVinc = result.data.data.info_fechavinc;
        this.obligacionSelected = result.data.data.info_obligaciones;

        this.datamesfidu = result.data.data.datamesfidu;
        this.datamesseceduc = result.data.data.datamesseceduc;
      });
    },
    print() {
      window.print();
    }
  }
};
</script>
