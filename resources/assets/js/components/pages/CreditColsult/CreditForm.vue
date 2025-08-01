<template>
    <div class="container pt-4">
        <b-form @submit.prevent="submitData" v-if="creditInfo">
            <!-- Informacion Personal -->
            <b-form-group label="" label-size="sm" label-class="h2 pt-0" class="mb-0">
                <div class="section-separator">
                    <span class="separator-text">Tu información personal</span>
                </div>

                <div class="form-container">
                    <b-row>
                        <b-col cols="12" class="d-flex align-items-center mb-3">
                            <span style="font-size: 16px; font-weight: 400; margin-right: 80px;">Género</span>
                            <b-form-radio-group id="gender" v-model="form.gender" :options="genders" required inline
                                class="mb-0">
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-radio-group>
                        </b-col>
                    </b-row>

                    <b-row class="mb-3">
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label for="firstName" class="labelForm"> Primer Nombre </label>
                                <b-form-input id="firstName" class="form-control2" v-model="form.firstName" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label for="middleName" class="labelForm"> Segundo Nombre </label>
                                <b-form-input id="middleName" class="form-control2" v-model="form.middleName" trim />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label for="firstLastname" class="labelForm"> Primer Apellido </label>
                                <b-form-input id="firstLastname" class="form-control2" v-model="form.firstLastname" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label class="labelForm"> Segundo Apellido </label>
                                <b-form-input id="secondLastname" class="form-control2" v-model="form.secondLastname"
                                    trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>

                    <b-row>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="birthday" valid-feedback="Thank you!">
                                <p class="labelForm">Fecha de Nacimiento</p>
                                <b-form-datepicker id="birthday" class="sizeCalendar form-control2"
                                    v-model="form.birthday"
                                    :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                    locale="es" required />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="placeBirth" valid-feedback="Thank you!">
                                <p class="labelForm">Lugar de Nacimiento</p>
                                <b-form-input id="placeBirth" class="form-control2" v-model="form.placeBirth" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" md="3">
                            <b-form-group label-for="idType" valid-feedback="Thank you!">
                                <p class="labelForm">Tipo de Documento</p>
                                <b-form-select id="idType" class="selectBorder form-control2" :options="idTypes"
                                    v-model="form.idType" required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="idNumber" valid-feedback="Thank you!">
                                <p class="labelForm">Número de Documento</p>
                                <b-form-input id="idNumber" class="form-control2" v-model="form.idNumber" type="number"
                                    trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>

                    <b-row>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="idExpeditionDate" valid-feedback="Thank you!">
                                <p class="labelForm">Fecha de Expedición</p>
                                <b-form-datepicker id="idExpeditionDate" v-model="form.idExpeditionDate"
                                    :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                    locale="es" class="sizeCalendar form-control2" required />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="idExpeditionPlace" valid-feedback="Thank you!">
                                <p class="labelForm">Lugar de Expedición</p>
                                <b-form-input id="idExpeditionPlace" class="form-control2"
                                    v-model="form.idExpeditionPlace" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" md="3">
                            <b-form-group label-for="maritalStatus" valid-feedback="Thank you!">
                                <p class="labelForm">Estado Civil</p>
                                <b-form-select id="maritalStatus" :options="maritalStatuses"
                                    v-model="form.maritalStatus" class="" required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="studies" valid-feedback="Thank you!">
                                <p class="labelForm">Nivel de Estudios</p>
                                <b-form-select id="studies" :options="studies" v-model="form.studies" class=""
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label for="country" class="labelForm"> Pais de Residencia </label>
                                <b-form-input id="country" class="form-control2" v-model="form.country" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label for="department" class="labelForm"> Departamento </label>
                                <b-form-input id="department" class="form-control2" v-model="form.department" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label for="municipality" class="labelForm"> Municipio </label>
                                <b-form-input id="municipality" class="form-control2" v-model="form.municipality" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label class="labelForm"> Barrio </label>
                                <b-form-input id="barrio" class="form-control2" v-model="form.barrio" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>

                    <b-row>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="houseType" valid-feedback="Thank you!">
                                <p class="labelForm">Tipo de Vivienda</p>
                                <b-form-select id="houseType" :options="houseType" v-model="form.houseType" class=""
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" md="3">
                            <b-form-group label-for="time" valid-feedback="Thank you!">
                                <p class="labelForm">Tiempo de residencia</p>
                                <b-form-select class="form-control2" id="time" :options="time" v-model="form.time"
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>


                        <b-col cols="12" md="3">
                            <b-form-group label-for="living" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Personas a cargo</p>
                                <b-form-select id="living" :options="living" v-model="form.living" class="" required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" md="3">
                            <b-form-group label-for="childs" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">¿Cuántos hijos tienes?</p>
                                <b-form-select id="childs" :options="childs" v-model="form.childs" class="" required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>

                    <b-row>

                        <b-col cols="12" md="3">
                            <b-form-group label-for="phoneNumber" valid-feedback="Thank you!">
                                <p class="labelForm"> Número celular </p>
                                <b-form-input id="phoneNumber" class="form-control2" type="number"
                                    v-model="form.phoneNumber" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="phoneNumberFijo" valid-feedback="Thank you!">
                                <p class="labelForm"> Teléfono fijo </p>
                                <b-form-input id="phoneNumberFijo" class="form-control2" v-model="form.phoneNumberFijo"
                                    type="number" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="epsEntity" valid-feedback="Thank you!">
                                <p class="labelForm"> Nombre de la EPS </p>
                                <b-form-input id="epsEntity" class="form-control2" v-model="form.epsEntity" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" md="3">
                            <b-form-group label-for="correspondencyType" valid-feedback="Thank you!">
                                <p class="labelForm">Envio de Correspondencia</p>
                                <b-form-select id="correspondencyType" class="selectBorder form-control2"
                                    :options="correspondencyType" v-model="form.correspondencyType" required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>

                    <b-row>
                        <b-col cols="12" md="6">
                            <b-form-group label-for="address" valid-feedback="Thank you!"
                                :invalid-feedback="invalidFeedback">
                                <p class="labelForm" style="margin-bottom: 10px !important">Dirección</p>
                                <b-form-input id="address" class="form-control2" v-model="form.address" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group label-for="email" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm" style="margin-bottom: 10px !important">Correo electrónico</p>
                                <b-form-input id="email" class="form-control2" type="email" v-model="form.email" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>

                    <p class="detailsSub">Detalles de tus bienes</p>

                    <b-row>
                        <b-col cols="12">
                            <b-form-group label-for="transport" label-class="mb-3" valid-feedback="Thank you!">
                                <b-form-radio-group id="transport" v-model="form.transport" :options="transport"
                                    required />
                            </b-form-group>
                        </b-col>
                    </b-row>

                    <b-row class="othersField">
                        <b-col cols="12" md="6">
                            <b-form-group label-for="others" valid-feedback="Thank you!">
                                <label class="labelForm"> Otro: </label>
                                <b-form-input id="others" placeholder="¿Cuál?" class="form-control2"
                                    v-model="form.others" trim />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                </div>
                <div class="section-separator">
                    <span class="separator-text">Datos del Conyugue</span>
                </div>
                <div class="form-container">
                    <b-row>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label for="firstNameSpouse" class="labelForm"> Primer Nombre </label>
                                <b-form-input id="firstNameSpouse" class="form-control2" v-model="form.firstNameSpouse"
                                    trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label for="middleNameSpouse" class="labelForm"> Segundo Nombre </label>
                                <b-form-input id="middleNameSpouse" class="form-control2"
                                    v-model="form.middleNameSpouse" trim />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label for="firstLastnameSpouse" class="labelForm"> Primer Apellido </label>
                                <b-form-input id="firstLastnameSpouse" class="form-control2"
                                    v-model="form.firstLastnameSpouse" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="secondLastnameSpouse" valid-feedback="Thank you!">
                                <label class="labelForm"> Segundo Apellido </label>
                                <b-form-input id="secondLastnameSpouse" class="form-control2"
                                    v-model="form.secondLastnameSpouse" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="idTypeDocSpouse" valid-feedback="Thank you!">
                                <p class="labelForm">Tipo de Documento</p>
                                <b-form-select id="idTypeDocSpouse" class="selectBorder form-control2"
                                    :options="idTypes" v-model="form.idTypeDocSpouse" required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="idNumberSpouse" valid-feedback="Thank you!">
                                <p class="labelForm">Número de Documento</p>
                                <b-form-input id="idNumberSpouse" class="form-control2" type='number'
                                    v-model="form.idNumberSpouse" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" md="3">
                            <b-form-group label-for="idExpeditionDateSpouse" valid-feedback="Thank you!">
                                <p class="labelForm">Fecha de Expedición</p>
                                <b-form-datepicker id="idExpeditionDateSpouse" v-model="form.idExpeditionDateSpouse"
                                    :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                    locale="es" class="sizeCalendar form-control2" required />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="phoneNumberSpouse" valid-feedback="Thank you!">
                                <p class="labelForm"> Número celular </p>
                                <b-form-input id="phoneNumberSpouse" class="form-control2" type="number"
                                    v-model="form.phoneNumberSpouse" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                </div>
                <div class="section-separator">
                    <span class="separator-text">Información Laboral Del Solicitante</span>
                </div>
                <div class="form-container">
                    <b-row>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="ocupations" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Ocupaciòn</p>
                                <b-form-select id="ocupations" :options="ocupations" v-model="form.ocupations" class=""
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="rents" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Declara renta</p>
                                <b-form-select id="rents" :options="rents" v-model="form.rents" class="" required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="economicActivity" valid-feedback="Thank you!">
                                <p class="labelForm">Actividad Economica principal</p>
                                <b-form-select id="economicActivity" :options="economicActivity"
                                    v-model="form.economicActivity" class="" required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="workName" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Nombre de la Empresa</p>
                                <b-form-input id="workName" class="form-control2" v-model="form.workName" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="workTitle" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Cargo</p>
                                <b-form-input id="workTitle" class="form-control2" v-model="form.workTitle" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="workType" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Tipo contrato</p>
                                <b-form-input id="workType" class="form-control2" v-model="form.workType" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="startDate" valid-feedback="Thank you!">
                                <p class="labelForm">Fecha de vinculacion</p>
                                <b-form-datepicker id="startDate" v-model="form.startDate"
                                    :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                    locale="es" class="sizeCalendar form-control2" required />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="workAddress" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Direccion</p>
                                <b-form-input id="workAddress" class="form-control2" v-model="form.workAddress" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="phoneWork" label-class="mb-3" valid-feedback="Thank you!">
                                <label class="labelForm">Teléfono laboral</label>
                                <b-form-input id="phoneWork" class="form-control2" type="number"
                                    v-model="form.phoneWork" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label class="labelForm"> Ciudad/Depto </label>
                                <b-form-input id="workCity" class="form-control2" v-model="form.workCity" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="bussinesType" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Tipo de Empresa</p>
                                <b-form-select id="bussinesType" :options="bussinesType" v-model="form.bussinesType"
                                    class="" required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="bussinesNit" label-class="mb-3" valid-feedback="Thank you!">
                                <label class="labelForm">Nit dela Empresa</label>
                                <b-form-input id="bussinesNit" class="form-control2" type="number"
                                    v-model="form.bussinesNit" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>

                    <!--
                    <b-row>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="factoryName" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Entidad - Pagaduria</p>
                                <b-form-input id="factoryName" class="form-control2" v-model="form.factoryName" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="workScale" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Escalafón</p>
                                <b-form-input id="workScale" class="form-control2" v-model="form.workScale" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group label-for="addressWork" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Nombre del colegio (docentes)</p>
                                <b-form-input id="addressWork" class="form-control2" v-model="form.addressWork" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label for="workDepartment" class="labelForm"> Departamento </label>
                                <b-form-input id="workDepartment" class="form-control2" v-model="form.workDepartment"
                                    trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label for="workMunicipality" class="labelForm"> Municipio </label>
                                <b-form-input id="workMunicipality" class="form-control2"
                                    v-model="form.workMunicipality" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                    </b-row>-->
                </div>
                <div class="section-separator">
                    <span class="separator-text">Instrucciones de Giro</span>
                </div>
                <div class="form-container">
                    <b-row>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <p class="labelForm">Entidad bancaria</p>
                                <b-form-input id="bankingEntity" class="form-control2" v-model="form.bankingEntity" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <p class="labelForm">Número de cuenta</p>
                                <b-form-input id="accountNumber" class="form-control2" type="number"
                                    v-model="form.accountNumber" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <p class="labelForm">Tipo de cuenta</p>
                                <b-form-select id="accountType" class="form-control2" :options="accountType"
                                    v-model="form.accountType" />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <p class="labelForm">Giro PIN</p>
                                <b-form-input id="pinNumber" class="form-control2" v-model="form.pinNumber" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <!--
                    <b-row>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <p class="labelForm">Forma de desembolso</p>
                                <b-form-select id="expenditureType" class="form-control2" :options="expenditureType"
                                    v-model="form.expenditureType" />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="ingreso" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Total devengado</p>
                                <b-form-input id="ingreso" class="form-control2" v-model="form.ingreso" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="gasto" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Total Descuentos</p>
                                <b-form-input id="gasto" class="form-control2" v-model="form.gasto" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" md="3">
                            <b-form-group label-for="neto" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Neto a pagar</p>
                                <b-form-input id="neto" class="form-control2" v-model="form.neto" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>-->
                </div>
                <div class="section-separator">
                    <span class="separator-text">Referencias del Solicitante</span>
                </div>
                <div class="form-container">
                    <p class="detailsSub">Referencia familiar (que no vivan con el titular)</p>
                    <b-row>
                        <b-col cols="12" md="6">
                            <b-form-group label-for="referenceFName" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Nombre completo</p>
                                <b-form-input id="referenceFName" class="form-control2" v-model="form.referenceFName"
                                    trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="referenceFParent" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Parentesco</p>
                                <b-form-input id="referenceFParent" class="form-control2"
                                    v-model="form.referenceFParent" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="referenceFActivity" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Actividad</p>
                                <b-form-input id="referenceFActivity" class="form-control2"
                                    v-model="form.referenceFActivity" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="referenceFPhone" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Número de celular o fijo</p>
                                <b-form-input id="referenceFPhone" class="form-control2" v-model="form.referenceFPhone"
                                    trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" md="3">
                            <b-form-group label-for="referenceFDepartment" label-class="mb-3"
                                valid-feedback="Thank you!">
                                <p class="labelForm">Departamento</p>
                                <b-form-input id="referenceFDepartment" class="form-control2"
                                    v-model="form.referenceFDepartment" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="referenceFMunicipality" label-class="mb-3"
                                valid-feedback="Thank you!">
                                <p class="labelForm">Municipio</p>
                                <b-form-input id="referenceFMunicipality" class="form-control2"
                                    v-model="form.referenceFMunicipality" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" md="3">
                            <b-form-group label-for="referenceFBarrio" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Barrio</p>
                                <b-form-input id="referenceFBarrio" class="form-control2"
                                    v-model="form.referenceFBarrio" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                    </b-row>
                    <b-row>
                        <b-col cols="12" md="6">
                            <b-form-group label-for="referenceFAddress" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Dirección de residencia</p>
                                <b-form-input id="referenceFAddress" v-model="form.referenceFAddress"
                                    class="form-control2" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group label-for="referenceFState" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Estado actual</p>
                                <b-form-input id="referenceFState" class="form-control2" v-model="form.referenceFState"
                                    trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <p class="detailsSub">Referencia personal</p>
                    <b-row>
                        <b-col cols="12" md="6">
                            <b-form-group label-for="referenceName" label-class="mb-3" valid-feedback="Thank you!"
                                :invalid-feedback="invalidFeedback">
                                <p class="labelForm">Nombre completo</p>
                                <b-form-input id="referenceName" class="form-control2" v-model="form.referenceName" trim
                                    required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="referenceParent" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Parentesco</p>
                                <b-form-input id="referenceParent" class="form-control2" v-model="form.referenceParent"
                                    trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="referenceActivity" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Actividad</p>
                                <b-form-input id="referenceActivity" class="form-control2"
                                    v-model="form.referenceActivity" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="referencePhone" label-class="mb-3" valid-feedback="Thank you!"
                                :invalid-feedback="invalidFeedback">
                                <p class="labelForm">Número de celular o fijo</p>
                                <b-form-input id="referencePhone" type="number" class="form-control2"
                                    v-model="form.referencePhone" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="referenceDepartment" label-class="mb-3"
                                valid-feedback="Thank you!">
                                <p class="labelForm">Departamento</p>
                                <b-form-input id="referenceDepartment" class="form-control2"
                                    v-model="form.referenceDepartment" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="referenceMunicipality" label-class="mb-3"
                                valid-feedback="Thank you!">
                                <p class="labelForm">Municipio</p>
                                <b-form-input id="referenceMunicipality" class="form-control2"
                                    v-model="form.referenceMunicipality" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="referenceBarrio" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Barrio</p>
                                <b-form-input id="referenceBarrio" class="form-control2" v-model="form.referenceBarrio"
                                    trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                    </b-row>

                    <b-row>
                        <b-col cols="12" md="6">
                            <b-form-group label-for="referenceAddress" label-class="mb-3" valid-feedback="Thank you!">
                                <p class="labelForm">Dirección de residencia</p>
                                <b-form-input id="referenceAddress" v-model="form.referenceAddress"
                                    class="form-control2" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                </div>

                <div class="section-separator">
                    <span class="separator-text">DATOS DE OPERACIONES INTERNACIONALES</span>
                </div>
                <div class="form-container">
                    <b-row>
                        <b-col cols="12" md="6">
                            <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">¿Realiza operaciones en
                                moneda extranjera?</p>
                            <b-form-radio-group id="operationForeign" class="labelGen mb-3"
                                v-model="form.operationForeign" :options="operationForeign" required>
                                <b-form-valid-feedback>Thank you!</b-form-valid-feedback>
                                <b-form-invalid-feedback>Este campo es requerido.</b-form-invalid-feedback>
                            </b-form-radio-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">¿Posee cuentas en el
                                exterior?</p>
                            <b-form-radio-group id="accountForeign" class="labelGen mb-3" v-model="form.accountForeign"
                                :options="accountForeign" required>
                                <b-form-valid-feedback>Thank you!</b-form-valid-feedback>
                                <b-form-invalid-feedback>Este campo es requerido.</b-form-invalid-feedback>
                            </b-form-radio-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" md="12">
                            <div v-if="form.accountForeign === 'SI'">
                                <b-form-group
                                    label="Si su actividad económica implica transacciones en moneda extranjera, señale los tipos de transacción"
                                    v-slot="{ ariaDescribedby }">
                                    <b-form-checkbox-group id="checkbox-group-1" v-model="form.foreignAccountTypes"
                                        :options="foreignAccountOptions" :aria-describedby="ariaDescribedby"
                                        name="foreignAccountTypes"></b-form-checkbox-group>
                                </b-form-group>
                            </div>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" md="6">
                            <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">¿Realiza actividades con
                                cripto monedas?</p>
                            <b-form-radio-group id="operationCrypto" class="labelGen mb-3"
                                v-model="form.operationCrypto" :options="operationCrypto" required>
                                <b-form-valid-feedback>Thank you!</b-form-valid-feedback>
                                <b-form-invalid-feedback>Este campo es requerido.</b-form-invalid-feedback>
                            </b-form-radio-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">¿Desarrolla actividades
                                APNFD*?</p>
                            <b-form-radio-group id="activityAPNFD" class="labelGen mb-3" v-model="form.activityAPNFD"
                                :options="activityAPNFD" required>
                                <b-form-valid-feedback>Thank you!</b-form-valid-feedback>
                                <b-form-invalid-feedback>Este campo es requerido.</b-form-invalid-feedback>
                            </b-form-radio-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" md="12">
                            <div v-if="form.activityAPNFD === 'SI'">
                                <b-form-group label="PRODUCTOS EN MONEDA EXTRANJERA" v-slot="{ ariaDescribedby }">
                                    <b-row>
                                        <b-col cols="12" md="3">
                                            <b-form-group label-for="foreignEntity" label-class="mb-3"
                                                valid-feedback="Thank you!" :invalid-feedback="invalidFeedback">
                                                <p class="labelForm">Nombre de la entidad</p>
                                                <b-form-input id="foreignEntity" class="form-control2"
                                                    v-model="form.foreignEntity" trim />
                                            </b-form-group>
                                        </b-col>
                                        <b-col cols="12" md="3">
                                            <b-form-group label-for="foreignAccount" label-class="mb-3"
                                                valid-feedback="Thank you!" :invalid-feedback="invalidFeedback">
                                                <p class="labelForm">No. De la cuenta / Producto</p>
                                                <b-form-input id="foreignAccount" class="form-control2"
                                                    v-model="form.foreignAccount" trim />
                                            </b-form-group>
                                        </b-col>
                                        <b-col cols="12" md="3">
                                            <b-form-group label-for="foreignProduct" label-class="mb-3"
                                                valid-feedback="Thank you!">
                                                <p class="labelForm">Tipo de producto</p>
                                                <b-form-input id="foreignProduct" class="form-control2"
                                                    v-model="form.foreignProduct" trim />
                                            </b-form-group>
                                        </b-col>
                                        <b-col cols="12" md="3">
                                            <b-form-group label-for="foreignAmount" label-class="mb-3"
                                                valid-feedback="Thank you!">
                                                <p class="labelForm">Monto de operación</p>
                                                <b-form-input id="foreignAmount" class="form-control2"
                                                    v-model="form.foreignAmount" trim />
                                            </b-form-group>
                                        </b-col>
                                    </b-row>
                                    <b-row>
                                        <b-col cols="12" md="4">
                                            <b-form-group label-for="foreignCurrency" label-class="mb-3"
                                                valid-feedback="Thank you!" :invalid-feedback="invalidFeedback">
                                                <p class="labelForm">Moneda</p>
                                                <b-form-input id="foreignCurrency" class="form-control2"
                                                    v-model="form.foreignCurrency" trim />
                                            </b-form-group>
                                        </b-col>
                                        <b-col cols="12" md="4">
                                            <b-form-group label-for="foreignCity" label-class="mb-3"
                                                valid-feedback="Thank you!" :invalid-feedback="invalidFeedback">
                                                <p class="labelForm">Ciudad</p>
                                                <b-form-input id="foreignCity" class="form-control2"
                                                    v-model="form.foreignCity" trim />
                                            </b-form-group>
                                        </b-col>
                                        <b-col cols="12" md="4">
                                            <b-form-group label-for="foreignCountry" label-class="mb-3"
                                                valid-feedback="Thank you!">
                                                <p class="labelForm">país</p>
                                                <b-form-input id="foreignCountry" class="form-control2"
                                                    v-model="form.foreignCountry" trim />
                                            </b-form-group>
                                        </b-col>
                                    </b-row>
                                </b-form-group>
                            </div>
                        </b-col>
                    </b-row>
                </div>
                <div class="section-separator">
                    <span class="separator-text">INFORMACION PEPS</span>
                </div>
                <div class="form-container">
                    <b-row>
                        <b-col cols="12" md="6">
                            <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">¿La Sociedad lo
                                Identifica como Personaje Público?</p>
                            <b-form-radio-group id="personPublic" class="labelGen mb-3" v-model="form.personPublic"
                                :options="personPublic" required>
                                <b-form-valid-feedback>Thank you!</b-form-valid-feedback>
                                <b-form-invalid-feedback>Este campo es requerido.</b-form-invalid-feedback>
                            </b-form-radio-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">¿Usted Maneja Recursos
                                Públicos?</p>
                            <b-form-radio-group id="resourcePublic" class="labelGen mb-3" v-model="form.resourcePublic"
                                :options="resourcePublic" required>
                                <b-form-valid-feedback>Thank you!</b-form-valid-feedback>
                                <b-form-invalid-feedback>Este campo es requerido.</b-form-invalid-feedback>
                            </b-form-radio-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" md="6">
                            <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">¿Las decisiones a su
                                cargo influyen en la política o impactan en la sociedad?</p>
                            <b-form-radio-group id="politicalInfluence" class="labelGen mb-3"
                                v-model="form.politicalInfluence" :options="politicalInfluence" required>
                                <b-form-valid-feedback>Thank you!</b-form-valid-feedback>
                                <b-form-invalid-feedback>Este campo es requerido.</b-form-invalid-feedback>
                            </b-form-radio-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">¿Pertenece a algún grupo
                                étnico?</p>
                            <b-form-radio-group id="ethnicGroup" class="labelGen mb-3" v-model="form.ethnicGroup"
                                :options="ethnicGroup" required>
                                <b-form-valid-feedback>Thank you!</b-form-valid-feedback>
                                <b-form-invalid-feedback>Este campo es requerido.</b-form-invalid-feedback>
                            </b-form-radio-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" md="12">
                            <div v-if="form.ethnicGroup === 'SI'">
                                <b-row>
                                    <b-col cols="12" md="12">
                                        <b-form-group label-for="ethnicName" label-class="mb-3"
                                            valid-feedback="Thank you!" :invalid-feedback="invalidFeedback">
                                            <p class="labelForm">Escriba su grupo étnico</p>
                                            <b-form-input id="ethnicName" class="form-control2"
                                                v-model="form.ethnicName" trim required />
                                            <b-form-invalid-feedback> Este campo es requerido.
                                            </b-form-invalid-feedback>
                                        </b-form-group>
                                    </b-col>
                                </b-row>
                            </div>
                        </b-col>
                    </b-row>
                </div>
                <div class="section-separator">
                    <span class="separator-text">INFORMACION FINANCIERA</span>
                </div>
                <div class="form-container">
                    <b-row>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label for="financialEmploymentIncome" class="labelForm">Ingresos laborales: </label>
                                <b-form-input id="financialEmploymentIncome" class="form-control2" type="number"
                                    v-model="form.financialEmploymentIncome" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label for="financialFeesCommissions" class="labelForm">Honorarios y comisiones:
                                </label>
                                <b-form-input id="financialFeesCommissions" class="form-control2" type="number"
                                    v-model="form.financialFeesCommissions" trim />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" md="3">
                            <b-form-group>
                                <label for="financialOtherIncome" class="labelForm">Otros ingresos ¿cuáles?: </label>
                                <b-form-input id="financialOtherIncome" class="form-control2" type="number"
                                    v-model="form.financialOtherIncome" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="3">
                            <b-form-group label-for="financialTotalIncome" valid-feedback="Thank you!">
                                <label class="labelForm"> TOTAL INGRESOS: </label>
                                <b-form-input id="financialTotalIncome" class="form-control2" type="number"
                                    v-model="form.financialTotalIncome" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" md="6">
                            <b-form-group>
                                <label for="financialFixedAssets" class="labelForm">Activos Fijos (Ahorros, Inversiones,
                                    Propiedades, otros):</label>
                                <b-form-input id="financialFixedAssets" class="form-control2" type="number"
                                    v-model="form.financialFixedAssets" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group>
                                <label for="financialTotalAssets" class="labelForm">TOTAL ACTIVOS: </label>
                                <b-form-input id="financialTotalAssets" class="form-control2" type="number"
                                    v-model="form.financialTotalAssets" trim />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" md="4">
                            <b-form-group>
                                <label for="financialFee" class="labelForm">Arrendamiento o cuota de vivienda:
                                </label>
                                <b-form-input id="financialFee" class="form-control2" type="number"
                                    v-model="form.financialFee" trim />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>

                        <b-col cols="12" md="4">
                            <b-form-group>
                                <label for="financialLiabilities" class="labelForm">Pasivos financieros (Deudas
                                    Financieras): </label>
                                <b-form-input id="financialLiabilities" class="form-control2" type="number"
                                    v-model="form.financialLiabilities" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="4">
                            <b-form-group label-for="financialCurrentLiabilities" valid-feedback="Thank you!">
                                <label class="labelForm">Pasivos corrientes (Deudas con Terceros): </label>
                                <b-form-input id="financialCurrentLiabilities" class="form-control2" type="number"
                                    v-model="form.financialCurrentLiabilities" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" md="4">
                            <b-form-group>
                                <label for="financialFamilyExpenses" class="labelForm">Gastos familiares: </label>
                                <b-form-input id="financialFamilyExpenses" class="form-control2" type="number"
                                    v-model="form.financialFamilyExpenses" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="4">
                            <b-form-group>
                                <label for="financialOtherLiabilities" class="labelForm">Otros Pasivos ¿Cuáles?:
                                </label>
                                <b-form-input id="financialOtherLiabilities" class="form-control2" type="number"
                                    v-model="form.financialOtherLiabilities" trim required />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="4">
                            <b-form-group>
                                <label for="financialTotalLiabilities" class="labelForm">TOTAL PASIVOS: </label>
                                <b-form-input id="financialTotalLiabilities" class="form-control2" type="number"
                                    v-model="form.financialTotalLiabilities" trim />
                                <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                </div>
                |<div class="section-separator">
                    <span class="separator-text">DECLARACIÓN FATCA - CRS</span>
                </div>

                <div class="form-container">
                    <b-row>
                        <table>

                            <tbody>
                                <tr>
                                    <td>
                                        <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">¿Es usted una
                                            persona con ciudadanía estadounidense o de otro país?</p>
                                    </td>
                                    <td>
                                        <b-form-radio-group id="questionFatca1" class="labelGen mb-3"
                                            v-model="form.questionFatca1" :options="questionFatca1" required>
                                            <b-form-valid-feedback> Thank you! </b-form-valid-feedback>
                                            <b-form-invalid-feedback> Este campo es requerido.
                                            </b-form-invalid-feedback>
                                        </b-form-radio-group>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">¿Es usted
                                            residente en los Estados Unidos o posee Green Card?
                                        </p>
                                    </td>
                                    <td>
                                        <b-form-radio-group id="questionFatca2" class="labelGen mb-3"
                                            v-model="form.questionFatca2" :options="questionFatca2" required>
                                            <b-form-valid-feedback> Thank you! </b-form-valid-feedback>
                                            <b-form-invalid-feedback> Este campo es requerido.
                                            </b-form-invalid-feedback>
                                        </b-form-radio-group>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">¿Es usted
                                            sujeto obligado tributariamente en los Estados Unidos u otro país?</p>
                                    </td>
                                    <td>
                                        <b-form-radio-group id="questionFatca3" class="labelGen mb-3"
                                            v-model="form.questionFatca3" :options="questionFatca3" required>
                                            <b-form-valid-feedback> Thank you! </b-form-valid-feedback>
                                            <b-form-invalid-feedback> Este campo es requerido.
                                            </b-form-invalid-feedback>
                                        </b-form-radio-group>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">¿Ha otorgado
                                            poderes de representación legal o autorización de firma vigente concedido a
                                            una persona que viva en el exterior?</p>
                                    </td>
                                    <td>
                                        <b-form-radio-group id="questionFatca4" class="labelGen mb-3"
                                            v-model="form.questionFatca4" :options="questionFatca4" required>
                                            <b-form-valid-feedback> Thank you! </b-form-valid-feedback>
                                            <b-form-invalid-feedback> Este campo es requerido.
                                            </b-form-invalid-feedback>
                                        </b-form-radio-group>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div v-if="form.questionFatca4 === 'SI'">
                                            <b-form-group
                                                label="Si su respuesta es SI, por favor, diligencie la siguiente información:"
                                                v-slot="{ ariaDescribedby }">
                                                <b-row>
                                                    <b-col cols="12" md="4">
                                                        <b-form-group label-for="questionFatca4Country"
                                                            label-class="mb-3" valid-feedback="Thank you!"
                                                            :invalid-feedback="invalidFeedback">
                                                            <p class="labelForm">País</p>
                                                            <b-form-input id="questionFatca4Country"
                                                                class="form-control2"
                                                                v-model="form.questionFatca4Country" trim />
                                                        </b-form-group>
                                                    </b-col>
                                                    <b-col cols="12" md="4">
                                                        <b-form-group label-for="questionFatca4TaxId" label-class="mb-3"
                                                            valid-feedback="Thank you!"
                                                            :invalid-feedback="invalidFeedback">
                                                            <p class="labelForm">Identificación Tributaria</p>
                                                            <b-form-input id="questionFatca4TaxId" class="form-control2"
                                                                v-model="form.questionFatca4TaxId" trim />
                                                        </b-form-group>
                                                    </b-col>
                                                    <b-col cols="12" md="4">
                                                        <b-form-group label-for="questionFatca4PowerObject"
                                                            label-class="mb-3" valid-feedback="Thank you!">
                                                            <p class="labelForm">Objeto de poder</p>
                                                            <b-form-input id="questionFatca4PowerObject"
                                                                class="form-control2"
                                                                v-model="form.questionFatca4PowerObject" trim />
                                                        </b-form-group>
                                                    </b-col>
                                                </b-row>
                                            </b-form-group>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </b-row>
                </div>


                <div class="section-separator">
                    <span class="separator-text">CONOCIMIENTO DE LOS COMPROMISOS Y SEGUROS ADQUIRIDOS</span>
                </div>

                <div class="form-container">
                    <b-row>
                        <table>

                            <tbody>
                                <tr>
                                    <td>
                                        <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">Conoce y
                                            entiende las características, condiciones y coberturas del(los) seguro(s)
                                            solicitado (s), y le explicaron el costo del (los) seguro(s) solicitado(s)
                                            c/u.</p>
                                    </td>
                                    <td>
                                        <b-form-radio-group id="question1" class="labelGen mb-3"
                                            v-model="form.question1" :options="question1" required>
                                            <b-form-valid-feedback> Thank you! </b-form-valid-feedback>
                                            <b-form-invalid-feedback> Este campo es requerido.
                                            </b-form-invalid-feedback>
                                        </b-form-radio-group>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">Conoce y
                                            entiende la forma de pago de la prima de (los) seguro (s) solicitado (s).
                                        </p>
                                    </td>
                                    <td>
                                        <b-form-radio-group id="question2" class="labelGen mb-3"
                                            v-model="form.question2" :options="question2" required>
                                            <b-form-valid-feedback> Thank you! </b-form-valid-feedback>
                                            <b-form-invalid-feedback> Este campo es requerido.
                                            </b-form-invalid-feedback>
                                        </b-form-radio-group>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">En caso de
                                            adquirir cualquier otro(s) producto(s) de crédito con CK COMERCIALIZADORA UN
                                            MUNDO DE OPORTUNIDADES S.A.S. o con quien represente sus derechos e
                                            intereses u ostente en el futuro la calidad de acreedor o sea legítimo
                                            tenedor, diferente a la libranza, acepta cancelar el valor adeudado del
                                            (los) mismo(s) antes de cancelar la libranza.</p>
                                    </td>
                                    <td>
                                        <b-form-radio-group id="question3" class="labelGen mb-3"
                                            v-model="form.question3" :options="question3" required>
                                            <b-form-valid-feedback> Thank you! </b-form-valid-feedback>
                                            <b-form-invalid-feedback> Este campo es requerido.
                                            </b-form-invalid-feedback>
                                        </b-form-radio-group>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">Autoriza a CK
                                            COMERCIALIZADORA UN MUNDO DE OPORTUNIDADES S.A.S. o a quien represente sus
                                            derechos e intereses u ostente en el futuro la calidad de acreedor o sea
                                            legítimo tenedor, a ampliar el plazo, en el evento en que se presente mora
                                            en algunos de los productos adquiridos bajo mi nombre.</p>
                                    </td>
                                    <td>
                                        <b-form-radio-group id="question4" class="labelGen mb-3"
                                            v-model="form.question4" :options="question4" required>
                                            <b-form-valid-feedback> Thank you! </b-form-valid-feedback>
                                            <b-form-invalid-feedback> Este campo es requerido.
                                            </b-form-invalid-feedback>
                                        </b-form-radio-group>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="font-size: 16px; font-weight: 400; line-height: 20.83px">Conoce y
                                            entiende los valores descontados de manera anticipada.</p>
                                    </td>
                                    <td>
                                        <b-form-radio-group id="question5" class="labelGen mb-3"
                                            v-model="form.question5" :options="question5" required>
                                            <b-form-valid-feedback> Thank you! </b-form-valid-feedback>
                                            <b-form-invalid-feedback> Este campo es requerido.
                                            </b-form-invalid-feedback>
                                        </b-form-radio-group>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </b-row>

                    <b-row v-if="form.idNumber">
                        <b-col class="text-right">
                            <b-form-checkbox v-model="form.decevalProcess" class="mb-3">
                                ¿Desea realizar la consulta y proceso DECEVAL al guardar el registro de crédito?
                            </b-form-checkbox>
                            <b-button type="submit" variant="primary" :disabled="savingData">
                                <div v-if="savingData">
                                    <b-spinner small></b-spinner>
                                    <span>Guardando...</span>
                                </div>
                                <span v-else>Guardar</span>
                            </b-button>
                        </b-col>
                    </b-row>
                </div>
                <div class="seccion" v-if="pagaduriasClient">
                    <b-row>
                        <b-col cols="12" md="6">
                            <h3 class="tituleInfo">3. Oferta y aceptación</h3>
                            <p class="titelFinalForm">¡Debes verificar las condiciones de tu crédito!</p>

                            <div v-if="pagaduriasClient">
                                <span v-if="
                                    Object.values(pagaduriasClient)
                                        .map(item => item == null)
                                        .every(item => item == true)
                                " class="d-block">
                                    No hay ofertas disponibles
                                </span>
                                <b-form-group v-else label="Ofertas">
                                    <b-form-checkbox-group v-model="selectedOffers">
                                        <template v-for="pagaduriasType in pagaduriasTypes">
                                            <b-form-checkbox v-if="pagaduriasClient[pagaduriasType.key]"
                                                :value="pagaduriasType.value"
                                                :key="`pagaduria-option-${pagaduriasType.value}`">
                                                {{ pagaduriasType.label }}
                                            </b-form-checkbox>
                                        </template>
                                    </b-form-checkbox-group>
                                </b-form-group>
                            </div>

                            <div v-if="selectedOffers.length > 0">
                                <div v-for="offer in selectedOffers" :key="offer">
                                    <h2 class="text-caribbean-green">{{ offer }}</h2>
                                    <b-list-group class="mb-3">
                                        <b-list-group-item v-for="item in items[offer]" :key="item.key"
                                            class="listTableGroup">
                                            <b-row class="rowTable">
                                                <b-col class="nameTable">{{ item.key }}</b-col>
                                                <b-col class="numberTable">{{ item.value | currency }}</b-col>
                                            </b-row>
                                        </b-list-group-item>
                                    </b-list-group>
                                </div>
                            </div>
                        </b-col>
                        <b-col cols="12" md="6" class="colDataForm">
                            <div class="infoFinalContainer">
                                <p class="detailsSub">Monto solicitado</p>
                                <h2 class="numberMonto">
                                    {{ this.creditInfo.amount | currency }}
                                </h2>
                                <p class="detailsSub">Plazo de pago</p>
                                <h3 class="dateSub">31 - Dic - 2023</h3>
                                <div class="btnContainer">
                                    <b-button type="submit" class="btnAceptCredit">Aceptar</b-button>

                                    <b-modal id="modal-1" class="modalFinalCredit" @ok="approveValue" centered>
                                        <!-- <img
                                        src="/img/logoKapital_White.png"
                                        alt="Logo KapitalBank White "
                                        class="imgLogoPopup"
                                    /> -->
                                        <div class="containerInfoModal">
                                            <p class="namePopup">{{ form.firstName }} {{ form.middleName }}</p>
                                            <p class="lastNamePopup">
                                                {{ form.firstLastname }} {{ form.secondLastname }}
                                            </p>
                                            <!-- <p class="detailsSub">¡eres nuestro cliente</p>
                                        <p class="fontBillion">más importante!</p> -->
                                            <p class="textPopup">
                                                Espera un poco
                                                <strong>
                                                    estamos por<br />
                                                    terminar,
                                                </strong>
                                                nuestro sistema esta<br />analizando tu solicitud
                                            </p>
                                        </div>
                                        <template #modal-footer="{ ok }">
                                            <b-button class="btnAceptCredit" @click="ok()"> Aceptar </b-button>
                                        </template>
                                    </b-modal>
                                </div>
                            </div>
                        </b-col>
                    </b-row>
                </div>
            </b-form-group>
        </b-form>

        <b-form @submit.prevent="executeSpecificFunction" v-if="creditInfo">
            <div class="section-separator">
                <span class="separator-text">Datos Libranza</span>
            </div>
            <div class="form-container">

                <b-row>
                    <b-col cols="12" md="6">
                        <b-form-group label-for="payerName" label-class="mb-3" valid-feedback="Thank you!">
                            <p class="labelForm">Pagador y/o Tesorero y/o Jefe de Nómina</p>
                            <b-form-input id="payerName" class="form-control2" v-model="form.payerName" trim required />
                            <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col cols="12" md="3">
                        <b-form-group label-for="payerCity" label-class="mb-3" valid-feedback="Thank you!">
                            <p class="labelForm">ciudad</p>
                            <b-form-input id="payerCity" class="form-control2" v-model="form.payerCity" trim required />
                            <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col cols="12" md="3">
                        <b-form-group label-for="dateDiligence" valid-feedback="Thank you!">
                            <p class="labelForm">Fecha Diligenciamiento</p>
                            <b-form-datepicker id="dateDiligence" v-model="form.dateDiligence"
                                :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }" locale="es"
                                class="sizeCalendar form-control2" required />
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col cols="12" md="3">
                        <b-form-group label-for="creditValueL" label-class="mb-3" valid-feedback="Thank you!">
                            <p class="labelForm">Valor del crédito</p>
                            <b-form-input id="creditValueL" class="form-control2" type="number"
                                v-model="form.creditValueL" trim required />
                            <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>

                    <b-col cols="12" md="3">
                        <b-form-group label-for="rateL" label-class="mb-3" valid-feedback="Thank you!">
                            <p class="labelForm">Tasa (%)</p>
                            <b-form-input id="rateL" class="form-control2" type="number" v-model="form.rateL" trim
                                required />
                            <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col cols="12" md="3">
                        <b-form-group label-for="quotaValueL" label-class="mb-3" valid-feedback="Thank you!">
                            <p class="labelForm">Valor cuota</p>
                            <b-form-input id="quotaValueL" class="form-control2" type="number"
                                v-model="form.quotaValueL" trim required />
                            <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>

                    <b-col cols="12" md="3">
                        <b-form-group label-for="quotaL" label-class="mb-3" valid-feedback="Thank you!">
                            <p class="labelForm">Nº cuotas</p>
                            <b-form-input id="quotaL" class="form-control2" type="number" v-model="form.quotaL" trim
                                required />
                            <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>

                </b-row>
                <b-row>
                    <b-col cols="12" md="3">
                        <b-form-group label-for="totalCreditValueL" label-class="mb-3" valid-feedback="Thank you!">
                            <p class="labelForm">Valor total del crédito</p>
                            <b-form-input id="totalCreditValueL" v-model="form.totalCreditValueL" class="form-control2"
                                type="number" trim required />
                            <b-form-invalid-feedback> Este campo es requerido. </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col cols="12" md="3">
                        <b-form-group label-for="startDateL" valid-feedback="Thank you!">
                            <p class="labelForm">Fecha inicial</p>
                            <b-form-datepicker id="startDateL" v-model="form.startDateL"
                                :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }" locale="es"
                                class="sizeCalendar form-control2" required />
                        </b-form-group>
                    </b-col>
                    <b-col cols="12" md="3">
                        <b-form-group label-for="endDateL" valid-feedback="Thank you!">
                            <p class="labelForm">Fecha final</p>
                            <b-form-datepicker id="endDateL" v-model="form.endDateL"
                                :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }" locale="es"
                                class="sizeCalendar form-control2" required />
                        </b-form-group>
                    </b-col>
                </b-row>


                <b-row>
                    <b-col class="text-right">
                        <b-button type="submit" variant="primary" :disabled="savingData">
                            <div v-if="savingData">
                                <b-spinner small></b-spinner>
                                <span>Guardando...</span>
                            </div>
                            <span v-else>Guardar</span>
                        </b-button>
                    </b-col>
                </b-row>
            </div>
        </b-form>

    </div>
