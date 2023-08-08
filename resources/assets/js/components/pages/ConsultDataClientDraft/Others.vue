<template>
    <div class="col-md-12">
        <div class="panel panel-primary mb-3">
            <div class="panel-heading"><b>OTROS POSIBLES INGRESOS Y DEDUCCIONES</b></div>
            <div class="panel-body">
                <loading :active.sync="isLoading" color="#0CEDB0" :can-cancel="true" :is-full-page="true" />
                <div class="row">
                    <!--============================
                        datames FOPEP -
                    ==============================-->
                    <div class="col-12 tables-space mb-4" v-if="pagadurias.datames && pagaduriaType != 'FOPEP'">
                        <b class="panel-label">OTRO POSIBLE INGRESO 1:</b>
                        <div>
                            <p class="panel-value">{{ pagadurias.datames.vpension | currency }}</p>
                        </div>

                        <button
                            type="button"
                            class="btn btn-primary"
                            v-on:click="modalConfirmConsultPag('showDatames', 'FOPEP')"
                        >
                            Ver más
                        </button>
                        <button type="button" class="btn btn-secondary" v-on:click="showDatames = false">Cerrar</button>
                    </div>
                    <template v-if="showDatames">
                        <Datames :datames="pagadurias.datames" :user="user" />
                        <EmploymentHistory :user="user" :fechavinc="fechavinc" :datames="pagadurias.datames" />
                        <Descapli :descapli="descapli" />
                        <Descnoap :descnoap="descnoap" />
                    </template>

                    <!--============================
                        datamesfidu FIDUPREVISORA
                    ==============================-->

                    <div
                        class="col-12 tables-space mb-4"
                        v-if="pagadurias.datamesfidu && pagaduriaType != 'FIDUPREVISORA'"
                    >
                        <b class="panel-label">OTRO POSIBLE INGRESO 2:</b>
                        <div>
                            <p class="panel-value">{{ pagadurias.datamesfidu.vpension | currency }}</p>
                        </div>
                        <button
                            type="button"
                            class="btn btn-primary"
                            v-on:click="modalConfirmConsultPag('showDatamesFidu', 'FIDUPREVISORA')"
                        >
                            Ver más
                        </button>
                        <button type="button" class="btn btn-secondary" v-on:click="showDatamesFidu = false">
                            Cerrar
                        </button>
                    </div>

                    <template v-if="showDatamesFidu">
                        <DatamesFidu v-if="showDatamesFidu" :datamesfidu="pagadurias.datamesfidu" :user="user" />
                        <EmploymentHistory :user="user" :fechavinc="fechavinc" :datamesfidu="pagadurias.datamesfidu" />
                        <DescapliEmpty />
                        <DescnoapEmpty />
                    </template>

                    <!--============================
                        DATAMESSEDUC SED VALLE
                    ==============================-->

                    <div
                        class="col-12 tables-space mb-4"
                        v-if="pagadurias.datamessedvalle && pagaduriaType != 'SEDVALLE'"
                    >
                        <b class="panel-label">OTRO POSIBLE INGRESO 4:</b>
                        <div>
                            <p class="panel-value">{{ pagadurias.datamessedvalle.vpension | currency }}</p>
                        </div>
                        <button
                            type="button"
                            class="btn btn-primary"
                            v-on:click="modalConfirmConsultPag('showDatamesSedValle', 'SEDVALLE')"
                        >
                            Ver más
                        </button>
                        <button type="button" class="btn btn-secondary" v-on:click="showDatamesSedValle = false">
                            Cerrar
                        </button>
                    </div>

                    <template v-if="showDatamesSedValle">
                        <DatamesSeduc :datamessedvalle="pagadurias.datamessedvalle" :user="user" />
                        <EmploymentHistory
                            :user="user"
                            :fechavinc="fechavinc"
                            :datamessedvalle="pagadurias.datamessedvalle"
                        />
                        <DescapliEmpty />
                        <EmbargosSedValle :embargossedvalle="embargossedvalle" />
                    </template>

                    <!--============================
                        DATAMESCALISEMCALI
                    ==============================-->

                    <div
                        class="col-12 tables-space mb-4"
                        v-if="pagadurias.datamessemcali && pagaduriaType != 'SEMCALI'"
                    >
                        <b class="panel-label">OTRO POSIBLE INGRESO 3:</b>
                        <div>
                            <p class="panel-value">{{ pagadurias.datamessemcali.vpension | currency }}</p>
                        </div>
                        <button
                            type="button"
                            class="btn btn-primary"
                            v-on:click="modalConfirmConsultPag('showDatamessemcali', 'SEMCALI')"
                        >
                            Ver más
                        </button>
                        <button type="button" class="btn btn-secondary" v-on:click="showDatamessemcali = false">
                            Cerrar
                        </button>
                    </div>

                    <template v-if="showDatamessemcali">
                        <DatamesSemCali :datamessemcali="pagadurias.datamessemcali" :user="user" />
                        <EmploymentHistory
                            :user="user"
                            :fechavinc="fechavinc"
                            :datamessedvalle="pagadurias.datamessemcali"
                        />
                        <Descapli :descapli="descapli" />
                        <Descnoap :descnoap="descnoap" />
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DatamesFidu from './DatamesFidu';
import DatamesSemCali from './DatamesSemCali.vue';
import DatamesSedValle from './DatamesSedValle.vue';
import Datames from './Datames';
import EmploymentHistory from './EmploymentHistory';
import Descapli from './Descapli';
import DescapliEmpty from './DescapliEmpty';
import Descnoap from './Descnoap';
import DescnoapEmpty from './DescnoapEmpty';
import EmbargosSedValle from './EmbargosSedValle.vue';

export default {
    name: 'Others',
    props: ['pagaduriaType', 'pagadurias', 'fechavinc', 'descapli', 'descnoap', 'user', 'embargossedvalle'],
    components: {
        Datames,
        DatamesFidu,
        DatamesSemCali,
        DatamesSedValle,
        EmploymentHistory,
        Descapli,
        DescapliEmpty,
        Descnoap,
        DescnoapEmpty,
        EmbargosSedValle
    },
    created() {},
    data() {
        return {
            showDatames: false,
            showDatamesFidu: false,
            showDatamesSedValle: false,
            showDatamessemcali: false,
            isLoading: false
        };
    },
    methods: {
        modalConfirmConsultPag(val, pagaduria) {
            this.$bvModal
                .msgBoxConfirm('Esta acción tiene un costo', {
                    title: '¿Está seguro que desea realizar la consulta?',
                    size: 'sm',
                    buttonSize: 'sm',
                    okVariant: 'success',
                    okTitle: 'Consultar',
                    cancelTitle: 'Cancelar',
                    cancelVariant: 'danger',
                    headerClass: 'p-2 border-bottom-0',
                    footerClass: 'p-2 border-top-0',
                    centered: true
                })
                .then(value => {
                    if (!value) return;
                    this.isLoading = true;
                    this.saveVisados(val, pagaduria).then(status => {
                        if (status != 200) return;
                        this[val] = true;
                    });
                });
        },
        async saveVisados(val, pagaduria) {
            try {
                const payload = { pagaduria, pagadurias: this.pagadurias };
                const response = await axios.post('/visados', { ...payload });
                return Promise.resolve(response.status);
            } catch (e) {
                toastr.error('No se pudo realizar la consulta');
            } finally {
                this.isLoading = false;
            }
        }
    }
};
</script>

<style scoped></style>
