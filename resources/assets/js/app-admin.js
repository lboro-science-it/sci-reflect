
/**
 * Load JS dependencies
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Create Vue instance for staff-specific stuff. All staff views will have
 * this instance.
 */

Vue.component('group-listing', require('./components/GroupListing.vue'));
Vue.component('polar-chart', require('./components/PolarChart.vue'));
Vue.component('staff-partial', require('./components/StaffPartial.vue'));

import 'axios';

const app = new Vue({
    el: '#app',

    data: {
        partialContent: null
    },

    methods: {
        getPartial (route, event) {
            axios.get(route).then(response => {
                console.log(response.data);
            });
        }
    }
});
