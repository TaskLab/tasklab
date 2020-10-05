import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

interface StateData {
  onMobileDevice: boolean,
  onDarkMode: boolean
}

export default new Vuex.Store({
  state: {
    onMobileDevice: false,
    onDarkMode: false,
  },
  strict: process.env.NODE_ENV !== 'production',
  mutations: {
    updateOnMobileDevice(state: StateData, value: boolean): void {
      state.onMobileDevice = value;
    },
    updateOnDarkMode(state: StateData, value: boolean): void {
      state.onDarkMode = value;
    }
  }
})