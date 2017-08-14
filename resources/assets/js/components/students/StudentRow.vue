<template>
    <tr v-show="showRow()">
        <td><input type="checkbox"
                   v-model="checked"
                   v-on:change="checkboxChanged"
                   v-bind:true-value="true"
                   v-bind:false-false="false">
        </td>
        <td>{{ student.name }}</td>
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
                <a class="btn btn-success" :href="getRateLink(round.roundNumber)">rate</a>
                ** todo only show if can rate **
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
                checked: false,
                currentGroupId: this.student.groupId,
                editGroupId: this.student.groupId
            }
        },

        props: [
            'filterGroup',
            'filterText',
            'groups',
            'mode',
            'student'
        ],

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
                if (this.checked) {
                    this.$emit('checked', this.student.id);
                } else {
                    this.$emit('unchecked', this.student.id);
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
        },

    }
</script>
