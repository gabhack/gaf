<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Administración de Datos</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">Selecciona el tipo de archivo a importar</label>
                    <select v-model="optionSelected" class="form-control">
                        <option value="fechavinc">FECHAVINC</option>
                        <option value="datames">DATAMES</option>
                        <option value="descapli">DESCAPLI</option>
                        <option value="descnoap">DESCNOAP</option>
                        <option value="datamesfidu">DATAMESFIDU</option>
                        <option value="datamessedvalle">DATAMESSEDVALLE</option>
                    </select>
                </div>

                <div class="form-group" v-if="optionSelected === 'fechavinc'">
                    <label for="">Selecciona el archivo a importar (FECHAVINC)</label>
                    <input type="file" name="fechavinc" v-on:change="getFile" class="form-control" />
                </div>

                <div class="form-group" v-if="optionSelected === 'datames'">
                    <label for="">Selecciona el archivo a importar (DATAMES)</label>
                    <input type="file" ref="file" name="datames" v-on:change="getFile" class="form-control" />
                </div>

                <div class="form-group" v-if="optionSelected === 'descapli'">
                    <label for="">Selecciona el archivo a importar (DESCAPLI)</label>
                    <input type="file" name="descapli" v-on:change="getFile" class="form-control" />
                </div>

                <div class="form-group" v-if="optionSelected === 'descnoap'">
                    <label for="">Selecciona el archivo a importar (DESCNOAP)</label>
                    <input type="file" name="descnoap" v-on:change="getFile" class="form-control" />
                </div>

                <div class="form-group" v-if="optionSelected === 'datamesfidu'">
                    <label for="">Selecciona el archivo a importar (DATAMESFIDU)</label>
                    <input type="file" name="datamesfidu" v-on:change="getFile" class="form-control" />
                </div>

                <div class="form-group" v-if="optionSelected === 'datamessedvalle'">
                    <label for="">Selecciona el archivo a importar (DATAMESSEDVALLE)</label>
                    <input type="file" name="datamessedvalle" v-on:change="getFile" class="form-control" />
                </div>

                <div class="form-group" v-if="optionSelected !== null">
                    <button class="btn btn-primary" v-on:click="importFile">Importar</button>
                </div>

                <div
                    class="form-group mt-2"
                    v-if="
                        optionSelected === 'datames' ||
                        optionSelected === 'datamesfidu' ||
                        optionSelected === 'datamessedvalle'
                    "
                >
                    <label for="">Vaciar Tabla</label>
                    <button class="btn btn-primary" v-on:click="dumpDataMes">Vaciar tabla</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            optionSelected: null,
            file: []
        };
    },

    mounted() {},

    methods: {
        getFile(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length) return;
            this.file = files[0];
        },
        dumpDataMes() {
            if (this.optionSelected === 'datames') {
                axios.get('dumpDataMes').then(response => {
                    toastr.success('Datos de tabla Borrados');
                    console.log(response.data);
                });
            } else if (this.optionSelected === 'datamesfidu') {
                axios.get('dumpDataMesFidu').then(response => {
                    toastr.success('Datos de tabla Borrados');
                    console.log(response.data);
                });
            } else if (this.optionSelected === 'datamessedvalle') {
                axios.get('dumpDataMesSedValle').then(response => {
                    toastr.success('Datos de tabla Borrados');
                    console.log(response.data);
                });
            }
        },
        importFile() {
            if (this.optionSelected === 'fechavinc') {
                const formData = new FormData();
                formData.append('file', this.file);
                axios
                    .post('fechaVincImport', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'mime-type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        }
                    })
                    .then(response => {
                        toastr.success('Importación de fechaVinc realizada');
                    })
                    .catch(error => {
                        console.log(error);
                    });
            } else if (this.optionSelected === 'datames') {
                const formData = new FormData();
                formData.append('file', this.file);
                axios
                    .post('datamesImport', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'mime-type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        }
                    })
                    .then(response => {
                        toastr.success('Importación datames Realizado');
                    })
                    .catch(error => {
                        console.log(error);
                    });
            } else if (this.optionSelected === 'descapli') {
                const formData = new FormData();
                formData.append('file', this.file);
                axios
                    .post('descapliImport', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'mime-type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        }
                    })
                    .then(response => {
                        toastr.success('importación de Descapli Realizada');
                    })
                    .catch(error => {
                        console.log(error);
                    });
            } else if (this.optionSelected === 'descnoap') {
                const formData = new FormData();
                formData.append('file', this.file);
                axios
                    .post('descnoapController', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'mime-type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        }
                    })
                    .then(response => {
                        toastr.success('importación de Descnoap Realizada');
                        console.log(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            } else if (this.optionSelected === 'datamesfidu') {
                const formData = new FormData();
                formData.append('file', this.file);
                axios
                    .post('datamesfiduImport', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'mime-type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        }
                    })
                    .then(response => {
                        toastr.success('importación de Datamesfidu Realizada');
                        console.log(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            } else if (this.optionSelected === 'datamessedvalle') {
                const formData = new FormData();
                formData.append('file', this.file);
                axios
                    .post('datamessedvalleImport', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'mime-type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        }
                    })
                    .then(response => {
                        toastr.success('importación de DATAMESSEDVALLE Realizada');
                        console.log(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        }
    }
};
</script>
