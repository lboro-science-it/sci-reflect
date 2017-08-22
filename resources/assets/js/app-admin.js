
/**
 * Load JS dependencies
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Create Vue instance for staff-specific stuff. All staff views will have
 * this instance.
 */


Vue.component('activity-content', require('./components/activity/ActivityContent.vue'));
Vue.component('activity-pages', require('./components/activity/ActivityPages.vue'));
Vue.component('activity-rounds', require('./components/activity/ActivityRounds.vue'));
Vue.component('activity-setup', require('./components/activity/ActivitySetup.vue'));
Vue.component('activity-skills', require('./components/activity/ActivitySkills.vue'));

Vue.component('draggable', require('vuedraggable'));

Vue.component('group-batch', require('./components/groups/GroupBatch.vue'));
Vue.component('group-bulk', require('./components/groups/GroupBulk.vue'));
Vue.component('group-row', require('./components/groups/GroupRow.vue'));
Vue.component('group-table', require('./components/groups/GroupTable.vue'));

Vue.component('round-add', require('./components/rounds/RoundAdd.vue'));
Vue.component('rounds-list', require('./components/rounds/RoundsList.vue'));
Vue.component('rounds-setup', require('./components/rounds/RoundsSetup.vue'));

Vue.component('skill-list-item', require('./components/ratings/SkillListItem.vue'));
Vue.component('skill-rater', require('./components/ratings/SkillRater.vue'));
Vue.component('student-rater', require('./components/ratings/StudentRater.vue'));

Vue.component('staff-row', require('./components/staff/StaffRow.vue'));
Vue.component('staff-table', require('./components/staff/StaffTable.vue'));

Vue.component('student-row', require('./components/students/StudentRow.vue'));
Vue.component('student-table', require('./components/students/StudentTable.vue'));

Vue.component('polar-chart', require('./components/PolarChart.vue'));
Vue.component('staff-partial', require('./components/StaffPartial.vue'));

import 'axios';


const app = new Vue({
    el: '#app',

    data: {
        partialContent: null,
        sciReflect: sciReflect
    },

    methods: {
        getPartial (route, event) {
            axios.get(route).then(response => {
                console.log(response.data);
            });
        }
    }
});