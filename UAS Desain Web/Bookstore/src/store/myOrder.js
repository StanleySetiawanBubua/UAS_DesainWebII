export default {
    namespaced: true,
    state: {
      orders: [],
    },
    mutations: {
      addOrder: (state, order) => {
        state.orders.push(order);
      },
      clearOrders: (state) => {
        state.orders = [];
      },
    },
    actions: {
      addOrder: ({ commit }, order) => {
        commit('addOrder', order);
      },
      clearOrders: ({ commit }) => {
        commit('clearOrders');
      },
    },
    getters: {
      orders: (state) => state.orders,
    },
  }
  