</template>

<script>
import axios from 'axios';
import { mapState } from 'vuex';
import { validationMixin } from 'vuelidate';
import { degrees, PDFDocument, rgb } from 'pdf-lib';
import fontkit from '@pdf-lib/fontkit';

export default {
    mixins: [validationMixin],
    data() {
        return {
            form: {
                gender: null,
                firstName: '',
                middleName: '',
                firstLastname: '',
                secondLastname: '',
                birthday: '',
                placeBirth: '',
                idType: '',
                idNumber: '',
                idExpeditionDate: '',
                idExpeditionPlace: '',
                maritalStatus: '',
                studies: '',
                country: '',
                department: '',
                municipality: '',
                barrio: '',
                houseType: '',
                time: '',
                living: '',
                childs: '',
                phoneNumber: '',
                phoneNumberFijo: '',
                epsEntity: '',
                correspondencyType: '',
                address: '',
                email: '',
                transport: '',
                others: '',
                firstNameSpouse: '',
                middleNameSpouse: '',
                firstLastnameSpouse: '',
                secondLastnameSpouse: '',
                idNumberSpouse: '',
                idTypeDocSpouse: '',
                idExpeditionDateSpouse: '',
                phoneNumberSpouse: '',
                ocupations: '',
                rents: '',
                economicActivity: '',
                workName: '',
                workTitle: '',
                workType: '',
                startDate: '',
                workAddress: '',
                phoneWork: '',
                workCity: '',
                bussinesType: '',
                bussinesNit: '',
                factoryName: '',//no
                workScale: '',//no
                addressWork: '',//no
                workDepartment: '',//no
                workMunicipality: '',//no
                bankingEntity: '',
                accountNumber: '',
                accountType: '',
                pinNumber: '',
                expenditureType: '', //no            
                ingreso: '',//no
                gasto: '',//no
                neto: '',//no
                referenceFName: '',
                referenceFParent: '',
                referenceFActivity: '',
                referenceFPhone: '',
                referenceFDepartment: '',
                referenceFMunicipality: '',
                referenceFBarrio: '',
                referenceFAddress: '',
                referenceFState: '',
                referenceName: '',
                referenceParent: '',
                referenceActivity: '',
                referencePhone: '',
                referenceDepartment: '',
                referenceMunicipality: '',
                referenceBarrio: '',
                referenceAddress: '',
                operationForeign: null,
                accountForeign: null,
                operationCrypto: null,
                activityAPNFD: null,
                foreignEntity: '',
                foreignAccount: '',
                foreignProduct: '',
                foreignAmount: '',
                foreignCurrency: '',
                foreignCity: '',
                foreignCountry: '',
                personPublic: null,
                resourcePublic: null,
                politicalInfluence: null,
                ethnicGroup: null,
                ethnicName: '',
                financialEmploymentIncome: '',
                financialFeesCommissions: '',
                financialOtherIncome: '',
                financialTotalIncome: '',
                financialFixedAssets: '',
                financialTotalAssets: '',
                financialFee: '',
                financialLiabilities: '',
                financialCurrentLiabilities: '',
                financialFamilyExpenses: '',
                financialOtherLiabilities: '',
                financialTotalLiabilities: '',
                questionFatca1: null,
                questionFatca2: null,
                questionFatca3: null,
                questionFatca4: null,
                questionFatca4Country: '',
                questionFatca4TaxId: '',
                questionFatca4PowerObject: '',
                foreignAccountTypes: [],
                question1: null,
                question2: null,
                question3: null,
                question4: null,
                question5: null,
                pagaduria: '',
                movilePlan: '',
                ours: '',
                decevalProcess: false
            },
            creditInfo: null,
            genders: [
                { text: 'Masculino', value: 'M' },
                { text: 'Femenino', value: 'F' }
            ],
            movilePlan: [
                { text: 'Prepago', value: 'Y' },
                { text: 'Pospago', value: 'X' }
            ],
            transport: [
                { text: 'Carro', value: 'Y' },
                { text: 'Moto', value: 'X' },
                { text: 'Casa/Apartamento', value: 'X' },
                { text: 'Ninguno', value: 'Z' }
            ],
            ours: [
                { text: 'Redes sociales', value: 'Y' },
                { text: 'Un amigo', value: 'X' },
                { text: 'Propaganda y publicidad', value: 'Z' }
            ],
            time: [
                { text: '1 año', value: '1' },
                { text: '3 años', value: '3' },
                { text: '6 años', value: '6' },
                { text: '9+ años', value: '9+' }
            ],
            living: [
                { text: '0', value: '0' },
                { text: '1', value: '1' },
                { text: '2', value: '2' },
                { text: '3', value: '3' },
                { text: '4', value: '4' },
                { text: '+', value: 'x' }
            ],
            childs: [
                { text: '0', value: '0' },
                { text: '1', value: '1' },
                { text: '2', value: '2' },
                { text: '3', value: '3' },
                { text: '4', value: '4' },
                { text: '+', value: 'x' }
            ],
            ocupations: [
                { text: 'Empleado', value: 'empleado' },
                { text: 'Pensionado', value: 'pensionado' },
                { text: 'Ama de casa', value: 'amacasa' },
                { text: 'Estudiante', value: 'estudiante' }
            ],
            rents: [
                { text: 'SI', value: 'SI' },
                { text: 'NO', value: 'NO' }
            ],
            bussinesType: [
                { text: 'Publica', value: 'Publica' },
                { text: 'Privada', value: 'Privada' },
                { text: 'Mixta', value: 'Mixta' }
            ],
            idTypes: [
                { text: 'Cédula de Ciudadanía', value: 'CC' },
                { text: 'Cédula de Extranjería', value: 'CE' },
                { text: 'Tarjeta de Identidad', value: 'TI' },
                { text: 'Registro Civil', value: 'RC' }
            ],
            accountType: [
                { text: 'Corriente', value: 'CORRIENTE' },
                { text: 'Ahorros', value: 'AHORROS' }
            ],
            expenditureType: [
                { text: 'Consignación cuenta', value: 'Consignacion' },
                { text: 'Giro masivo BBVA ', value: 'Giro' }
            ],
            maritalStatuses: ['Casado (a)', 'Soltero (a)', 'Union Libre', 'Viudo (a)'],
            studies: ['Bachiller', 'Tecnologo', 'Universitario', 'Especializacion'],
            houseType: ['Familiar', 'Arrendada', 'Propia'],
            correspondencyType: ['Casa', 'Mail'],
            economicActivity: ['Activo', 'Pensionado'],
            operationForeign: ['SI', 'NO'],
            accountForeign: ['SI', 'NO'],
            operationCrypto: ['SI', 'NO'],
            activityAPNFD: ['SI', 'NO'],
            personPublic: ['SI', 'NO'],
            resourcePublic: ['SI', 'NO'],
            politicalInfluence: ['SI', 'NO'],
            ethnicGroup: ['SI', 'NO'],
            question1: ['SI', 'NO'],
            question2: ['SI', 'NO'],
            question3: ['SI', 'NO'],
            question4: ['SI', 'NO'],
            question5: ['SI', 'NO'],
            questionFatca1: ['SI', 'NO'],
            questionFatca2: ['SI', 'NO'],
            questionFatca3: ['SI', 'NO'],
            questionFatca4: ['SI', 'NO'],
            state: null,
            savingData: false,
            pagaduriasClient: null,
            selectedOffers: [],
            foreignAccountOptions: [
                { text: 'Exportación', value: 'exp' },
                { text: 'Importación', value: 'imp' },
                { text: 'Inversiones', value: 'inv' },
                { text: 'Préstamo en moneda extranjera', value: 'monex' },
                { text: 'Otra', value: 'otra' }
            ],            // Definimos los arrays como propiedades de data
            unidades: ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'],
            decimas: ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'],
            decenas: ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'],
            centenas: ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS']
        };
    },
    mounted() {
        let localCreditInfo = window.localStorage.getItem('creditInfo');
        let creditInfo = this.creditInfo ? this.creditInfo : JSON.parse(localCreditInfo);
        this.creditInfo = creditInfo;
        this.form.pagaduria = creditInfo.pagaduria;

        delete creditInfo['pagaduria'];

        if (this.isAwaiting) {
            this.$swal({
                icon: 'success',
                title: 'Su solicitud ha sido enviada'
            }).then(() => {
                window.location.href = '/home';
            });
        }

        if (this.isInvalid) {
            const msg = 'Se presentaron errores al crear el(los) pagare(s). Por favor verifique su información.';

            this.$bvToast.toast(msg, {
                title: 'Ha ocurrido un error',
                autoHideDelay: 5000,
                variant: 'warning',
                solid: true
            });
        }
    },
    computed: {
        ...mapState('pagaduriasModule', ['pagaduriasTypes']),
        invalidFeedback() {
            console.log(this);
            if (this) {
                return 'Enter at least 4 characters.';
            }
            return 'Please enter something.';
        },
        isAwaiting() {
            return new URLSearchParams(window.location.search).get('status') === 'awaiting';
        },
        isInvalid() {
            return new URLSearchParams(window.location.search).get('status') === 'invalid';
        },
        items() {
            return {
                FOPEP: {
                    amount: { key: 'Monto Solicitado', value: 1000000 },
                    dues: { key: 'Cuotas', value: this.creditInfo.dues }
                },
                FIDUPREVISORA: {
                    amount: { key: 'Monto Solicitado', value: 2000000 },
                    dues: { key: 'Cuotas', value: this.creditInfo.dues }
                },
                SEDVALLE: {
                    amount: { key: 'Monto Solicitado', value: 1500000 },
                    dues: { key: 'Cuotas', value: this.creditInfo.dues }
                },
                SEMCALI: {
                    amount: { key: 'Monto Solicitado', value: 3000000 },
                    dues: { key: 'Cuotas', value: this.creditInfo.dues }
                }
            };
        }
    },
    methods: {
        validateState(name) {
            const { $dirty, $error } = this.form[name];
            return $dirty ? !$error : null;
        },
        submitData() {
            const params = this.form;

            axios
                .post('/cotizer-data', { creditInfo: this.creditInfo, cotizerData: params })
                .then(response => {
                    const cotizerId = response.data.cotizador.id;
                    const decevalProcess = response.data.decevalProcess;

                    window.location.href = `/cifin/consultar?cotizerId=${cotizerId}&decevalProcess=${decevalProcess}`;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        approveValue() {
            if (this.creditInfo.total.value <= 250000) {
                this.$swal({
                    icon: 'success',
                    title: 'Felicidades tu credito ha sido aprobado'
                }).then(() => {
                    this.$router.push({ name: 'Register' });
                });
            } else {
                this.$swal({
                    icon: 'error',
                    title: 'Ups, tu credito ha sido rechazado'
                });
            }
        },
        async saveData() {
            if (this.form.idNumber) {
                this.savingData = true;
                this.selectedOffers = [];
                this.pagaduriasClient = null;
                this.pagaduriasClient = await this.getAllPagadurias();
            } else {
                alert('Debes ingresar un numero de cedula');
                return;
            }

            this.savingData = false;
            /*setTimeout(() => {
                document.querySelector('.seccion8').scrollIntoView({ behavior: 'smooth' });
            }, 100); */
        },
        getAllPagadurias() {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/pagadurias/per-doc/${this.form.idNumber}`)
                    .then(res => {
                        resolve(res.data);
                    })
                    .catch(err => {
                        console.log(err);
                        reject(err);
                    })
                    .finally(() => {
                        this.savingData = false;
                    });
            });
        },

        executeSpecificFunction() {
            this.modifyPdf();
        },
        convertirLetras(numero, moneda = 'PESOS COLOMBIANOS', centimos = 'CENTAVOS') {
            // Verificar si el número es válido
            if (isNaN(numero) || numero === null || numero === '') {
                return '';
            }

            // Formatear el número con 2 decimales
            const numeroStr = parseFloat(numero).toFixed(2);
            const [enteroStr, decimalStr] = numeroStr.split('.');
            let entero = parseInt(enteroStr);
            const decimal = parseInt(decimalStr);

            if (entero === 0) {
                const resultadoDecimal = decimal > 0
                    ? ' CERO ' + moneda + ' CON ' + this.convertirCantidad(decimal) + ' ' + centimos
                    : ' CERO ' + moneda;
                return resultadoDecimal.trim();
            }

            let texto = this.convertirCantidad(entero) + ' ' + moneda;

            if (decimal > 0) {
                texto += ' CON ' + this.convertirCantidad(decimal) + ' ' + centimos;
            }

            return texto.trim();
        },

        convertirCantidad(cantidad) {
            if (cantidad === 0) return 'CERO';
            if (cantidad < 10) return this.unidades[cantidad];
            if (cantidad < 20) return this.decimas[cantidad - 10];

            let resultado = '';
            const millones = Math.floor(cantidad / 1000000);
            const restoMillones = cantidad % 1000000;

            if (millones > 0) {
                resultado += (millones === 1 ? 'UN MILLON' : this.convertirCantidad(millones) + ' MILLONES') + ' ';
            }

            const miles = Math.floor(restoMillones / 1000);
            const restoMiles = restoMillones % 1000;

            if (miles > 0) {
                resultado += (miles === 1 ? 'MIL' : this.convertirCantidad(miles) + ' MIL') + ' ';
            }

            if (restoMiles > 0) {
                resultado += this.convertirCentenas(restoMiles);
            }

            return resultado.trim();
        },

        convertirCentenas(cantidad) {
            if (cantidad === 100) return 'CIEN';
            const centenasVal = Math.floor(cantidad / 100);
            const resto = cantidad % 100;
            let resultado = this.centenas[centenasVal];

            if (resto > 0) {
                resultado += ' ' + this.convertirDecenas(resto);
            }

            return resultado;
        },

        convertirDecenas(cantidad) {
            if (cantidad < 10) return this.unidades[cantidad];
            if (cantidad < 20) return this.decimas[cantidad - 10];

            const decenasVal = Math.floor(cantidad / 10);
            const unidadesVal = cantidad % 10;
            let resultado = this.decenas[decenasVal];

            if (unidadesVal > 0) {
                resultado += ' Y ' + this.unidades[unidadesVal];
            }

            return resultado;
        },

        async splitTextToLines(text, maxWidth, font, fontSize, x, y, page) {
            const words = text.split(' ');
            let line = '';
            let lines = [];

            for (let i = 0; i < words.length; i++) {
                const testLine = line + (line ? ' ' : '') + words[i];
                const testWidth = font.widthOfTextAtSize(testLine, fontSize);

                if (testWidth > maxWidth && line) {
                    lines.push(line);
                    line = words[i];
                } else {
                    line = testLine;
                }
            }

            if (line) {
                lines.push(line);
            }

            // Dibujar cada línea
            for (let i = 0; i < lines.length; i++) {
                page.drawText(lines[i], {
                    x: x,
                    y: y - (i * (fontSize + 2)), // Espacio entre líneas
                    size: fontSize,
                    font: font,
                    color: rgb(0, 0, 0),
                });
            }

            return lines.length;
        },

        async modifyPdf() {
            try {
                const textos = [
                    //this.form.payerName,
                    //this.form.payerCity,
                    // this.form.dateDiligence,
                    this.form.creditValueL,
                    this.form.rateL,
                    this.form.quotaValueL,
                    this.form.quotaL,
                    this.form.totalCreditValueL,
                    this.form.startDateL,
                    this.form.endDateL
                ];

                const textosPagina16 = [
                    this.form.totalCreditValueL,
                    this.form.totalCreditValueL ? this.convertirLetras(this.form.totalCreditValueL) : '',
                    this.form.rateL,
                    this.form.quotaL,
                    this.form.quotaValueL,
                    this.form.quotaValueL ? this.convertirLetras(this.form.quotaValueL) : ''
                ];

                if (textos.every(t => !t)) {
                    this.error = 'Por favor ingresa al menos un texto para agregar al PDF';
                    return;
                }

                const url = 'http://ami-project.me/storage/pdfs/firmados/LIBRANZA_CK_10017297_1.pdf';
                const existingPdfBytes = await fetch(url).then(res => res.arrayBuffer());

                const pdfDoc = await PDFDocument.load(existingPdfBytes);
                pdfDoc.registerFontkit(fontkit);

                const fontUrl = 'http://ami-project.me/storage/pdfs/firmados/Times New Roman Bold.ttf';
                const fontBytes = await fetch(fontUrl).then(res => res.arrayBuffer());
                const timesNewRomanFont = await pdfDoc.embedFont(fontBytes);

                const pages = pdfDoc.getPages();

                if (pages.length < 19) {
                    this.error = 'El PDF no tiene suficientes páginas. Se necesitan al menos 19 páginas.';
                    return;
                }

                // Modificar página 9 (índice 8)
                const page9 = pages[8];
                const { height: height9 } = page9.getSize();
                const posicionesX9 = [50, 160, 210, 280, 330, 440, 525];

                textos.slice(0, 7).forEach((texto, i) => {
                    if (texto) {
                        page9.drawText(texto, {
                            x: posicionesX9[i],
                            y: height9 - 188,
                            size: 10,
                            font: timesNewRomanFont,
                            color: rgb(0, 0, 0),
                        });
                    }
                });

                // Modificar página 10 (índice 9)
                const page10 = pages[9];
                const { height: height10 } = page10.getSize();
                const posicionesX10 = [50, 160, 210, 280, 330, 440, 525];

                textos.slice(0, 7).forEach((texto, i) => {
                    if (texto) {
                        page10.drawText(texto, {
                            x: posicionesX10[i],
                            y: height10 - 200,
                            size: 10,
                            font: timesNewRomanFont,
                            color: rgb(0, 0, 0),
                        });
                    }
                });

                // Modificar página 16 (índice 15)
                const page16 = pages[15];
                const { width, height } = page16.getSize();

                const coordenadasPagina16 = [
                    { x: 60, y: height - 512, ancho: 100 },
                    { x: 170, y: height - 502, ancho: 230 },
                    { x: 410, y: height - 512, ancho: 100 },
                    { x: 60, y: height - 544, ancho: 100 },
                    { x: 102, y: height - 544, ancho: 100 },
                    { x: 214, y: height - 536, ancho: 335 }
                ];

                for (let i = 0; i < textosPagina16.length; i++) {
                    const texto = textosPagina16[i];
                    if (texto) {
                        const coord = coordenadasPagina16[i];

                        if (i === 1 || i === 5) {
                            await this.splitTextToLines(
                                texto,
                                coord.ancho,
                                timesNewRomanFont,
                                6,
                                coord.x,
                                coord.y,
                                page16
                            );
                        } else {
                            page16.drawText(texto, {
                                x: coord.x,
                                y: coord.y,
                                size: 10,
                                font: timesNewRomanFont,
                                color: rgb(0, 0, 0),
                            });
                        }
                    }
                }

                // Generar y descargar el PDF
                const pdfBytes = await pdfDoc.save();
                this.downloadPdf(pdfBytes);
                this.error = null;

            } catch (error) {
                console.error('Error al modificar el PDF:', error);
                this.error = 'Ocurrió un error al procesar el PDF. Verifica la consola para más detalles.';
            }
        },

        downloadPdf(pdfBytes) {
            const blob = new Blob([pdfBytes], { type: 'application/pdf' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'LIBRANZA_CK_10017297_1_modificado.pdf';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

    }
};
</script>

<style lang="scss" scoped>
.sidebar {
    transition: width 400ms;
}

.form-control {
    // background-color: #e1e1e1;
    border: none;
    // height: 38px;
}

.custom-select {
    // background-color: #e1e1e1;
    height: 36px;
}

.labelForm {
    font-size: 14px;
    font-weight: 400;
    line-height: 18.23px;
}

.labelContenedor {
    border-bottom: 1px solid #c9c9ce;
}

.inputContenedor {
    border-bottom: 1px solid #c9c9ce;
}

.rowForm {
    margin-right: 30px;
}

// secciones
.seccion1 {
    padding: 50px 97px 20px;
}

.seccion2 {
    padding: 35px 97px;
}

.seccion3 {
    padding: 50px 97px 20px;
}

.seccion4 {
    padding: 35px 97px;
}

.seccion5 {
    padding: 50px 97px 51px;
}

.seccion6 {
    padding: 35px 97px;
}

.seccion7 {
    padding: 30px 97px 10px;
}

.seccion8 {
    padding: 35px 97px;
}

.colDataForm {
    text-align: right;
}

.detailsSub {
    margin-top: 15px;
    margin-bottom: 20px;
    font-size: 18px;
    font-weight: 500;
    line-height: 23.44px;
}

.dateSub {
    font-size: 32px;
    margin-top: 5%;
    font-weight: 200;
    margin-bottom: 25px;
    color: #0cedb0;
    margin-top: -6%;
}

.margin_data_account {
    margin-top: 10px;
}

.fontBillion {
    font-family: 'Billion Dreams Regular';
    font-size: 32px;
    margin-top: 5%;
    font-weight: 200;
    margin-bottom: 25px;
    color: #6770f2;
    margin-top: -6%;
}

.lastNamePopup {
    font-size: 30px;
    margin-top: 5%;
    font-weight: 200;
    margin-bottom: 25px;
    color: #0052a7;
    margin-top: -9%;
}

.titelFinalForm {
    font-size: 20px;
    margin-top: 5%;
    font-weight: 200;
    margin-bottom: 25px;
    color: #685856;
}

.textPopup {
    font-size: 16px;
    line-height: 1.2;
    margin-top: 5%;
    font-weight: 200;
    margin-bottom: 30px;
    color: #685856;
}

.imgLogoPopup {
    position: absolute;
    top: -35%;
    width: 46%;
    left: 29%;
}

.sizeCalendar {
    font-size: 13px;
}

.labelGen {
    font-size: 13px;
}

.genderLabel {
    margin-bottom: 3px;
}

.selectBorder {
    border: none;
}

.tituleInfo {
    font-size: 32px;
    font-weight: bolder;
    color: #0cedb0;
    margin-bottom: 5%;
}

.numberMonto {
    font-size: 32px;
    font-weight: bolder;
    margin-bottom: 5%;
    margin-top: -6%;
    color: #0cedb0;
}

.namePopup {
    font-size: 25px;
    font-weight: bolder;
    margin-bottom: 5%;
    margin-top: -6%;
    color: #0052a7;
}

.othersField {
    margin-top: 15px;
}

.marginNeto {
    margin-top: 10px;
}

.infoFinalContainer {
    position: relative;
    top: 30%;
}

// estilos de la tabla
.nameTable {
    text-align: left;
}

.numberTable {
    text-align: right;
}

.rowTable {
    padding: 3px;
}

.listTableGroup:nth-child(odd) {
    padding: 0 10px;
    background-color: #fff;
    border: none;
}

.listTableGroup:nth-child(even) {
    padding: 0 10px;
    background-color: #e1e2fc;
    border-radius: 20px;
}

// estilos de boton
.btnContainer {
    margin-top: 26%;
}

.btnAceptCredit {
    padding: 8px 79px;
    background-image: linear-gradient(to right, #0cedb0 0%, #0cedb0 55%, #0cedb0 100%);
    color: #fff;
    border-radius: 50px;
}

.btnAceptPopup {
    padding: 8px 60px;
    background-image: linear-gradient(to right, #0cedb0 0%, #0cedb0 55%, #0cedb0 100%);
    color: #fff;
    border-radius: 50px;
}

.btnAceptCredit {
    transition: 0.5s;
    background-size: 200% auto;
}

// estilos de modal
.containerInfoModal {
    text-align: center;
    padding: 10px;
}

.section-separator {
    position: relative;
    height: 3px;
    margin: 1rem 0;
    text-align: center;

    .separator-text {
        position: relative;
        top: -0.7em;
        padding: 0 1rem;
        color: #685856; // Color de texto que combina
        font-size: 1.5rem;
    }
}

.form-container {
    border: 2px solid #ccc;
    /* Borde del cuadrado */
    border-radius: 8px;
    /* Esquinas redondeadas (opcional) */
    padding: 20px;
    /* Espacio interno */
    //max-width: 500px; /* Ancho máximo del contenedor */
    margin: 0 auto;
    /* Centrar el contenedor */
}
</style>
