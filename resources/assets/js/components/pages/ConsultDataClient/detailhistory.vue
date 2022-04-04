<template>
    <div class="container-fluid">                
            <div class="row mb-5">
                <div class="col-12 d-flex align-items-center justify-content-between">                
                    <div class="d-flex align-items-end">
                        <img src="/img/avatar-img.svg" width="90" class="mr-3">
                        <h2 class="h3 text-black-pearl font-weight-exbold d-inline-block mb-0">{{detalleConsulta.nombre}}</h2>
                    </div>                    
                </div>
            </div>
            <div id="consulta-container" class="row">
                <div class="col-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <b>Información básica</b>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <table class="table table-responsive table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Consecutivo</th>
                                            <th>Nombre</th>
                                            <th>Cedula</th>
                                            <th>Aprobado</th>
                                            <th>Cuota Compra</th>
                                            <th>Credito Libre Inversión</th>
                                            <th>Cantidad Maxima Incorporación</th>
                                            <th>Cuota Credito</th>
                                            <th>Entidad</th>
                                            <th>Estado</th>
                                            <th>Fecha Consulta Ami</th>
                                            <th>Fecha Respuesta</th>
                                            <th>Fecha Vinculación</th>
                                            <th>Pagare</th>
                                            <th>plazo</th>
                                            <th>Porcentaje Incorporación</th>
                                            <th>Tipo de Credito</th>
                                            <th>Tipo de Consulta</th>
                                            <th>Tipo de Vinculación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{detalleConsulta.conc}}</td>
                                            <td>{{detalleConsulta.nombre}}</td>
                                            <td>{{detalleConsulta.ced}}</td>
                                            <td>{{detalleConsulta.aprobado}}</td>
                                            <td>{{detalleConsulta.ccompra}}</td>
                                            <td>{{detalleConsulta.clibinv}}</td>
                                            <td>{{detalleConsulta.cmaxincorp}}</td>
                                            <td>{{detalleConsulta.cuotacredito}}</td>
                                            <td>{{detalleConsulta.entidad}}</td>
                                            <td>{{detalleConsulta.estado}}</td>
                                            <td>{{detalleConsulta.fconsultaami}}</td>
                                            <td>{{detalleConsulta.frespuesta}}</td>
                                            <td>{{detalleConsulta.fvinculacion}}</td>
                                            <td>{{detalleConsulta.pagare}}</td>
                                            <td>{{detalleConsulta.plazo}}</td>
                                            <td>{{detalleConsulta.porcincorp}}</td>
                                            <td>{{detalleConsulta.tcredito}}</td>
                                            <td>{{detalleConsulta.tipo_consulta}}</td>
                                            <td>{{detalleConsulta.tvinculacion}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Información Pesonal (DATAMES)</b></div>
                        <div class="panel-body">      
                            <table class="table table-responsive table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>FONDO</th>
                                        <th>TD DOCUMENTO</th>
                                        <th>DOCUMENTO</th>
                                        <th>X</th>
                                        <th>PENSIONADO (APELLIDOS Y NOMBRES)</th>
                                        <th>FECHA DE NACIMIENTO</th>
                                        <th>DIRECCION</th>
                                        <th>DPTO (NOMBRE DEPARTAMENTO de residencia del pensionado)</th>
                                        <th>MNPIO (NOMBRE MUNICIPIO de residencia del pensionado)</th>
                                        <th>TIPO_PENSION (NOMBRE_PENSION)</th>
                                        <th>NOMBRE_BANCO (NOMBRE DEL BANCO)</th>
                                        <th>SUCURSAL (NOMBRE DE LA SUCURSAL)</th>
                                        <th>TELÉFONO</th>
                                        <th>CELULAR</th>
                                        <th>CORREO ELECTRÓNICO</th>
                                        <th>*VALOR PENSIONES</th>
                                        <th>*VALOR SALUD</th>
                                        <th>*VALOR EMBARGOS</th>
                                        <th>*VALOR DESCUENTOS</th>
                                        <th>*CUPO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{datames.fondo}}</td>
                                        <td>{{datames.td}}</td>
                                        <td>{{datames.x}}</td>
                                        <td>{{datames.doc}}</td>
                                        <td>{{datames.nomp}}</td>
                                        <td>{{datames.fecnacimient}}</td>
                                        <td>{{datames.dir}}</td>
                                        <td>{{datames.dpto}}</td>
                                        <td>{{datames.mnpio}}</td>
                                        <td>{{datames.tp}}</td>
                                        <td>{{datames.nbanco}}</td>
                                        <td>{{datames.sucursal}}</td>
                                        <td>{{datames.tel}}</td>
                                        <td>{{datames.cel}}</td>
                                        <td>{{datames.correo}}</td>
                                        <td>{{datames.vpension}}</td>
                                        <td>{{datames.vsalud}}</td>
                                        <td>{{datames.venbargos}}</td>
                                        <td>{{datames.vdesc}}</td>
                                        <td>{{datames.cupo}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <b>(FECHAVINC)</b>
                        </div>
                        <div class="panel-body">
                            <table class="table table-responsive table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>DOCUMENTO</th>
                                        <th>VINCULACIÓN</th>
                                        <th>TIPO_PENSION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{fechaVinc.doc}}</td>
                                        <td>{{fechaVinc.vinc}}</td>
                                        <td>{{fechaVinc.tp}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>            

                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Obligaciones Seleccionadas</b></div>
                        <div class="panel-body">      
                            <table class="table table-responsive table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Periodo</th>
                                        <th>Consecutivo</th>
                                        <th>Clase Tercero</th>
                                        <th>Tercero</th>
                                        <th>Nombre del Tercero</th>
                                        <th>Tipo Documento</th>
                                        <th>Documento</th>
                                        <th>Nombre</th>
                                        <th>Pagare</th>
                                        <th>Porcentaje</th>
                                        <th>Valor Aplicado</th>
                                        <th>Valor Total</th>
                                        <th>Valor Pagado</th>
                                        <th>Saldo</th>
                                        <th>Fecha Grabación</th>
                                        <th>Forma</th>
                                        <th>Código entidad anterior</th>
                                        <th>Nombre Entidad Anterior</th>
                                        <th>Fecha de Cesión</th>
                                        <th>Tipo Descuento</th>
                                        <th>PAGARE5DIG</th>
                                        <th>PAGARE4DIGCON0</th>
                                        <th>Numero pagare</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(obligacionSelected, key) in obligacionSelected" :key="key">                                        
                                        <td>{{obligacionSelected.periodo}}</td>
                                        <td>{{obligacionSelected.concecutivo}}</td>
                                        <td>{{obligacionSelected.clase}}</td>
                                        <td>{{obligacionSelected.tercero}}</td>
                                        <td>{{obligacionSelected.nomtercero}}</td>
                                        <td>{{obligacionSelected.td}}</td>
                                        <td>{{obligacionSelected.doc}}</td>
                                        <td>{{obligacionSelected.nomp}}</td>
                                        <td>{{obligacionSelected.pagare}}</td>
                                        <td>{{obligacionSelected.porcentaje}}</td>
                                        <td>{{obligacionSelected.vaplicado}}</td>
                                        <td>{{obligacionSelected.vtotal}}</td>
                                        <td>{{obligacionSelected.vpagado}}</td>
                                        <td>{{obligacionSelected.saldo}}</td>
                                        <td>{{obligacionSelected.fgrab}}</td>
                                        <td>{{obligacionSelected.forma}}</td>
                                        <td>{{obligacionSelected.codentiant}}</td>
                                        <td>{{obligacionSelected.nonentant}}</td>
                                        <td>{{obligacionSelected.fechacesion}}</td>
                                        <td>{{obligacionSelected.tdesc}}</td>
                                        <td>{{obligacionSelected.p5d}}</td>
                                        <td>{{obligacionSelected.p4d}}</td>
                                        <td>{{obligacionSelected.numpagopt}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>        
    </div>
</template>
<script>
export default {
    props: ["id"],
    data() {
        return {
            detalleConsulta:{},
            datames:[],
            fechaVinc:[],
            obligacionSelected:[],            
        }
    },
    mounted(){
        this.getData();
    },
    methods:{  
        getData(){
            axios.post('detalleConsulta',{id:this.id}).then((result)=>{
                console.log(result.data.data);

                this.detalleConsulta = result.data.data.detalle_consulta;
                this.datames = result.data.data.info_datames;
                this.fechaVinc = result.data.data.info_fechavinc;
                this.obligacionSelected = result.data.data.info_obligaciones;

            })
        }
    }
}
</script>