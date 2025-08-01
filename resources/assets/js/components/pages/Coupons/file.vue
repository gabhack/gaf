-- Tabla para mostrar los resultados de EN MORA -->

        <div class="panel mb-3 col-md-12" v-if="descuentos && descuentos.length > 0 && selectedEstado === 'En mora'">
            <div class="panel-heading">
                <b>RESUMEN</b>
            </div>
            <div class="row d-flex align-items-center justify-content-center py-4">
                <div class="col-4"><label class="label-titulo">Estado</label></div>
                <div class="col-4"><label class="label-titulo">Total Clientes</label></div>
                <div class="col-4"><label class="label-titulo">Total Cuotas</label></div>

                <div class="col-4 pb-2"><label class="label-resumen">En mora</label></div>
                <div class="col-4 pb-2">
                    <label class="label-resumen">{{ rowsMora }}</label>
                </div>
                <div class="col-4 pb-2">
                    <label class="label-resumen">{{ totalCuotasMora }}</label>
                </div>
            </div>
            <div class="panel-heading">
                <b>RESULTADOS DE LA CONSULTA (Cartera en Mora)</b>
            </div>
            <div class="panel-body">
                <b-form-input
                    v-model="filtroDescuento"
                    placeholder="Buscar por documento..."
                    class="mb-3"
                ></b-form-input>
                <div class="table-responsive">
                    <b-table striped id="mora-table" hover :fields="descuentosFields" :items="descuentosFiltrados">
                        <template v-slot:cell(actions)="{ item }">
                            <b-button v-b-modal.modal-1 variant="primary" @click="handleButtonClick(item.doc, item.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="25" height="25">
                                    <g id="_01_align_center" data-name="01 align center">
                                        <path
                                            d="M23.821,11.181v0C22.943,9.261,19.5,3,12,3S1.057,9.261.179,11.181a1.969,1.969,0,0,0,0,1.64C1.057,14.739,4.5,21,12,21s10.943-6.261,11.821-8.181A1.968,1.968,0,0,0,23.821,11.181ZM12,19c-6.307,0-9.25-5.366-10-6.989C2.75,10.366,5.693,5,12,5c6.292,0,9.236,5.343,10,7C21.236,13.657,18.292,19,12,19Z"
                                        />
                                        <path
                                            d="M12,7a5,5,0,1,0,5,5A5.006,5.006,0,0,0,12,7Zm0,8a3,3,0,1,1,3-3A3,3,0,0,1,12,15Z"
                                        />
                                    </g>
                                </svg>
                            </b-button>
                        </template>
                    </b-table>