require('./bootstrap');

window.Vue = require('vue');

Vue.component('posts', require('./components/Posts.vue').default);

module.exports = {
    chainWebpack: config => config.resolve.set('symlinks', false)
}

const app = new Vue({
    el: '#app'
});
