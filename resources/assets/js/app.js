/**
 * Load project JS requirements.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Initialise Vue instance for student-related stuff.
 */

Vue.component('polar-chart', require('./components/PolarChart.vue'));

const app = new Vue({
    el: '#app',

    data: {
        sciReflect: sciReflect
    }
});
