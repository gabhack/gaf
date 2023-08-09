const descuentosModule = {
    namespaced: true,
    state: {
        descuentos: [],
        selectedPeriod: ''
    },
    getters: {
        descuentosPeriodos: state => {
            const periodos = state.descuentos.reduce((acc, coupon) => {
                if (acc.indexOf(coupon.nomina) === -1) {
                    acc.push(coupon.nomina);
                }
                return acc;
            }, []);

            // Ordenar periodos de forma descendente, se convierte a fecha para poder ordenar
            return periodos.sort((a, b) => new Date(b) - new Date(a));
        },
        descuentosPerPeriod: state => {
            const items = state.descuentos.filter(item => item.nomina === state.selectedPeriod);

            return {
                items: items,
                total: items.length
            };
        }
    },
    mutations: {
        setDescuentos: (state, payload) => {
            state.descuentos = payload;
        },
        setSelectedPeriod: (state, payload) => {
            state.selectedPeriod = payload;
        }
    },
    actions: {
        fetchDescuentos: (ctx, data) => {
            ctx.commit('setDescuentos', data);

            // Seleccionar el primer periodo por defecto
            ctx.commit('setSelectedPeriod', ctx.getters.descuentosPeriodos[0]);
        }
    }
};

export default descuentosModule;
