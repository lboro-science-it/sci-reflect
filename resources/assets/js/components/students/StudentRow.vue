<template>
    <tr v-show="showRow()">
        <td><input type="checkbox"
                   v-model="checked"
                   v-on:change="checkboxChanged"
                   v-bind:true-value="true"
                   v-bind:false-false="false">
        </td>
        <td>
            <select v-model="editGroupId" v-on:change="changedGroup">
                <option value="null">No group</option>
                <option v-for="group in groups" :value="group.id">{{ group.name }}</option>
            </select>
        </td>
        <td>{{ student.name }}</td>
        <td>{{ student.email }}</td>
        <td class="todo"><p v-if="student.currentRoundNumber">{{ student.currentRoundNumber }}</p><p v-else>Complete</p></td>
        <td v-for="round in student.rounds" class="todo">{{ round.completion }}</td>
        <td class="todo"><p v-if="student.hasAccessed">Yes</p><p v-else>No</p></td>
        <td class="todo"><p v-if="student.complete">Yes</p><p v-else>No</p></td>
        <td class="todo"><p v-if="student.hasAccessed">{{ student.lastAccessed }}</p></td>
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
            'student'
        ],

        methods: {
            // when group drop down is changed, persist to database
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

            // only show row if it fits filter criteria
            showRow() {
                let filterGroupResult = false;
                if (this.filterGroup == this.currentGroupId || this.filterGroup == 'all') {
                    filterGroupResult = true;
                } else if (this.filterGroup == 'null' && this.currentGroupId == null) {
                    filterGroupResult = true;
                }

                if (filterGroupResult == true) {
                    if (this.student.name.toLowerCase().includes(this.filterText.toLowerCase()) || this.filterText == '') {
                        return true;
                    }
                }

                return false;
            }
        },

    }
</script>
