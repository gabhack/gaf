import { setCurrentPeriod, floatToInt } from './utils';

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
            { label: 'SED ARAUCA', value: 'SEDARAUCA', key: 'SED ARAUCA' },
            { label: 'SED ATLANTICO', value: 'SEDATLANTICO', key: 'SED ATLANTICO'},
            { label: 'SED BOLIVAR', value: 'SEDBOLIVAR', key: 'SED BOLIVAR' },
            { label: 'SED BOYACA', value: 'SEDBOYACA', key: 'SED BOYACA' },
            { label: 'SED CALDAS', value: 'SEDCALDAS', key: 'SED CALDAS' },
            { label: 'SED CASANARE', value: 'SEDCASANARE', key: 'SED CASANARE' },
            { label: 'SED CAUCA', value: 'SEDCAUCA', key: 'SED CAUCA'},
            { label: 'SED CHOCO', value: 'SEDCHOCO', key: 'SED CHOCO'},
            { label: 'SED CORDOBA', value: 'SEDCORDOBA', key: 'SED CORDOBA' },
            { label: 'SED CUNDINAMARCA', value: 'SEDCUNDINAMARCA', key: 'SED CUNDINAMARCA'},
            { label: 'SED GUAJIRA', value: 'SEDGUAJIRA', key: 'SED GUAJIRA' },
            { label: 'SED GUAVIARE', value: 'SEDGUAVIARE', key: 'SED GUAVIARE' },
            { label: 'SED MAGDALENA', value: 'SEDMAGDALENA', key: 'SED MAGDALENA'},
            { label: 'SED META', value: 'SEDMETA', key: 'meta' && 'SED META' },
            { label: 'SED NARINO', value: 'SEDNARINO', key: 'SED NARINO'},
            { label: 'SED NORTE SANTANDER', value: 'SEDNORTEDESANTANDER', key: 'SED NORTE DE SANTANDER' },
            { label: 'SED SANTANDER', value: 'SEDSANTANDER', key: 'SED SANTANDER' },
            { label: 'SED RISARALDA', value: 'SEDRISARALDA', key: 'SED RISARALDA'},
            { label: 'SED SUCRE', value: 'SEDSUCRE', key: 'SED SUCRE' },
            { label: 'SED TOLIMA', value: 'SEDTOLIMA', key: 'SED TOLIMA' },
            { label: 'SED VALLE', value: 'SEDVALLE', key: 'SED VALLE' },
            { label: 'SED CAQUETA', value: 'SEDCAQUETA', key: 'SED CAQUETA' },
            { label: 'SED PUTUMAYO', value: 'SEDPUTUMAYO', key: 'SED PUTUMAYO' },
            { label: 'SED CESAR', value: 'SEDCESAR', key: 'SED CESAR' },
            { label: 'SED QUINDIO', value: 'SEDQUINDIO', key: 'SED QUINDIO' },
            { label: 'SED AMAZONAS', value: 'SEDAMAZONAS', key: 'SED AMAZONAS' },
            { label: 'SED VAUPES', value: 'SEDVAUPES', key: 'SED VAUPES' },
            { label: 'SED VICHADA', value: 'SEDVICHADA', key: 'SED VICHADA' },
            {
                label: 'SEM BARRANQUILLA',
                value: 'SEMBARRANQUILLA',
                key: 'SEM BARRANQUILLA'
            },
            { label: 'SEM APARTADO', value: 'SEMAPARTADO', key: 'SEM APARTADO' },
            { label: 'SEM ESTRELLA', value: 'SEMESTRELLA', key: 'SEM ESTRELLA' },
            { label: 'SEM SANTA MARTA', value: 'SEMSANTAMARTA', key: 'SEM SANTA MARTA' },
            { label: 'SEM BARRANCABERMEJA', value: 'SEMBARRANCABERMEJA', key: 'SEM BARRANCABERMEJA' },
            { label: 'SEM RIOHACHA', value: 'SEMRIOHACHA', key: 'SEM RIOHACHA' },
            { label: 'SEM RIONEGRO', value: 'SEMRIONEGRO', key: 'SEM RIONEGRO' },
            { label: 'SEM SABANETA', value: 'SEMSABANETA', key: 'SEM SABANETA' },
            { label: 'SEM SAN ANDRES', value: 'SEMSAN', key: 'SEM SAN' },
            { label: 'SEM SOACHA', value: 'SEMSOACHA', key: 'SEM SOACHA' },
            { label: 'SEM PITALITO', value: 'SEMPITALITO', key: 'SEM PITALITO' },
            { label: 'SEM BUGA', value: 'SEMBUGA', key: 'SEM BUGA' },
            { label: 'SEM CUCUTA', value: 'SEMCUCUTA', key: 'SEM CUCUTA' },
            { label: 'SEM SOGAMOSO', value: 'SEMSOGAMOSO', key: 'SEM SOGAMOSO' },
            { label: 'SEM TULUA', value: 'SEMTULUA', key: 'SEM TULUA' },
            { label: 'SEM TUMACO', value: 'SEMTUMACO', key: 'SEM TUMACO' },
            { label: 'SEM CIENAGA', value: 'SEMCIENAGA', key: 'SEM CIENAGA' },
            { label: 'SEM CALI', value: 'SEMCALI', key: 'SEM CALI'},
            { label: 'SEM DOSQUEBRADAS', value: 'SEMDOSQUEBRADAS', key: 'SEM DOSQUEBRADAS' },
            { label: 'SEM CARTAGENA', value: 'SEMCARTAGENA', key: 'SEM CARTAGENA' },
            { label: 'SEM ENVIGADO', value: 'SEMENVIGADO', key: 'SEM ENVIGADO' },
            { label: 'SEM CARTAGO', value: 'SEMCARTAGO', key: 'SEM CARTAGO' },
            { label: 'SEM BELLO', value: 'SEMBELLO', key: 'SEM BELLO' },
            { label: 'SEM DUITAMA', value: 'SEMDUITAMA', key: 'SEM DUITAMA' },
            { label: 'SEM GIRON', value: 'SEMGIRON', key: 'SEM GIRON' },
            { label: 'SEM CHIA', value: 'SEMCHIA', key: 'SEM CHIA' },
            { label: 'SEM VILLAVICENCIO', value: 'SEMVILLAVICENCIO', key: 'SEM VILLAVICENCIO' },
            { label: 'SEM IPIALES', value: 'SEMIPIALES', key: 'SEM IPIALES' },
            { label: 'SEM JAMUNDI', value: 'SEMJAMUNDI', key: 'SEM JAMUNDI' },
            { label: 'SEM MAGANGUE', value: 'SEMMAGANGUE', key: 'SEM MAGANGUE' },
            { label: 'SEM MONTERIA', value: 'SEMMONTERIA', key: 'SEM MONTERIA'},
            { label: 'SED HUILA', value: 'SEDHUILA', key: 'SED HUILA' },
            { label: 'SEM NEIVA', value: 'SEMNEIVA', key: 'SEM NEIVA'},
            { label: 'SEM PALMIRA', value: 'SEMPALMIRA', key: 'SEM PALMIRA' },
            { label: 'SEM GUAINIA', value: 'SEMGUAINIA', key: 'SEM GUAINIA' },
            { label: 'SEM ITAGUI', value: 'SEMITAGUI', key: 'SEM ITAGUI' },
            { label: 'SEM PIEDECUESTA', value: 'SEMPIEDECUESTA', key: 'SEM PIEDECUESTA' },
            { label: 'SEM PEREIRA', value: 'SEMPEREIRA', key: 'SEM PEREIRA' },
            { label: 'SEM MEDELLIN', value: 'SEMMEDELLIN', key: 'SEM MEDELLIN' },
            { label: 'SEM MANIZALES', value: 'SEMMANIZALES', key: 'SEM MANIZALES' },
            { label: 'SEM PASTO', value: 'SEMPASTO', key: 'SEM PASTO'},
            { label: 'SEM MAICAO', value: 'SEMMAICAO', key: 'SEM MAICAO' },
            { label: 'SEM MALAMBO', value: 'SEMMALAMBO', key: 'SEM MALAMBO' },
            { label: 'SEM POPAYAN', value: 'SEMPOPAYAN', key: 'SEM POPAYAN' },
            { label: 'SEM QUIBDO', value: 'SEMQUIBDO', key: 'SEM QUIBDO' },
            { label: 'SEM RIONEGRO', value: 'SEMRIONEGRO', key: 'SEM RIONEGRO'},
            { label: 'SEM SABANETA', value: 'SEMSABANETA', key: 'SEM SABANETA' },
            { label: 'SEM SAHAGUN', value: 'SEMSAHAGUN', key: 'SEM SAHAGUN' },
            { label: 'SED SINCELEJO', value: 'SEDSINCELEJO', key: 'SED SINCELEJO' },
            { label: 'SEM SOLEDAD', value: 'SEMSOLEDAD', key: 'SEM SOLEDAD' },
            { label: 'SEM VALLEDUPAR', value: 'SEMVALLEDUPAR', key: 'SEM VALLEDUPAR' },
            { label: 'SEM YOPAL', value: 'SEMYOPAL', key: 'SEM YOPAL' },
            { label: 'SEM YUMBO', value: 'SEMYUMBO', key: 'SEM YUMBO' },
            { label: 'SEM ZIPAQUIRA', value: 'SEMZIPAQUIRA', key: 'SEM ZIPAQUIRA' },
            { label: 'SEM MOSQUERA', value: 'SEMMOSQUERA', key: 'SEM MOSQUERA'},
            { label: 'SEM URIBIA', value: 'SEMURIBIA', key: 'SEM URIBIA' },
            { label: 'SEM TURBO', value: 'SEMTURBO', key: 'SEM TURBO' },
            { label: 'SEM TUNJA', value: 'SEMTUNJA', key: 'SEM TUNJA' },
            { label: 'SEM BUCARAMANGA', value: 'SEMBUCARAMANGA', key: 'SEM BUCARAMANGA' },
            { label: 'SEM BUENAVENTURA', value: 'SEMBUENAVENTURA', key: 'SEM BUENAVENTURA' },
            { label: 'SEM ARMENIA', value: 'SEMARMENIA', key: 'SEM ARMENIA' },
            { label: 'SEM FLORENCIA', value: 'SEMFLORENCIA', key: 'SEM FLORENCIA' },
            { label: 'SEM FLORIDABLANCA', value: 'SEMFLORIDABLANCA', key: 'SEM FLORIDABLANCA' },
            { label: 'SEM FACATATIVA', value: 'SEMFACATATIVA', key: 'SEM FACATATIVA' },
            { label: 'SEM FUSAGAZUGA', value: 'SEMFUSAGAZUGA', key: 'SEM FUSAGAZUGA' },
            { label: 'SEM GIRARDOT', value: 'SEMGIRARDOT', key: 'SEM GIRARDOT' },
            { label: 'SEM LORICA', value: 'SEMLORICA', key: 'SEM LORICA' },
            { label: 'SEM IBAGUE', value: 'SEMIBAGUE', key: 'SEM IBAGUE' },
            { label: 'SEM FUNZA', value: 'SEMFUNZA', key: 'SEM FUNZA' }
        ],
        selectedPeriod: ''
    },
    getters: {
        couponsPerPeriod: state => {
            return state.coupons.filter(item => {
                const periodToMatch = new Date(state.selectedPeriod).toISOString().slice(0, 10);
                const inicioPeriodo = new Date(item.inicioperiodo).toISOString().slice(0, 10);
                const finPeriodo = new Date(item.finperiodo).toISOString().slice(0, 10);
        
                return inicioPeriodo === periodToMatch || finPeriodo === periodToMatch;
            });
        },
        couponsIngresos: (state, getters) => {
            const items = getters.couponsPerPeriod.items.filter(
                item => Number(item.egresos) > 0
            );

            return {
                items: items,
                total: items.length,
                // amount: items.reduce((egresos, item) => egresos + Number(item.egresos), 0)
                amount: items.reduce((egresos, item) => {
                    const valorEgresos = Number(item.egresos);
                    return valorEgresos >= 0 ? egresos + valorEgresos : egresos;
                }, 0)
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
        // salarioBasico: (state, getters) => {
        //     return getters.couponsPerPeriod.items.find(coupon => coupon.code === 'SUEBA')?.ingresos || 0;
        // },


        // salarioBasico: (state, getters) => {
        //     return getters.couponsPerPeriod.items.filter(coupon => coupon.code === 'SUEBA').map(coupon => coupon.ingresos);
        // },
        
        salarioBasico: (state, getters) => {
            return getters.couponsPerPeriod.items
                .filter(coupon => coupon.code === 'SUEBA')
                .map(coupon => ({
                    concept: coupon.concept,
                    ingresos: coupon.ingresos
                }));
        },


        pagaduriaPeriodos: state => {
            console.log('Cupones en pagaduriaPeriodos:', state.coupons);
            let periodos = state.coupons.reduce((acc, coupon) => {
                const cleanFinPeriodo = coupon.finperiodo?.trim();
                if (!cleanFinPeriodo || isNaN(new Date(cleanFinPeriodo).getTime())) {
                    console.warn('Periodo inválido encontrado:', coupon);
                    return acc;
                }
            
                if (!acc.includes(cleanFinPeriodo)) {
                    acc.push(cleanFinPeriodo);
                }
                return acc;
            }, []);
            
        
            console.log('Periodos únicos antes de agregar el actual:', periodos);
        
            periodos = setCurrentPeriod(periodos);
        
            console.log('Periodos después de setCurrentPeriod:', periodos);
        
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
            try {
                console.log('Datos recibidos en fetchCoupons:', data);
    
                // Mapea los datos entrantes para asegurarte de que sean consistentes
                const items = data.map(item => {
                    return {
                        ...item,
                        nomtercero: item.concept,
                        ingresos: floatToInt(item.ingresos),
                        egresos: floatToInt(item.egresos),
                        vaplicado: floatToInt(item.egresos) // Asegúrate de convertir esto a un número
                    };
                });
    
                console.log('Datos mapeados en fetchCoupons:', items);
    
                // Commit a la mutación para guardar los datos
                ctx.commit('setCoupons', items);
    
                // Seleccionar el primer período por defecto si existe
                const periods = ctx.getters.pagaduriaPeriodos;
                if (periods.length > 0) {
                    console.log('Períodos disponibles:', periods);
                    ctx.commit('setSelectedPeriod', periods[0]);
                } else {
                    console.warn('No se encontraron períodos disponibles en los datos de cupones.');
                }
            } catch (error) {
                console.error('Error en fetchCoupons:', error);
            }
        }
    }
};

export default pagaduriasModule;
