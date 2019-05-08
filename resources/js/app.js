
require('./bootstrap')

window.Vue = require('vue')

import store from './store'
import Vuetify from 'vuetify'
import App from './components/App'
import Router from './routes'
import VeeValidate from 'vee-validate'
import dateFormat from './filters/dateFormat'
import subStrirng from './filters/subString'

Vue.use(Vuetify)
Vue.use(VeeValidate)
Vue.filter('subString', subStrirng)
Vue.filter('dateFormat', dateFormat)

const app = new Vue({
    el: '#app',
    store,
    router: Router,
    render: h => h(App)
});
