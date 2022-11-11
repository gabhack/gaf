const pagaduriasModule = {
  namespaced: true,
  state: {
    coupons: [],
    pagaduriaType: '',
    selectedPeriod: ''
  },
  getters: {
    couponsPerPeriod: state => {
      const items = state.coupons.filter(
        item => item.finperiodo === state.selectedPeriod || item.inicioperiodo === state.selectedPeriod
      );

      return {
        items: items,
        total: items.length
      };
    },
    couponsIngresos: (state, getters) => {
      const items = getters.couponsPerPeriod.items.filter(item => item.code !== 'SUEBA' && Number(item.egresos) > 0);

      return {
        items: items,
        total: items.length
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
      const periodos = state.coupons.reduce((acc, coupon) => {
        if (acc.indexOf(coupon.inicioperiodo) === -1) {
          acc.push(coupon.inicioperiodo);
        }
        return acc;
      }, []);

      // Ordenar periodos de forma descendente
      return periodos.sort((a, b) => b - a);
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
      const actualMonth = 9; //new Date().getMonth() + 1;

      // Se obtienen los valores de año y mes por separado
      const newItems = getters.ingresosIncapacidad.items.map(item => {
        const year = item.inicioperiodo.toString().substring(0, 4);
        const month = item.inicioperiodo.toString().substring(5, 7);

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
    setCoupons: (state, payload) => {
      state.coupons = payload;
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
      ctx.commit('setSelectedPeriod', ctx.getters.pagaduriaPeriodos[0]);
    }
  }
};

export default pagaduriasModule;
