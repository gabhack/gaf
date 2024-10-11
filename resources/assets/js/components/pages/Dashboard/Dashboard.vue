<template>
    <div class="pt-3">
        <b-row>
            <b-col cols="12" md="7" class="pb-3 pb-md-0">
                <b-card class="mb-2" style="height: 100%;">
                    <div>
                        <b-row>
                            <b-col cols="12" md="8" class="d-flex align-items-center">
                                <label class="ml-3 mb-0 table">Cantidad de consultas</label
                                ><span class="num-consultas">238</span>
                            </b-col>
                            <b-col cols="12" md="4" class="d-flex align-items-center justify-content-start justify-content-md-end">
                                <CustomButton @click="showMore('table')" class="ml-3 ml-md-0"
                                    >Ver todo <ArrowRight class="ml-2" />
                                </CustomButton>
                            </b-col>
                        </b-row>
                    </div>
                    <VueApexCharts height="250" type="bar" :options="chartOptions" :series="series"></VueApexCharts>
                </b-card>
            </b-col>
            <b-col cols="12" md="5" class="d-flex flex-column justify-content-between">
                <b-card class="usr-camp-card mb-3 mb-md-0">
                    <b-row>
                        <b-col cols="6" class="d-flex align-items-center">
                            <Users class="mr-2" />
                            <p class="usr-camp">Usuarios</p>
                        </b-col>
                        <b-col cols="6" class="d-flex justify-content-end">
                            <CustomButton @click="showMore('users')"
                                >Ver todo <ArrowRight class="ml-2" />
                            </CustomButton>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="5" class="d-flex align-items-center justify-content-start">
                            <label class="ml-3 mb-0 act-cre">Activos</label><span class="num-act-cre ml-3">25</span>
                        </b-col>
                        <b-col cols="5" class="d-flex align-items-center justify-content-start">
                            <label class="ml-3 mb-0 act-cre">Creados</label><span class="num-act-cre ml-3">15</span>
                        </b-col>
                    </b-row>
                </b-card>

                <b-card class="usr-camp-card mb-3 mb-md-0">
                    <b-row>
                        <b-col cols="6" class="d-flex align-items-center">
                            <Compania class="mr-2" />
                            <p class="usr-camp">Campañas</p>
                        </b-col>
                        <b-col cols="6" class="d-flex justify-content-end">
                            <CustomButton @click="showMore('campa')"
                                >Ver todo <ArrowRight class="ml-2" />
                            </CustomButton>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="5" class="d-flex align-items-center justify-content-start">
                            <label class="ml-3 mb-0 act-cre">Activas</label><span class="num-act-cre ml-3">25</span>
                        </b-col>
                        <b-col cols="5" class="d-flex align-items-center justify-content-start">
                            <label class="ml-3 mb-0 act-cre">Creadas</label><span class="num-act-cre ml-3">15</span>
                        </b-col>
                    </b-row>
                </b-card>
            </b-col>
        </b-row>

        <b-row class="pt-5">
          <b-col cols="12" class="d-flex ">
            <h4 class="mes-consulta">Consultas AMI: <b>{{ mesActual }}</b></h4> 
          </b-col>
        </b-row>

        <b-row>
            <b-col cols="12" md="4" class="pb-3 pb-md-0">
                <b-card class="relative-card silver">
                    <div class="visitas"><b>SILVER</b></div>
                    <div class="d-flex align-items-center" style="gap: 16px;"><span class="num-consultas">15</span> <span class="visitas">visitas</span></div>
                    <div class="absolute-content"><span class="porcentaje" style="color: #04B53F;">+33,45% <GoUpArrow class="ml-1"/></span></div>
                </b-card>
            </b-col>
            <b-col cols="12" md="4" class="pb-3 pb-md-0">
                <b-card class="relative-card gold">
                    <div class="visitas"><b>GOLD</b></div>
                    <div class="d-flex align-items-center" style="gap: 16px;"><span class="num-consultas">15</span><span class="visitas">visitas</span> </div>
                    <div class="absolute-content"><span class="porcentaje" style="color: #F34235;">-12,5% <GoDownArrow class="ml-1"/></span></div>
                </b-card>
            </b-col>
            <b-col cols="12" md="4" class="pb-3 pb-md-0">
                <b-card class="relative-card diamond">
                    <div class="visitas"><b>DIAMOND</b></div>
                    <div class="d-flex align-items-center" style="gap: 16px;"><span class="num-consultas">15</span> <span class="visitas">visitas</span></div>
                    <div class="absolute-content"><span class="porcentaje" style="color: #04B53F;">+52,1% <GoUpArrow class="ml-1"/></span></div>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>
