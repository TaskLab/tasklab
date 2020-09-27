require('./bootstrap');
require('bootstrap/dist/css/bootstrap.min.css');

import Vue from 'vue';
import { InertiaApp } from '@inertiajs/inertia-vue'

Vue.config.productionTip = false
Vue.use(InertiaApp)

const app = document.getElementById('app')

new Vue({
  render: h => h(InertiaApp, {
    props: {
      initialPage: JSON.parse(app.dataset.page),
      resolveComponent: name => import(`./Pages/${name}`).then(module => module.default)
    }
  })
}).$mount(app)