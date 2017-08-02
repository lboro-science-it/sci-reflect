
/**
 * Load JS dependencies
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Create Vue instance for staff-specific stuff. All staff views will have
 * this instance.
 */

Vue.component('polar-chart', require('./components/PolarChart.vue'));

const app = new Vue({
    el: '#app'
});