<script>
import VueApexCharts from 'vue-apexcharts';
import CustomButton from '../../customComponents/CustomButton.vue';
import ArrowRight from '../../icons/ArrowRight.vue';
import Users from '../../icons/users.vue';
import Compania from '../../icons/Compania.vue';
import GoDownArrow from '../../icons/GoDownArrow.vue';
import GoUpArrow from '../../icons/GoUpArrow.vue';
export default {
    components: {
        VueApexCharts,
        CustomButton,
        Users,
        Compania,
        ArrowRight,
        GoDownArrow,
        GoUpArrow
    },
    data() {
        return {
            testerVar: false,
            mesActual: this.getCurrentMonth(),
            // eje Y tabla START
            series: [
                {
                    name: '# consultas',
                    data: [30, 40, 45, 50, 49, 60, 70, 91, 82]
                }
            ],
            // END

            chartOptions: {
                chart: {
                    type: 'bar',
                    height: 350,
                    zoom: {
                        enabled: false
                    }
                },

                // eje X tabla START
                xaxis: {
                    categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
                },
                //END

                // Numero dato en mitad de la barra START
                dataLabels: {
                    enabled: false,
                    background: {
                        borderRadius: 10,
                        borderColor: '#fff'
                    }
                },
                //END

                legend: {
                    horizontalAlign: 'left'
                },
                colors: ['#6cd6b0', '#548d96'],
                plotOptions: {
                    bar: {
                        distributed: true,
                        borderRadius: 0,
                        horizontal: false
                    }
                },
                legend: {
                    show: false // Aquí desactivas la leyenda
                },
                markers: {
                    size: 3,
                    colors: ['#1E90FF'],
                    strokeColor: '#1E90FF',
                    strokeWidth: 3
                }
            }
        };
    },

    methods: {
        showMore(show) {
            if (show == 'table') {
                console.log('ir a TABLA ver todo');
            }
            if (show == 'users') {
                console.log('ir a USUARIOS ver todo');
            }
            if (show == 'campa') {
                console.log('ir a CAMPAÑA ver todo');
            }
        },

        getCurrentMonth() {
            const date = new Date();
            const month = date.getMonth();
            return this.getMonthName(month);
        },
        getMonthName(monthIndex) {
            const months = [
                'Enero',
                'Febrero',
                'Marzo',
                'Abril',
                'Mayo',
                'Junio',
                'Julio',
                'Agosto',
                'Septiembre',
                'Octubre',
                'Noviembre',
                'Diciembre'
            ];
            return months[monthIndex];
        }
    }
};
</script>
<style scoped lang="scss">
.card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 100%;
}
label {
    &.table {
        font-size: 18px;
        font-weight: 500;
        line-height: 23.44px;
    }
    &.act-cre {
        font-size: 16px;
        font-weight: 400;
        line-height: 20.83px;
    }
}
.num {
    &-consultas {
        font-size: 42px;
        font-weight: 700;
        line-height: 54.68px;
    }
    &-act-cre {
        font-size: 25px;
        font-weight: 700;
        line-height: 32.55px;
    }
}
.usr-camp {
    font-size: 16px;
    font-weight: 700;
    line-height: 20.83px;
    margin-bottom: 0;
    &-card .card-body {
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
}
.mes-consulta{
  font-size: 25px;
  font-weight: 500;
  line-height: 32.55px;
}
.relative-card{
    position: relative;
    & .card-body{
        min-height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    & .absolute-content{
        position: absolute; 
        bottom: 7%;
        right: 5%;


        & span.porcentaje{
            font-size: 14px;
            font-weight: 600;
            line-height: 18.23px;
        }
    }
    &.silver{
        background-color: #edf8fe;
    }
    &.gold{
        background-color: #fcf6e8;
    }
    &.diamond{
        background-color: #edf8fe;
    }
}
.visitas{
    font-size: 18px;
    font-weight: 500;
    line-height: 23.44px;
}

</style>
