const descuentosModule = {
    namespaced: true,
    state: {
        descuentos: [],
        descuentosType: '',
        selectedPeriod: ''
    },
    getters: {
        descuentosPeriodos: state => {
            const periodos = state.descuentos.reduce((acc, descuento) => {
                if (acc.indexOf(descuento.nomina) === -1) {
                    acc.push(descuento.nomina);
                }
                return acc;
            }, []);

            // Ordenar periodos de forma descendente, se convierte a fecha para poder ordenar
            return periodos.sort((a, b) => new Date(b) - new Date(a));
        },
        descuentosPerPeriod: state => {
            const items = state.descuentos.filter(
                item => item.nomina === state.selectedPeriod && item.mliquid !== 'ALERTA'
            );

            return {
                items: items.map(item => ({ ...item, check: false })),
                total: items.length
            };
        }
    },
    mutations: {
        setDescuentos: (state, payload) => {
            state.descuentos = payload;
        },
        setDescuentosType: (state, payload) => {
            state.descuentosType = payload;
        },
        setSelectedPeriod: (state, payload) => {
            state.selectedPeriod = payload;
        }
    },
    actions: {
        fetchDescuentos: (ctx, data) => {
            ctx.commit('setDescuentos', data);

            // Seleccionar el primer periodo por defecto
            if (ctx.getters.descuentosPeriodos.length > 0) {
                ctx.commit('setSelectedPeriod', ctx.getters.descuentosPeriodos[0]);
            }
        }
    }
};

export default descuentosModule;
