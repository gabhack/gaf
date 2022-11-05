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
    salarioBasico: (state, getters) => {
      return getters.couponsPerPeriod.items.find(coupon => coupon.code === 'SUEBA')?.ingresos;
    },
    pagaduriaPeriodos: state => {
      const periodos = state.coupons.reduce((acc, coupon) => {
        if (acc.indexOf(coupon.inicioperiodo) === -1) {
          acc.push(coupon.inicioperiodo);
        }
        return acc;
      }, []);

      // return in reverse order
      return periodos.sort((a, b) => b - a);
    }
  },
  mutations: {
    setPagaduriaType: (state, payload) => {
      state.pagaduriaType = payload;
    },
    setCoupons: (state, payload) => {
      const items = payload.map(item => {
        return {
          ...item,
          nomtercero: item.concept,
          vaplicado: item.egresos
        };
      });

      state.coupons = items;
    },
    setSelectedPeriod: (state, payload) => {
      state.selectedPeriod = payload;
    }
  }
};

export default pagaduriasModule;
