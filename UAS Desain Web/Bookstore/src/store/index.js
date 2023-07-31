import Vue from 'vue'
import Vuex from 'vuex'
import cart from '../store/cart'
import alert from '../store/alert'
import dialog from '../store/dialog'
import auth from '../store/auth'
import region from '../store/region'
import myOrder from './myOrder';


Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    sideBar: false,
    prevUrl: '',
    payment: []
  },
  getters: {
    sideBar: state => state.sideBar,
    prevUrl: state => state.prevUrl,
    payment: state => state.payment,
  },
  mutations: {
    setSideBar: (state, value) => {
      state.sideBar = value
    },
    setPrevUrl: (state, value) => {
      state.prevUrl = value;
    },
    setPayment: (state, value) => {
      state.payment = value
    },
  },
  actions: {
    setSideBar: ({commit}, value) => {
      commit('setSideBar', value)
    },
    setPrevUrl: ({ commit }, value) => {
    commit('setPrevUrl', value)
    },
    setPayment: ({commit}, value) => {
      commit('setPayment', value)
    },
  },
  modules: {
    cart,
    alert,
    dialog,
    auth,
    region,
    myOrder,
  }
})
