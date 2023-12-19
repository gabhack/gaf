import { setCurrentPeriod } from './utils';

const pagaduriasModule = {
    namespaced: true,
    state: {
        coupons: [],
        couponsType: '',
        pagaduriaType: '',
        pagaduriaLabel: '',
        pagaduriasTypes: [
            { label: 'FIDUPREVISORA', value: 'FIDUPREVISORA', key: 'datamesFidu' },
            { label: 'FOPEP', value: 'FOPEP', key: 'datamesFopep' },
            { label: 'SED ANTIOQUIA', value: 'SEDANTIOQUIA', key: 'SED ANTIOQUIA' },
            { label: 'SED ARAUCA', value: 'SEDARAUCA', key: 'datamesSedArauca' },
            { label: 'SED ATLANTICO', value: 'SEDATLANTICO', key: 'datamesSedAtlantico' },
            { label: 'SED BOLIVAR', value: 'SEDBOLIVAR', key: 'datamesSedBolivar' },
            { label: 'SED BOYACA', value: 'SEDBOYACA', key: 'SED BOYACA' },
            { label: 'SED CALDAS', value: 'SEDCALDAS', key: 'datamesSedCaldas' },
            { label: 'SED CASANARE', value: 'SEDCASANARE', key: 'SED CASANARE' },
            { label: 'SED CAUCA', value: 'SEDCAUCA', key: 'datamesSedCauca' },
            { label: 'SED CESAR', value: 'SEDCESAR', key: 'datamesSedCesar' },
            { label: 'SED CHOCO', value: 'SEDCHOCO', key: 'datamesSedChoco' },
            { label: 'SED CORDOBA', value: 'SEDCORDOBA', key: 'datamesSedCordoba' },
            { label: 'SED CUNDINAMARCA', value: 'SEDCUNDINAMARCA', key: 'SED CUNDINAMARCA' && 'cundinamarca' },
            { label: 'SED GUAJIRA', value: 'SEDGUAJIRA', key: 'datamesSedGuajira' },
            { label: 'SED HUILA', value: 'SEDHUILA', key: 'SED HUILA' },
            { label: 'SED MAGDALENA', value: 'SEDMAGDALENA', key: 'datamesSedMagdalena' },
            { label: 'SED META', value: 'SEDMETA', key: 'meta' && 'SED META' },
            { label: 'SED NARINO', value: 'SEDNARINO', key: 'narino' },
            { label: 'SED NORTE SANTANDER', value: 'SEDNORTEDESANTANDER', key: 'SED NORTEDESANTANDER' },
            { label: 'SED RISARALDA', value: 'SEDRISARALDA', key: 'risaralda' },
            { label: 'SED SUCRE', value: 'SEDSUCRE', key: 'datamesSedSucre' },
            { label: 'SED TOLIMA', value: 'SEDTOLIMA', key: 'datamesSedTolima' },
            { label: 'SED VALLE', value: 'SEDVALLE', key: 'datamesSedValle' },
            { label: 'SED CAQUETA', value: 'SEDCAQUETA', key: 'SED CAQUETA' },
            { label: 'SEM BARRANQUILLA', value: 'SEMBARRANQUILLA', key: 'datamesSemBarranquilla' },
            { label: 'SEM BUGA', value: 'SEMBUGA', key: 'SEM BUGA'},
            { label: 'SEM CALI', value: 'SEMCALI', key: 'datamesSemCali' },
            { label: 'SEM CARTAGENA', value: 'SEMCARTAGENA', key: 'datamesSemCartagena' },
            { label: 'SEM CARTAGO', value: 'SEMCARTAGO', key: 'SEM CARTAGO' },
            { label: 'SEM DUITAMA', value: 'SEMDUITAMA', key: 'SEM DUITAMA' },
            { label: 'SEM GIRON', value: 'SEMGIRON', key: 'SEM GIRON' },
            { label: 'SEM CHIA', value: 'SEMCHIA', key: 'SEM CHIA' },
            { label: 'SEM VILLAVICENCIO', value: 'SEMVILLAVICENCIO', key: 'SEM VILLAVICENCIO' },
            { label: 'SEM GIRARDOT', value: 'SEMGIRARDOT', key: 'datamesSemGirardot' },
            { label: 'SEM IBAGUE', value: 'SEMIBAGUE', key: 'datamesSemIbague' },
            { label: 'SEM IPIALES', value: 'SEMIPIALES', key: 'SEM IPIALES' },
            { label: 'SEM JAMUNDI', value: 'SEMJAMUNDI', key: 'SEM JAMUNDI' },
            { label: 'SEM MAGANGUE', value: 'SEMMAGANGUE', key: 'SEM MAGANGUE' },
            { label: 'SEM MEDELLIN', value: 'SEMMEDELLIN', key: 'datamesSemMedellin' },
            { label: 'SEM MONTERIA', value: 'SEMMONTERIA', key: 'datamesSemMonteria' },
            { label: 'SEM NEIVA', value: 'SEMNEIVA', key: 'SEM NEIVA' && 'neiva' },
            { label: 'SEM PALMIRA', value: 'SEMPALMIRA', key: 'SEM PALMIRA' },
            { label: 'SEM PASTO', value: 'SEMPASTO', key: 'datamesSemPasto' },
            { label: 'SEM POPAYAN', value: 'SEMPOPAYAN', key: 'datamesSemPopayan' },
            { label: 'SEM QUIBDO', value: 'SEMQUIBDO', key: 'datamesSemQuibdo' },
            { label: 'SEM RIONEGRO', value: 'SEMRIONEGRO', key: 'datamesSemRioNegro' },
            { label: 'SEM SABANETA', value: 'SEMSABANETA', key: 'datamesSemSabaneta' },
            { label: 'SEM SAHAGUN', value: 'SEMSAHAGUN', key: 'datamesSemSahagun' },
            { label: 'SEM SINCELEJO', value: 'SEMSINCELEJO', key: 'datamesSemSincelejo' },
            { label: 'SEM SOLEDAD', value: 'SEMSOLEDAD', key: 'datamesSemSoledad' },
            { label: 'SEM VALLEDUPAR', value: 'SEMVALLEDUPAR', key: 'SEM VALLEDUPAR' },
            { label: 'SEM YOPAL', value: 'SEMYOPAL', key: 'SEM YOPAL' },
            { label: 'SEM YUMBO', value: 'SEMYUMBO', key: 'SEM YUMBO' },
            { label: 'SEM ZIPAQUIRA', value: 'SEMZIPAQUIRA', key: 'SEM ZIPAQUIRA' },
            { label: 'SEM MOSQUERA', value: 'SEMMOSQUERA', key:'SEM MOSQUERA' && 'mosquera' },
            { label: 'SEM URIBIA', value: 'SEMURIBIA', key: 'SEM URIBIA' },
            { label: 'SEM TURBO', value: 'SEMTURBO', key: 'SEM TURBO' },
            { label: 'SEM TUNJA', value: 'SEMTUNJA', key: 'SEM TUNJA' },
            { label: 'SEM BUCARAMANGA', value: 'SEMBUCARAMANGA', key: 'SEM BUCARAMANGA' },
            { label: 'SEM BUENAVENTURA', value: 'SEMBUENAVENTURA', key: 'SEM BUENAVENTURA' },
            { label: 'SEM ARMENIA', value: 'SEMARMENIA', key: 'SEM ARMENIA' },
            { label: 'SEM FUNZA', value: 'SEMFUNZA', key: 'funza' }
        ],
        selectedPeriod: ''
    },
    getters: {
        couponsPerPeriod: state => {
            const items = state.coupons.filter(
                item => item.inicioperiodo === state.selectedPeriod || item.finperiodo === state.selectedPeriod
            );

            return {
                items: items,
                total: items.length
            };
        },
        couponsIngresos: (state, getters) => {
            const items = getters.couponsPerPeriod.items.filter(
                item => item.code !== 'SUEBA' && Number(item.egresos) > 0
            );

            return {
                items: items,
                total: items.length,
                amount: items.reduce((egresos, item) => egresos + Number(item.egresos), 0)
            };
        },
        ingresosExtras: (state, getters) => {
            const items = getters.couponsPerPeriod.items.filter(
                item => item.code !== 'SUEBA' && item.code !== 'INGCUP' && Number(item.ingresos) > 0
            );

            return items;
        },
        valorIngreso: (state, getters) => {
            return getters.couponsPerPeriod.items.find(coupon => coupon.code === 'INGCUP')?.ingresos || 0;
        },
        salarioBasico: (state, getters) => {
            return getters.couponsPerPeriod.items.find(coupon => coupon.code === 'SUEBA')?.ingresos || 0;
        },
        pagaduriaPeriodos: state => {
            let periodos = state.coupons.reduce((acc, coupon) => {
                if (acc.indexOf(coupon.finperiodo) === -1) {
                    acc.push(coupon.finperiodo);
                }
                return acc;
            }, []);

            // Agregar el periodo actual si no existe
            periodos = setCurrentPeriod(periodos);

            // Ordenar periodos de forma descendente, se convierte a fecha para poder ordenar
            return periodos.sort((a, b) => new Date(b) - new Date(a));
        },
        ingresosIncapacidad: state => {
            const items = state.coupons.filter(
                item => (item.code === 'PGINC' || item.code === 'PGINC100') && Number(item.ingresos) > 0
            );

            const amount = items.reduce((ingresos, item) => ingresos + Number(item.ingresos), 0);

            return {
                items: items,
                total: items.length,
                amount: amount
            };
        },
        ingresosIncapacidadPerPeriod: (state, getters) => {
            const items = getters.couponsPerPeriod.items.filter(
                item => (item.code === 'PGINC' || item.code === 'PGINC100') && Number(item.ingresos) > 0
            );

            const amount = items.reduce((ingresos, item) => ingresos + Number(item.ingresos), 0);

            return {
                items: items,
                total: items.length,
                amount: amount
            };
        },
        incapacidadValida: (state, getters) => {
            const monthsNumber = 2;

            const actualYear = new Date().getFullYear();
            const actualMonth = 10; // new Date().getMonth() + 1;

            // Se obtienen los valores de año y mes por separado
            const newItems = getters.ingresosIncapacidad.items.map(item => {
                const year = item.finperiodo.toString().substring(0, 4);
                const month = item.finperiodo.toString().substring(5, 7);

                return {
                    ...item,
                    year: Number(year),
                    month: Number(month)
                };
            });

            // Se buscan los cupones que esten 3 meses antes o del mes actual
            const periodsByYear = newItems.filter(item => {
                return item.year === actualYear && item.month > actualMonth - monthsNumber;
            });

            // Agrupar los periodos por meses
            const periodsGroupByMonth = periodsByYear.reduce((group, item) => {
                const { month } = item;

                group[month] = group[month] || [];
                group[month].push(item);
                return group;
            }, {});

            // Si existen cupones en los 3 meses anteriores no se puede aplicar la incapacidad
            return Object.keys(periodsGroupByMonth).length < monthsNumber ? true : false;
        }
    },
    mutations: {
        setPagaduriaType: (state, payload) => {
            state.pagaduriaType = payload;
        },
        setPagaduriaLabel: (state, payload) => {
            state.pagaduriaLabel = payload;
        },
        setCoupons: (state, payload) => {
            state.coupons = payload;
        },
        setCouponsType: (state, payload) => {
            state.couponsType = payload;
        },
        setSelectedPeriod: (state, payload) => {
            state.selectedPeriod = payload;
        }
    },
    actions: {
        fetchCoupons: (ctx, data) => {
            const items = data.map(item => {
                return {
                    ...item,
                    nomtercero: item.concept,
                    vaplicado: item.egresos
                };
            });

            ctx.commit('setCoupons', items);

            // Seleccionar el primer periodo por defecto
            if (ctx.getters.pagaduriaPeriodos.length > 0) {
                ctx.commit('setSelectedPeriod', ctx.getters.pagaduriaPeriodos[0]);
            }
        }
    }
};

export default pagaduriasModule;
