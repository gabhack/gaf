<template>
    <div style="padding: 30px">
        <b-row>
            <b-col cols="12" md="9" style="margin-left: 28px">
                <h3 class="heading-title">Historial de Análisis de Cartera</h3>
                <p style="font-size: 14px; line-height: 1.6; color: #6c757d; margin-top: 15px">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
            </b-col>
        </b-row>

        <!-- Panel para contenido futuro -->
        <div class="panel mb-3 col-md-12" style="margin-top: 30px">
            <div class="panel-body">
                <h4 class="heading-title" style="margin-bottom: 20px">Historial de Consultas</h4>
                <p style="text-align: center; color: #6c757d; padding: 40px 0;">
                    Contenido en desarrollo...
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'HistorialCartera',
    data() {
        return {
            // Datos para el historial cuando se implemente
        }
    },
    mounted() {
        // Lógica de inicialización cuando se implemente
    },
    methods: {
        // Métodos para cargar historial cuando se implemente
    }
}
</script>

<style scoped>
.heading-title {
    color: #2c8c73;
    font-weight: 600;
}

.panel {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.panel-body {
    padding: 20px;
}
</style>
