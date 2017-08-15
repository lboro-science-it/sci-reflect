<template>
    <tr v-show="showRow()">
        <td><input type="checkbox"
                   :checked="checked"
                   v-model="checkedStatus"
                   v-on:change="checkboxChanged"
                   v-bind:true-value="true"
                   v-bind:false-value="false">
        </td>
        <td>{{ (student.name != '') ? student.name : student.email }}</td>
        <td v-show="mode == 'overview'">
            <select v-model="editGroupId" v-on:change="changedGroup">
                <option value="null">No group</option>
                <option v-for="group in groups" :value="group.id">{{ group.name }}</option>
            </select>
        </td>
        <template v-for="round in student.rounds">
            <td v-show="mode == 'overview'"
                :class="{ 
                    info: student.currentRoundNumber == round.roundNumber,
                    success: round.completion == '100%'
                }">
                {{ round.completion }}
            </td>
            <td v-show="mode == 'overview'">
                <a class="btn"
                   :href="getRateLink(round.roundNumber)"
                   :class="{ 'disabled': round.staffHasRated,
                             'btn-success': round.staffHasRated,
                             'btn-info': !round.staffHasRated }"
                   v-show="round.staffCanRate">Rate</a>
            </td>
        </template>
       
        <td v-show="mode == 'details'" :class="{ success: student.hasAccessed }"><p v-if="student.hasAccessed">Yes</p><p v-else>No</p></td>
        <td v-show="mode == 'details'" :class="{ success: student.complete }"><p v-if="student.complete">Yes</p><p v-else>No</p></td>
        <td v-show="mode == 'details'"><p v-if="student.hasAccessed">{{ student.lastAccessed }}</p></td>
    </tr>
</template>

<script>
    import 'axios';

    export default {
        data () {
            return {
                checkedStatus: false,
                currentGroupId: this.student.groupId,
                editGroupId: this.student.groupId
            }
        },

        props: [
            'checked',
            'filterGroup',
            'filterText',
            'groups',
            'mode',
            'student'
        ],

        watch: {
            checked () {
                this.checkedStatus = this.checked;
            }
        },

        methods: {
            // when group dropdown is changed, persist to database
            changedGroup() {
                if (this.editGroupId != this.currentGroupId) {
                    axios.put('student/' + this.student.id + '/group', {
                        groupId: this.editGroupId
                    }).then(response => {
                        this.currentGroupId = response.data;
                        this.editGroupId = response.data;
                    });
                }
            },

            // when checkbox is checked, store in parent array
            checkboxChanged() {
                if (this.checkedStatus) {
                    this.$emit('checked');
                } else {
                    this.$emit('unchecked');
                }
            },

            // returns link for rating the row's student for given round
            getRateLink(roundNumber) {
                return window.sciReflect.baseUrl + '/r/' + roundNumber + '/rate/' + this.student.id;
            },

            // only show row if it fits filter criteria
            showRow() {
                let filterGroupResult = false;
                if (this.filterGroup == this.currentGroupId || this.filterGroup == 'all') {
                    filterGroupResult = true;
                } else if (this.filterGroup == 'null' && this.currentGroupId == null) {
                    filterGroupResult = true;
                }

                if (filterGroupResult == true) {
                    if (this.filterText == '' || 
                            (this.student.name != '' && this.student.name.toLowerCase().includes(this.filterText.toLowerCase()))
                        ) {
                        return true;
                    }
                }

                return false;
            }
        }

    }
</script>
