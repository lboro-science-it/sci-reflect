<template>
    <tr v-show="showRow()">
        <td>
            <select v-model="currentGroupId" v-on:change="changedGroup">
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
            changedGroup() {
                console.log('ok the group has changed');
            },

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

        mounted () {

        }
    }
</script>
