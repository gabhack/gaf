const embargosModule = {
    namespaced: true,
    state: {
        embargos: [],
        embargosType: '',
        selectedPeriod: ''
    },
    getters: {
        embargosPeriodos: state => {
            const periodos = state.embargos.reduce((acc, embargo) => {
                if (acc.indexOf(embargo.nomina) === -1) {
                    acc.push(embargo.nomina);
                }
                return acc;
            }, []);

            // Ordenar periodos de forma descendente, se convierte a fecha para poder ordenar
            return periodos.sort((a, b) => new Date(b) - new Date(a));
        },
        embargosPerPeriod: state => {
            const items = state.embargos.filter(item => item.nomina === state.selectedPeriod);

            return {
                items: items,
                total: items.length
            };
        }
    },
    mutations: {
        setEmbargos: (state, payload) => {
            state.embargos = payload;
        },
        setEmbargosType: (state, payload) => {
            state.embargosType = payload;
        },
        setSelectedPeriod: (state, payload) => {
            state.selectedPeriod = payload;
        }
    },
    actions: {
        fetchEmbargos: (ctx, data) => {
            ctx.commit('setEmbargos', data);

            // Seleccionar el primer periodo por defecto
            if (ctx.getters.embargosPeriodos.length > 0) {
                ctx.commit('setSelectedPeriod', ctx.getters.embargosPeriodos[0]);
            }
        }
    }
};

export default embargosModule;
