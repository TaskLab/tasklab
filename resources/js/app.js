require('./bootstrap');
require('bootstrap/dist/css/bootstrap.min.css');

import Vue from 'vue';
import Vuex from 'vuex';
import { InertiaApp } from '@inertiajs/inertia-vue';
import store from './store.ts';

Vue.config.productionTip = false;
Vue.use(InertiaApp);
Vue.use(Vuex);

const app = document.getElementById('app');

new Vue({
  store,
  render: h => h(InertiaApp, {
    props: {
      initialPage: JSON.parse(app.dataset.page),
      resolveComponent: name => import(`./Pages/${name}`).then(module => module.default)
    }
  })
}).$mount(app)