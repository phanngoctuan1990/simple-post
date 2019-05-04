
require('./bootstrap')

window.Vue = require('vue')

import store from './store'
import Vuetify from 'vuetify'
import App from './components/App'
import Router from './routes/index'
import VeeValidate from 'vee-validate'

Vue.use(Vuetify)
Vue.use(VeeValidate)

const app = new Vue({
    el: '#app',
    store,
    router: Router,
    render: h => h(App)
});
