export default {
  namespaced: true,
  state: {
    carts: [],
  },
  mutations: {
    insert: (state, payload) => {
      state.carts.push({
        id: payload.id,
        title: payload.title,
        cover: payload.cover,
        price: payload.price,
        weight: payload.weight,
        quantity: 1,
        stock: payload.stock, 
      });
    },
    update: (state, payload) => {
      const idx = state.carts.indexOf(payload);
      state.carts.splice(idx, 1, {
        id: payload.id,
        title: payload.title,
        cover: payload.cover,
        price: payload.price,
        weight: payload.weight,
        quantity: payload.quantity,
        stock: payload.stock, 
      });
      if (payload.quantity <= 0) {
        state.carts.splice(idx, 1);
      }
    },
    set: (state, payload) => {
      state.carts = payload;
    },
  },
  actions: {
    add: ({ state, commit }, payload) => {
      const cartItemIndex = state.carts.findIndex((item) => item.id === payload.id);
      if (cartItemIndex === -1) {
        if (payload.stock > 0) {
          commit('insert', payload);
        } else {
          console.log('Stok barang kosong');
        }
      } else {
        const updatedCarts = [...state.carts];
        const cartItem = { ...updatedCarts[cartItemIndex] }; 
        const newQuantity = cartItem.quantity + 1;
        if (newQuantity <= cartItem.stock) {
          cartItem.quantity = newQuantity;
          updatedCarts.splice(cartItemIndex, 1, cartItem); 
          commit('set', updatedCarts);
        } else {
          console.log('Stok barang tidak mencukupi');
        }
      }
    },
    remove: ({ state, commit }, payload) => {
      const cartItem = state.carts.find((item) => item.id === payload.id);
      if (cartItem) {
        if (cartItem.quantity === 1) {
          commit('update', { ...cartItem, quantity: 0 });
        } else {
          commit('update', { ...cartItem, quantity: cartItem.quantity - 1 });
        }
      }
    },
  },
  getters: {
    carts: (state) => state.carts,
    count: (state) => state.carts.length,
    totalQuantity: (state) =>
      state.carts.reduce((total, item) => total + item.quantity, 0),
    totalPrice: (state) =>
      state.carts.reduce((total, item) => total + item.price * item.quantity, 0),
    totalWeight: (state) =>
      state.carts.reduce((total, item) => total + item.weight * item.quantity, 0),
  },
};
